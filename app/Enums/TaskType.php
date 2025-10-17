<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TaskType: int implements HasLabel
{
    case WLASNE = 1;
    case ZLECONE = 2;
    case NDJST = 3;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::WLASNE => __('Własne'),
            self::ZLECONE => __('Zlecone'),
            self::NDJST => __('Nie dotyczy JST'),
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::WLASNE => 'primary',
            self::ZLECONE => 'success',
            self::NDJST => 'info',
        };
    }
}
