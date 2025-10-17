<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Approve: int implements HasLabel
{
    case TAK = 1;
    case NIE = 2;
    case WT = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TAK => __('Tak'),
            self::NIE => __('Nie'),
            self::WT => __('W trakcie'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::TAK => 'primary',
            self::NIE => 'success',
            self::WT => 'info',
        };
    }
}
