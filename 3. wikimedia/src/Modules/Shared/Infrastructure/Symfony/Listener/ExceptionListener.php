<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final readonly class ExceptionListener
{
    public function __construct(
        private ExceptionsHttpStatusCodeMapping $exceptionHandler,
    ) {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $status = $this->exceptionHandler->statusCodeFor($exception);

        $request = $event->getRequest();

        if ($request->getRequestFormat() === 'json' || str_starts_with($request->getPathInfo(), '/api')) {
            $data = $this->dataFor($exception, $status);
            $event->setResponse(new JsonResponse($data, $status));
        }
    }

    private function dataFor(\Throwable $exception, int $status): array
    {
        $data = [
            'message' => $exception->getMessage(),
        ];

        if (SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR === $status) {
            $data['trace'] = $exception->getTrace();
        }

        return $data;
    }
}
