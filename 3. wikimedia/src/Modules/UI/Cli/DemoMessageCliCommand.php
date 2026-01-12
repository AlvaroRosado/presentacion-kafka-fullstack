<?php

namespace App\Modules\UI\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:dispatch-message',
    description: 'Dispatch message.',
    aliases: ['app:dispatch-message'],
    hidden: false
)]
class DemoMessageCliCommand extends Command
{
    public function __construct(private MessageBusInterface $messageBus)
    {
        parent::__construct('app:demo-send-message');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->messageBus->dispatch(
            new TestMessage(
                'This is a test message.',
            )
        );

        return Command::SUCCESS;
    }
}
