<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Insert test data')
                ->label('Insert test data')
                ->icon('heroicon-o-arrow-uturn-up')
                ->outlined()
                ->action(function ($livewire) {
                    $data = User::factory()->make()->toArray();

                    $livewire->form->fill($data);
                })
                ->extraAttributes(['class' => 'custom-action'])
                ->visible(function () {
                    return app()->environment('local');
                }),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if ($data['office_type'] == 'P') $data['commune_id'] = null;

        if ($data['verified']) $data['verified'] = now();
        else $data['verified'] = null;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
