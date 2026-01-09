<?php

namespace App\Modules\Shared\Domain;

interface Lock
{
    public function acquire(): bool;

    public function refresh(?float $ttl = null): void;

    public function isAcquiredByCurrentInstance(): bool;

    public function isAcquiredBySomeone(): bool;

    public function release(): void;

    public function isExpired(): bool;

    public function getRemainingLifetime(): ?float;
}
