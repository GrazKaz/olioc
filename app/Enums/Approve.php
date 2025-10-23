<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Approve: int implements HasLabel
{
    case YES = 1;
    case NO = 2;
    case PENDING = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::YES => __('Yes'),
            self::NO => __('No'),
            self::PENDING => __('Pending'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::YES => 'primary',
            self::NO => 'success',
            self::PENDING => 'info',
        };
    }
}
