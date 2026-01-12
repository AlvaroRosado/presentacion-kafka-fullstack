<?php

namespace App\Modules\Shared\Domain;

abstract class Event extends Message
{
    public function streamName(): ?string
    {
        return null;
    }

    public function streamId(): ?string
    {
        return null;
    }

    public function version(): ?string
    {
        return null;
    }
}
