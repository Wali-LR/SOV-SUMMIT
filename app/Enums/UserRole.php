<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Vendor = 'vendor';
    case Customer = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Vendor => 'Vendor',
            self::Customer => 'Customer',
        };
    }
}
