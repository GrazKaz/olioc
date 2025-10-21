<?php

namespace App\Filament\Pages\Auth;

use App\Models\Commune;
use App\Models\County;
use App\Models\User;
use Filament\Auth\Pages\Register as BaseRegister;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Auth\Events\Registered;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\SimplePage;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\RenderHook;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\View\PanelsRenderHook;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;
use LogicException;

/**
 * @property-read Action $loginAction
 * @property-read Schema $form
 */
class Register extends BaseRegister
{
    use CanUseDatabaseTransactions;
    use WithRateLimiting;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    /**
     * @var class-string<Model>
     */
    protected string $userModel;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getUsernameFormComponent(),
                $this->getNameFormComponent(),
                $this->getSurnameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getCountyFormComponent(),
                $this->getCommuneFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                Action::make('Insert register data')
                    ->label('Insert register data')
                    ->icon('heroicon-o-arrow-uturn-up')
                    ->action(function ($livewire) {
                        $data = User::factory()->make()->toArray();

                        $county = County::all()->random()->id;
                        $commune = Commune::where('county_id', $county)->get()->random()->id;

                        $data['county_id'] = $county;
                        $data['commune_id'] = $commune;

                        $data['password'] = 'abcd1234';
                        $data['passwordConfirmation'] = 'abcd1234';

                        $livewire->form->fill($data);
                    })
                    ->visible(function () {
                        return app()->environment('local');
                    })
            ]);
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('Username'))
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->autofocus();
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('Name'))
            ->required()
            ->maxLength(255);
    }

    protected function getSurnameFormComponent(): Component
    {
        return TextInput::make('surname')
            ->label(__('Surname'))
            ->required()
            ->maxLength(255);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::auth/pages/register.form.email.label'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getCountyFormComponent(): Component
    {
        return Select::make('county_id')
            ->label(__('County'))
            ->options(County::pluck('name', 'id'))
            ->required()
            ->live()
            ->afterStateUpdated(function (Set $set) {
                $set('commune_id', null);
            });
    }

    protected function getCommuneFormComponent(): Component
    {
        return Select::make('commune_id')
            ->label(__('Commune'))
            ->options(function (Get $get) {
                return Commune::where('county_id', $get('county_id'))->pluck('name', 'id');
            })
            ->hint(function ($component) {
                return new HtmlString(Blade::render('<x-filament::loading-indicator class="loading" wire:loading wire:target="data.county_id" />'));
            })
            ->extraInputAttributes(function ($component) {
                return [
                    'wire:loading.attr' => 'disabled',
                    'wire:target' => 'data.county_id',
                ];
            });
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::auth/pages/register.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            ->showAllValidationMessages()
            ->dehydrateStateUsing(fn($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::auth/pages/register.form.password.validation_attribute'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::auth/pages/register.form.password_confirmation.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    protected function handleRegistration(array $data): Model
    {
        return $this->getUserModel()::create([...$data,
            'role' => 1
        ]);
    }
}
