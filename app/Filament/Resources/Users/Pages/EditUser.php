<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return __('Edit user');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verify-user')
                ->label(__('Verify'))
                ->icon('fas-stamp')
                ->requiresConfirmation()
                ->action(function ($record) {

                    $record->update([
                        'verified' => now(),
                    ]);
                })
                ->visible(fn ($record) => ($record->verified === null)),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.users.index');
    }
}
