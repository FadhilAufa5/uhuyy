<?php

namespace App\Enums;

enum Roles: string
{
    case SuperAdmin = 'super-admin';
    case User = 'user';

    public static function labels(): array
    {
        return [
            self::SuperAdmin->value => 'Super Admin',
            self::User->value => 'User',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::User => 'User',
        };
    }
}
