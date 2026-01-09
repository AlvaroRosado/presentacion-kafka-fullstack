<?php

namespace App\Modules\Shared\Domain;

class EntityNotFoundException extends \DomainException
{
    private ?array $queryDebugInfo = null;

    public static function forEntity(string $entityClass, array $queryDebugInfo = []): self
    {
        $exception = new self(
            sprintf('Entity "%s" not found', $entityClass)
        );

        $exception->queryDebugInfo = $queryDebugInfo;

        return $exception;
    }

    public function getQueryDebugInfo(): ?array
    {
        return $this->queryDebugInfo;
    }
}
