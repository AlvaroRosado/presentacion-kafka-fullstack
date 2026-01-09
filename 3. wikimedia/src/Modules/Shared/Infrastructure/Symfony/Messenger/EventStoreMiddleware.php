<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Messenger;

use App\Modules\Shared\Domain\Event;
use App\Modules\Shared\Domain\EventStoreRepository;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;

class EventStoreMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EventStoreRepository $eventStoreRepository,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if (
            !$message instanceof Event
            || $envelope->last(ReceivedStamp::class)
            || is_null($message->streamName())
            || is_null($message->streamId())
        ) {
            return $stack->next()->handle($envelope, $stack);
        }

        $this->eventStoreRepository->add(
            $message
        );

        return $stack->next()->handle($envelope, $stack);
    }
}
