<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Role: int implements HasLabel
{
    case USER = 1;
    case SEMIADMIN = 7;
    case ADMINISTRATOR = 10;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::USER => __('User'),
            self::SEMIADMIN => __('Semi-admin'),
            self::ADMINISTRATOR => __('Administrator'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::USER => 'primary',
            self::SEMIADMIN => 'success',
            self::ADMINISTRATOR => 'info',
        };
    }
}
