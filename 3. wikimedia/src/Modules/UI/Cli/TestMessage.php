<?php

namespace App\Modules\UI\Cli;

use App\Modules\Shared\Domain\Event;

class TestMessage extends Event
{
    public function __construct(
        public string $message,
    ) {
    }

    public function identifier(): string
    {
        return 'test_event';
    }
}
