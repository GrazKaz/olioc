<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Info extends Page
{
    protected string $view = 'filament.pages.info';

    public function getTitle(): string
    {
        return __('Information');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
