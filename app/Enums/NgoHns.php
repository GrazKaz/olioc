<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NgoHns: int implements HasLabel
{
    case EMPTY = 0;
    case NGO = 1;
    case HNS = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::NGO => 'NGO',
            self::HNS => 'HNS',
            self::EMPTY => __('-none-'),

        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::EMPTY => 'info',
            self::NGO => 'success',
            self::HNS => 'primary',
        };
    }
}
