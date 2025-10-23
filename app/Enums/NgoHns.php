<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NgoHns: int implements HasLabel
{
    case EMPTY = 0;
    case HNS = 1;
    case NGO = 2;


    public function getLabel(): ?string
    {
        return match ($this) {
            self::HNS => __('HNS'),
            self::NGO => __('NGO'),
            self::EMPTY => __('--'),

        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::HNS => 'primary',
            self::NGO => 'success',
            self::EMPTY => 'info',

        };
    }
}
