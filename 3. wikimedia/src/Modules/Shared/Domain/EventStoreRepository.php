<?php

namespace App\Modules\Shared\Domain;

interface EventStoreRepository
{
    public function ofStream(string $stream, string $streamId, ?\DateTimeInterface $from = null): iterable;

    public function add(Event $event): void;
}
