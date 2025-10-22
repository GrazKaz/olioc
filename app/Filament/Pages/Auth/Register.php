<?php

namespace App\Filament\Pages\Auth;

use App\Http\Responses\RegisterResponse;
use App\Models\Commune;
use App\Models\County;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Auth\Events\Registered;
use Filament\Auth\Http\Responses\Contracts\RegistrationResponse;
use Filament\Auth\Pages\Register as BaseRegister;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
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

    protected Width|string|null $maxContentWidth = Width::TwoExtraLarge;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    /**
     * @var class-string<Model>
     */
    protected string $userModel;

    public function register(): ?RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function (): Model {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $this->sendEmailVerificationNotification($user);

        redirect()->to(filament()->getLoginUrl())->with('message', [
            'type' => 'info',
            'text' => __('The account has been created and awaits verification. You will receive an e-mail message after it\'s verified and active.'),
        ]);

        return null;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Wizard\Step::make('Username')
                        ->label(__('Username'))
                        ->icon(Heroicon::OutlinedUser)
                        ->schema([
                            $this->getUsernameFormComponent(),
                            $this->getNameFormComponent(),
                            $this->getSurnameFormComponent(),
                            $this->getEmailFormComponent(),
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
                        ]),
                    Wizard\Step::make('Location')
                        ->label(__('Location'))
                        ->icon(Heroicon::OutlinedMapPin)
                        ->schema([
                            $this->getCountyFormComponent(),
                            $this->getCommuneFormComponent(),
                            $this->getLocationInfoFormComponent(),

                        ]),
                    Wizard\Step::make('Password')
                        ->label(__('Password'))
                        ->icon(Heroicon::OutlinedLockClosed)
                        ->schema([
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                ])
                ->previousAction(
                    fn (Action $action) => $action->label(__('Previous step'))->icon('heroicon-c-arrow-left'),
                )
                ->nextAction(
                    fn (Action $action) => $action->label(__('Next step'))->icon('heroicon-c-arrow-right'),
                )
                ->submitAction(new HtmlString(Blade::render(<<<BLADE
                    <x-filament::button type="submit" wire:submit="register">
                        {{ __('Register') }}

                    </x-filament::button>
                    BLADE)))
                ->contained(false)
            ]);
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('Username (login)'))
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->autofocus();
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('First name'))
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
            ->live()
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

    protected function getLocationInfoFormComponent(): Component
    {
        return TextEntry::make('location_info')
            ->label(__('Chosen location info'))
            ->state(function (Get $get) {

                $str = '---';

                if ($get('county_id') != null)
                {
                    $county = County::where('id', $get('county_id'))->first(['id', 'name']);

                    $str = Blade::render(<<<BLADE
                     <span><span class="font-semibold">{{ __('County') }}:</span> $county->id - $county->name</span>
                    BLADE);
                }

                if ($get('commune_id') != null)
                {
                    $commune = Commune::where('id', $get('commune_id'))->first(['id', 'office', 'name']);

                    $str .= Blade::render(<<<BLADE
                     <span class="ml-6"><span class="font-semibold">{{ __('Commune') }}:</span> $commune->id - $commune->office $commune->name</span>
                    BLADE);
                }

                return new HtmlString($str);
            })
            ->hint(function ($component) {
                return new HtmlString(Blade::render('<x-filament::loading-indicator class="loading" wire:loading wire:target="data.commune_id,data.county_id" />'));
            })
            ->extraAttributes([
                'class' => 'placeholder',
                'wire:loading.attr' => 'disabled',
                'wire:target' => 'data.commune_id,data.county_id',
            ]);
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

    protected function getFormActions(): array
    {
        return [];
    }

    protected function handleRegistration(array $data): Model
    {
        return $this->getUserModel()::create([...$data,
            'role' => 1
        ]);

        // Mail about new account?
    }
}
