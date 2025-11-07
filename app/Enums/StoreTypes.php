<?php

namespace App\Enums;

enum StoreTypes: string
{
    case BasicHealth   = 'basic';
    case HealthAndCare = 'health';
    case SuperStore    = 'super';
    case Medical       = 'medical';
    case NewStore      = 'new';
    case Child         = 'child';

    public static function labels(): array
    {
        return [
            self::BasicHealth->value   => 'Basic Health',
            self::HealthAndCare->value => 'Health and Care',
            self::SuperStore->value    => 'Super Store',
            self::Medical->value       => 'Medical',
            self::Child->value         => 'Child',
            self::NewStore->value      => 'New Store',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::BasicHealth => 'Basic Health',
            self::HealthAndCare => 'Health and Care',
            self::SuperStore => 'Super Store',
            self::Medical => 'Medical',
            self::Child => 'Child',
            self::NewStore => 'New Store',
        };
    }

}
