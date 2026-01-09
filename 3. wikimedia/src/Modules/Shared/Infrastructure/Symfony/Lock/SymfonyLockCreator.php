<?php

namespace App\Modules\Shared\Infrastructure\Symfony\Lock;

use App\Modules\Shared\Domain\LockCreator;
use Symfony\Component\Lock\LockFactory as LockFactoryComponent;

final readonly class SymfonyLockCreator implements LockCreator
{
    public function __construct(private LockFactoryComponent $lockFactory)
    {
    }

    public function createLock(string $lockName, ?float $ttl = 300.0): SymfonyLock
    {
        $lock = $this->lockFactory->createLock($lockName, $ttl, false);

        return new SymfonyLock($lock);
    }
}
