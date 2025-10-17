<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Status: int implements HasLabel
{
    case WYS = 1;
    case PWW = 2;
    case ZAW = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::WYS => __('Wysłana'),
            self::PWW => __('Podpisana przez WW'),
            self::ZAW => __('Zawarta'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::WYS => 'primary',
            self::PWW => 'success',
            self::ZAW => 'info',
        };
    }
}
