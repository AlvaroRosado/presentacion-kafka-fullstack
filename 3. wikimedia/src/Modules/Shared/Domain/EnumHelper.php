<?php

namespace App\Modules\Shared\Domain;

trait EnumHelper
{
    /**
     * @return array<string, int|string>
     */
    public static function toArray(): array
    {
        $choices = [];

        foreach (self::cases() as $case) {
            if ($case instanceof \BackedEnum) {
                $choices[$case->name] = $case->value;

                continue;
            }
            $choices[$case->name] = $case->name;
        }

        return $choices;
    }

    public function equals(self $enum): bool
    {
        if ($enum instanceof \BackedEnum) {
            return $enum->value === $this->value;
        }

        return $enum->name === $this->name;
    }

    public function equalsOneOf(array $enums): bool
    {
        foreach ($enums as $value) {
            if ($this->equals($value)) {
                return true;
            }
        }

        return false;
    }
}
