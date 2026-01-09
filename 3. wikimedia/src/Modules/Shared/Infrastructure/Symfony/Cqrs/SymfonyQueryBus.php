<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Cqrs;

use App\Modules\Shared\Application\Query;
use App\Modules\Shared\Application\QueryBus;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final readonly class SymfonyQueryBus implements QueryBus
{
    public function __construct(
        private MessageBusInterface $queryBus,
    ) {
    }

    public function ask(Query $message): mixed
    {
        try {
            $envelope = $this->queryBus->dispatch($message);
        } catch (HandlerFailedException $receivedException) {
            $unwrappedException = $receivedException;
            while ($unwrappedException instanceof HandlerFailedException) {
                $unwrappedException = $unwrappedException->getPrevious();
            }

            throw $unwrappedException ?? $receivedException;
        }

        /** @var HandledStamp[] $handledStamps */
        $handledStamps = $envelope->all(HandledStamp::class);

        if (!$handledStamps) {
            throw new \LogicException(sprintf('Message of type "%s" was handled zero times. Exactly one handler is expected when using "%s::%s()".', get_debug_type($envelope->getMessage()), self::class, __FUNCTION__));
        }

        if (count($handledStamps) > 1) {
            $handlers = implode(', ', array_map(fn (HandledStamp $stamp): string => sprintf('"%s"', $stamp->getHandlerName()), $handledStamps));

            throw new \LogicException(sprintf('Message of type "%s" was handled multiple times. Only one handler is expected when using "%s::%s()", got %d: %s.', get_debug_type($envelope->getMessage()), self::class, __FUNCTION__, count($handledStamps), $handlers));
        }

        return $handledStamps[0]->getResult();
    }
}
