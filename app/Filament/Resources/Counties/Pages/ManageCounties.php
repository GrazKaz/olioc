<?php

namespace App\Filament\Resources\Counties\Pages;

use App\Filament\Resources\Counties\CountyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCounties extends ManageRecords
{
    protected static string $resource = CountyResource::class;

    public function getTitle(): string
    {
        return __('Counties');
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
