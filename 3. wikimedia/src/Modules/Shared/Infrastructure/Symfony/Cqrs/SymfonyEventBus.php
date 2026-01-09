<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Cqrs;

use App\Modules\Shared\Application\EventBus;
use App\Modules\Shared\Domain\AggregateRoot;
use App\Modules\Shared\Domain\Event;
use App\Modules\Shared\Domain\Message;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

class SymfonyEventBus implements EventBus
{
    public function __construct(
        private MessageBusInterface $eventBus,
        private MessageBusInterface $immediateEventBus,
    ) {
    }

    public function publishAll(array $events, ?Message $causation = null, bool $immediate = false): void
    {
        foreach ($events as $event) {
            $this->publish($event, $causation);
        }
    }

    public function publish(Event $event, ?Message $causation = null, bool $immediate = false): void
    {
        try {
            $this->dispatchEvent($event, $causation, $immediate);
        } catch (HandlerFailedException $e) {
            throw $this->unwrapException($e);
        }
    }

    private function dispatchEvent(Event $event, ?Message $causation = null, bool $immediate = false): void
    {
        if ($causation) {
            $event = $event->asResponseTo($causation);
        }

        $stamps = [];

        if ($immediate) {
            $this->immediateEventBus->dispatch($event, $stamps);
        } else {
            $stamps[] = new DispatchAfterCurrentBusStamp();
            $this->eventBus->dispatch($event, $stamps);
        }
    }

    private function unwrapException(HandlerFailedException $exception): \Throwable
    {
        while ($exception instanceof HandlerFailedException) {
            $exception = $exception->getPrevious() ?? $exception;
        }

        return $exception;
    }

    public function publishFromAggregate(AggregateRoot $aggregateRoot, bool $immediate = false): void
    {
        $events = $aggregateRoot->pullDomainEvents();
        $this->publishAll($events);
    }
}
