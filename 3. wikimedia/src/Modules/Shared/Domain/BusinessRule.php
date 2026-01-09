<?php

namespace App\Modules\Shared\Domain;

interface BusinessRule
{
    public function isBroken(): bool;

    public function message(): string;
}
