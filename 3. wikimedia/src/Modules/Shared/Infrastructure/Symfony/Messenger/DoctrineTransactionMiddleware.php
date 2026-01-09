<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Messenger;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class DoctrineTransactionMiddleware implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            $envelope = $stack->next()->handle($envelope, $stack);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();

            return $envelope;
        } catch (\Throwable $exception) {
            $this->entityManager->getConnection()->rollBack();

            if ($exception instanceof HandlerFailedException) {
                // Remove all HandledStamp from the envelope so the retry will execute all handlers again.
                // When a handler fails, the queries of allegedly successful previous handlers just got rolled back.
                throw new HandlerFailedException($exception->getEnvelope()->withoutAll(HandledStamp::class), $exception->getWrappedExceptions());
            }

            throw $exception;
        }
    }
}
