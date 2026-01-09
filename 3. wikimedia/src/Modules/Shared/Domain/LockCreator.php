<?php

namespace App\Modules\Shared\Domain;

interface LockCreator
{
    public function createLock(string $lockName, ?float $ttl = 300.0): Lock;
}
