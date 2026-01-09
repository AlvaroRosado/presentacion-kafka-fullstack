<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Lock;

use App\Modules\Shared\Domain\Lock;
use Symfony\Component\Lock\Lock as LockComponent;
use Symfony\Component\Lock\SharedLockInterface;

final readonly class SymfonyLock implements Lock
{
    public function __construct(private LockComponent|SharedLockInterface $lock)
    {
    }

    public function acquire(): bool
    {
        return $this->lock->acquire();
    }

    public function refresh(?float $ttl = null): void
    {
        $this->lock->refresh($ttl);
    }

    public function isAcquiredByCurrentInstance(): bool
    {
        return $this->lock->isAcquired();
    }

    public function isAcquiredBySomeone(): bool
    {
        if (!$this->lock->acquire()) {
            return true;
        }

        $this->lock->release();

        return false;
    }

    public function release(): void
    {
        $this->lock->release();
    }

    public function isExpired(): bool
    {
        return $this->lock->isExpired();
    }

    public function getRemainingLifetime(): ?float
    {
        return $this->lock->getRemainingLifetime();
    }
}
