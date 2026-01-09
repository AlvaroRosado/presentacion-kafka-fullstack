<?php

namespace App\Modules\Shared\Application;

use App\Modules\Shared\Domain\AggregateRoot;
use App\Modules\Shared\Domain\Event;
use App\Modules\Shared\Domain\Message;

interface EventBus
{
    public function publishAll(array $events, ?Message $causation = null, bool $immediate = false): void;
    public function publish(Event $event, ?Message $causation = null, bool $immediate = false): void;
    public function publishFromAggregate(AggregateRoot $aggregateRoot, bool $immediate = false): void;
}
