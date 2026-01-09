<?php

namespace App\Modules\Shared\Domain;

class MessageId
{
    final private function __construct(private string $value)
    {
    }

    public function __toString()
    {
        return $this->value;
    }

    public static function of(string $uuidAsString): self
    {
        MyUuid::fromString($uuidAsString);

        return new static($uuidAsString);
    }

    final public static function nextId(): self
    {
        return static::of(MyUuid::generate()->toString());
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function toString(): string
    {
        return (string) $this;
    }

    public function id(): MyUuid
    {
        return MyUuid::fromString($this->value);
    }
}
