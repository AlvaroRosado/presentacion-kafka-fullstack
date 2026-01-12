<?php

namespace App\Modules\UI\Cli;

use App\Modules\Shared\Application\EventBus;
use App\Modules\Wikipedia\Domain\WikipediaChangedOccurred;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'app:wikipedia:ingest',
    description: 'Lee stream de Wikimedia y despacha al Event Bus con métricas'
)]
class IngestWikipediaStreamCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client,
        private EventBus $eventBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Wikipedia Stream Ingest');
        $io->text('Conectando al stream... (Presiona Ctrl+C para salir)');

        $response = $this->client->request('GET', 'https://stream.wikimedia.org/v2/stream/recentchange', [
            'buffer' => false,
            'timeout' => -1,
        ]);

        $startTime = microtime(true);
        $lastTick = $startTime;
        $eventsInWindow = 0;
        $totalEvents = 0;
        $currentEps = 0;

        foreach ($this->client->stream($response) as $chunk) {

            if ($chunk->isTimeout()) {
                $this->updateMetrics($eventsInWindow, $lastTick, $currentEps);
                continue;
            }

            $content = $chunk->getContent();

            if (empty($content) || !str_starts_with($content, 'data: ')) {
                continue;
            }

            $jsonStr = substr($content, 6);

            try {
                $data = json_decode($jsonStr, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                continue;
            }

            $oldLen = $data['length']['old'] ?? 0;
            $newLen = $data['length']['new'] ?? 0;
            $diff = $newLen - $oldLen;

            $event = new WikipediaChangedOccurred(
                title: $data['title'] ?? 'unknown',
                user: $data['user'] ?? 'unknown',
                bot: $data['bot'] ?? false,
                wiki: $data['wiki'] ?? 'unknown',
                timestamp: $data['timestamp'] ?? time(),
                url: $data['title_url'] ?? '#',
                diffSize: $diff,
                comment: $data['comment'] ?? '',
                type: $data['type'] ?? 'unknown',
                namespace: $data['namespace'] ?? 0
            );

            $this->eventBus->publish($event);

            $totalEvents++;
            $eventsInWindow++;

            $this->updateMetrics($eventsInWindow, $lastTick, $currentEps);

            $botLabel = $event->bot
                ? '<fg=gray;options=bold>[BOT] 🤖</>'
                : '<fg=green;options=bold>[HUM] 👤</>';

            $wikiLabel = str_pad(substr($event->wiki, 0, 15), 15);
            $titleLabel = substr($event->title, 0, 50);

            $output->writeln(sprintf(
                '<fg=yellow>[EPS: %3d]</> <fg=blue>[Tot: %d]</> %s | %s | %s',
                $currentEps,
                $totalEvents,
                $botLabel,
                $wikiLabel,
                $titleLabel
            ));
        }

        return Command::SUCCESS;
    }

    private function updateMetrics(int &$windowEvents, float &$lastTick, int &$currentEps): void
    {
        $now = microtime(true);
        $diff = $now - $lastTick;

        if ($diff >= 1.0) {
            $currentEps = (int) round($windowEvents / $diff);
            $windowEvents = 0;
            $lastTick = $now;
        }
    }
}
