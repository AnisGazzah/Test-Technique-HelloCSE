<?php

namespace App\Enums;

enum ProfileStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public static function all(): array
    {
        return self::cases() ;
    }

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => __('profile.status.active'),
            self::INACTIVE => __('profile.status.inactive'),
        };
    }

}
