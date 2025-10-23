<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OfficeType: string implements HasLabel
{
    case COUNTY = 'P';
    case COMMUNE = 'G';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::COUNTY => __('County'),
            self::COMMUNE => __('Commune'),
        };
    }
}
