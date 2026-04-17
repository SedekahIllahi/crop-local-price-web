<?php

namespace App\Enums;

enum RoleEnum: string
{
    case DEVELOPER = 'developer';
    case SUPERADMIN = 'superadmin';
    case ADMIN = 'admin';
    case USER = 'user';

    public static function getID(string $role): int
    {
        return match ($role) {
            self::DEVELOPER->value => 1,
            self::SUPERADMIN->value => 2,
            self::ADMIN->value => 3,
            self::USER->value => 4,
            default => 0,
        };
    }
}
