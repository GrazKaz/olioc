<?php

namespace App\Filament\Resources\Communes\Pages;

use App\Filament\Resources\Communes\CommuneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCommunes extends ManageRecords
{
    protected static string $resource = CommuneResource::class;

    public function getTitle(): string
    {
        return __('Communes');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
