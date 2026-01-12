<?php

namespace App\Modules\Wikipedia\Domain;

use App\Modules\Shared\Domain\Event;

final class WikipediaChangedOccurred extends Event
{
    public function __construct(
        public readonly string $title,
        public readonly string $user,
        public readonly bool $bot,
        public readonly string $wiki,
        public readonly int $timestamp,
        public readonly string $url,
        public readonly int $diffSize,
        public readonly string $comment,
        public readonly string $type,
        public readonly int $namespace
    ) {
        parent::__construct();
    }

    public function identifier(): string
    {
        return 'wikipedia.change.occurred';
    }
}
