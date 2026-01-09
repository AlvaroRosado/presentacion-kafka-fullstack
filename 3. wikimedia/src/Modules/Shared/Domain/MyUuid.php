<?php

namespace App\Modules\Shared\Domain;

use Symfony\Component\Uid\Uuid;

class MyUuid
{
    private Uuid $value;

    private function __construct(Uuid $uuid)
    {
        $this->value = $uuid;
    }

    public static function generate(): static
    {
        return new static(Uuid::v7());
    }

    public static function fromString(string $uuid): static
    {
        if (empty($uuid)) {
            throw new \InvalidArgumentException('UUID cannot be empty');
        }

        if (!Uuid::isValid($uuid)) {
            throw new \InvalidArgumentException(sprintf('The string "%s" is not a valid UUID', $uuid));
        }

        return new static(Uuid::fromString($uuid));
    }

    public function toString(): string
    {
        return $this->value->__toString();
    }

    public function toBinary(): Uuid
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value->equals($other->value);
    }
}
