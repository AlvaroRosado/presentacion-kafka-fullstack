<?php

namespace App\Modules\Shared\Application;

use App\Modules\Shared\Domain\Message;

interface CommandBus
{
    public function dispatch(Command $message, ?Message $causation = null): void;
}
