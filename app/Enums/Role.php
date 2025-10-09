<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Role: int implements HasLabel
{
    case USER = 1;
    case PUBLISHER = 5;
    case ADMINISTRATOR = 10;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::USER => __('User'),
            self::PUBLISHER => __('Publisher'),
            self::ADMINISTRATOR => __('Administrator'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::USER => 'primary',
            self::PUBLISHER => 'success',
            self::ADMINISTRATOR => 'info',
        };
    }
}
