<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends Page implements HasForms
{
    use InteractsWithForms;

    public function getTitle(): string
    {
        return __('Change password');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public $user;

    protected string $view = 'filament.pages.change-password';

    public function mount(): void
    {
        $this->user = auth()->user();
    }

    public function passwordForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make(__('Update password'))
                    ->description(__('Ensure your_password is safe.'))
                    ->aside()
                    ->schema([
                        TextInput::make('currentPassword')
                            ->label(__('Current password'))
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->revealable(),
                        TextInput::make('password')
                            ->label(__('New password'))
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                            ->same('passwordConfirmation')
                            ->revealable(),
                        TextInput::make('passwordConfirmation')
                            ->label(__('Confirm password'))
                            ->password()
                            ->required()
                            ->dehydrated(false)
                            ->revealable(),
                    ]),
            ])
            ->statePath('passwordData');
    }

    public function alignActions(): Alignment
    {
        return Alignment::Right;
    }

    protected function getPasswordFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('savePassword'),
        ];
    }

    public function savePassword(): void
    {
        try {
            $data = $this->passwordForm->getState();

            $this->user->update([
                'password' => $data['password'],
            ]);
        }
        catch (Halt $exception) {
            return;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_' . Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->passwordForm->fill();

        $this->notify();
    }

    protected function notify()
    {
        return Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }
}
