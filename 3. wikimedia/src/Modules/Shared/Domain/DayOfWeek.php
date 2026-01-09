<?php

namespace App\Modules\Shared\Domain;

enum DayOfWeek: string
{
    case MONDAY = 'monday';
    case TUESDAY = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY = 'thursday';
    case FRIDAY = 'friday';
    case SATURDAY = 'saturday';
    case SUNDAY = 'sunday';

    public static function label(): array
    {
        return [
            'Monday' => self::MONDAY->value,
            'Tuesday' => self::TUESDAY->value,
            'Wednesday' => self::WEDNESDAY->value,
            'Thursday' => self::THURSDAY->value,
            'Friday' => self::FRIDAY->value,
            'Saturday' => self::SATURDAY->value,
            'Sunday' => self::SUNDAY->value,
        ];
    }
}
