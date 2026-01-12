<?php

namespace App\Modules\Wikipedia\Infrastructure\Web;

use App\Modules\Shared\Application\EventHandler;
use App\Modules\Wikipedia\Domain\WikipediaChangedOccurred;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;

class OnWikipediaChangeOcurredThenSendSseEvent implements EventHandler
{
    public function __construct(
        private HubInterface $hub,
        private Environment $twig
    ) {}

    public function __invoke(WikipediaChangedOccurred $event): void
    {
        $html = $this->twig->render('website/broadcast/stream_item.html.twig', [
            'title'     => $event->title,
            'user'      => $event->user,
            'bot'       => $event->bot,
            'wiki'      => $event->wiki,
            'timestamp' => $event->timestamp,
            'url'       => $event->url,
            'diffSize'  => $event->diffSize,
            'comment'   => $event->comment,
            'type'      => $event->type,
            'namespace' => $event->namespace
        ]);

        $update = new Update(
            'wikipedia_changes',
            $html
        );

        $this->hub->publish($update);
    }
}
