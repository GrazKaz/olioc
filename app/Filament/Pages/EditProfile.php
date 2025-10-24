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

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    public function getTitle(): string
    {
        return __('Edit profile');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public $user;

    public ?array $profileData = [];
    public ?array $addressData = [];
    public ?array $passwordData = [];

    protected string $view = 'filament.pages.edit-profile';

    public function mount(): void
    {
        $this->user = auth()->user();

        $this->profileForm->fill($this->user->only(['username', 'name', 'surname', 'email', 'phone_number']));
    }

    public function profileForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make(__('Basic information'))
                    ->columns(2)
                    ->description(__('Update the basic information.'))
                    ->aside()
                    ->schema([
                        TextEntry::make('username')
                            ->label(__('Username'))
                            ->state(fn ($record): string => $this->user->username)
                            ->extraAttributes(['class' => 'placeholder']),
                        TextEntry::make('role')
                            ->label(__('Role'))
                            ->state(fn ($record): string => $this->user->role->getLabel())
                            ->extraAttributes(['class' => 'placeholder']),
                        TextEntry::make('created_at')
                            ->label(__('Account created at'))
                            ->state(function () {
                                return date('d.m.Y H:i:s', strtotime($this->user->created_at));
                            })
                            ->extraAttributes(['class' => 'placeholder']),
                        TextEntry::make('last_login_at')
                            ->label(__('Last login at'))
                            ->state(function () {
                                return ($this->user->last_login_at) ? date('d.m.Y H:i:s', strtotime($this->user->last_login_at)) : __('none');
                            })
                            ->extraAttributes(['class' => 'placeholder']),
                        TextInput::make('name')
                            ->label(__('First name'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('surname')
                            ->label(__('Surname'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('email')
                            ->label('E-mail')
                            ->email()
                            ->maxLength(255)
                            ->required()
                            ->unique(User::class, ignorable: $this->user),
                        TextInput::make('phone_number')
                            ->label('Phone number')
                            ->maxLength(255)
                            ->required(),
                    ]),
            ])
            ->statePath('profileData');
    }

    public function locationForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make(__('Location information'))
                    ->description(__('Update the office location information and address.'))
                    ->aside()
                    ->schema([
                        Group::make()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('county_id')
                                    ->label(__('County TERYT'))
                                    ->state(fn ($record): string => $this->user->county->id)
                                    ->extraAttributes(['class' => 'placeholder']),
                                TextEntry::make('county')
                                    ->label(__('County'))
                                    ->state(fn ($record): string => $this->user->county->name)
                                    ->extraAttributes(['class' => 'placeholder']),
                            ]),
                        Group::make()
                            ->columns(3)
                            ->schema([
                                TextEntry::make('commune_id')
                                    ->label(__('Commune TERYT'))
                                    ->state(fn ($record): string => $this->user->commune->id)
                                    ->extraAttributes(['class' => 'placeholder']),
                                TextEntry::make('office')
                                    ->label(__('Office'))
                                    ->state(fn ($record): string => $this->user->commune->office)
                                    ->extraAttributes(['class' => 'placeholder']),
                                TextEntry::make('commune')
                                    ->label(__('Commune'))
                                    ->state(fn ($record): string => $this->user->commune->name)
                                    ->extraAttributes(['class' => 'placeholder']),
                            ])
                            ->visible(function() {
                                return $this->user->office_type == 'G';
                            }),
                    ]),
            ])
            ->statePath('locationData');
    }

    public function addressForm(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make(__('Basic information'))
                    ->columns(2)
                    ->description(__('Update the office address.'))
                    ->aside()
                    ->schema([
                        TextInput::make('street')
                            ->label(__('Street'))
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('street_number')
                            ->label(__('Street number'))
                            ->maxLength(30)
                            ->required(),
                        TextInput::make('postal_code')
                            ->label(__('Postal code'))
                            ->afterLabel('Format 99-999')
                            ->regex('/^[0-9]{2}-[0-9]{3}$/')
                            ->mask('99-999')
                            ->required(),
                        TextInput::make('city')
                            ->label(__('City'))
                            ->maxLength(255)
                            ->required(),
                    ]),
            ])
            ->statePath('addressData');
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

    protected function getProfileFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('saveProfile'),
        ];
    }

    protected function getAddressFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('saveAddress'),
        ];
    }

    protected function getPasswordFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('savePassword'),
        ];
    }

    public function saveProfile(): void
    {
        try {
            $data = $this->profileForm->getState();

            $this->user->update($data);
        }
        catch (Halt $exception) {
            return;
        }

        $this->notify();
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
