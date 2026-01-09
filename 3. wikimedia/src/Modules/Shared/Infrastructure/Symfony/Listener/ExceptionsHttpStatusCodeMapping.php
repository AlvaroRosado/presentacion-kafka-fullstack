<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Listener;

use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface as SymfonyRequestException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ExceptionsHttpStatusCodeMapping
{
    private const int DEFAULT_STATUS_CODE = SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR;

    /**
     * Registration of common exceptions.
     */
    private array $exceptions = [
        NotFoundHttpException::class => SymfonyResponse::HTTP_NOT_FOUND,
    ];

    public function register(string $exceptionClass, int $statusCode): void
    {
        $this->exceptions[$exceptionClass] = $statusCode;
    }

    public function statusCodeFor(\Throwable $e): int
    {
        if ($e instanceof SymfonyRequestException) {
            // Symfony has raised an error due to a malformed request.
            return SymfonyResponse::HTTP_BAD_REQUEST;
        }

        return $this->exceptions[$e::class] ?? self::DEFAULT_STATUS_CODE;
    }
}
