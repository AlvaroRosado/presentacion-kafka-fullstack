<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Cqrs;

use App\Modules\Shared\Application\Command;
use App\Modules\Shared\Application\CommandBus;
use App\Modules\Shared\Domain\Message;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyCommandBus implements CommandBus
{
    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    public function dispatch(Command $message, ?Message $causation = null): void
    {
        try {
            if ($causation) {
                $message->asResponseTo($causation);
            }
            $this->commandBus->dispatch($message);
        } catch (HandlerFailedException $receivedException) {
            $unwrappedException = $receivedException;
            while ($unwrappedException instanceof HandlerFailedException) {
                $unwrappedException = $unwrappedException->getPrevious();
            }

            throw $unwrappedException ?? $receivedException;
        }
    }
}
