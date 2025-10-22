<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use App\Http\Responses\LoginResponse;
use Filament\Auth\MultiFactor\Contracts\HasBeforeChallengeHook;
use Filament\Auth\Pages\Login as BaseAuth;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Models\Contracts\FilamentUser;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class Login extends BaseAuth
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getUsernameEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Actions::make([
                    Action::make('auto-login-admin')
                        ->label('Auto login Administrator')
                        ->icon('heroicon-o-arrow-uturn-up')
                        ->color('info')
                        ->extraAttributes(['class' => 'auto-login-btn w-full'])
                        ->action(function () {
                            return redirect(route('dev-login', 'a'));
                        }),
                    Action::make('auto-login-semiadmin')
                        ->label('Auto login Supervisor')
                        ->icon('heroicon-o-arrow-uturn-up')
                        ->color('success')
                        ->extraAttributes(['class' => 'auto-login-btn w-full'])
                        ->action(function () {
                            return redirect(route('dev-login', 's'));
                        }),
                    ])
                    ->visible(function() {
                        return (bool) app()->environment('local');
                    }),
            ]);
    }

    protected function getUsernameEmailFormComponent(): TextInput
    {
        return TextInput::make('username_email')
            ->label(__('Username / Email'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        $login_type = filter_var($data['username_email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $login_type => $data['username_email'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        /** @var SessionGuard $authGuard */
        $authGuard = Filament::auth();

        $authProvider = $authGuard->getProvider(); /** @phpstan-ignore-line */
        $credentials = $this->getCredentialsFromFormData($data);

        $user = $authProvider->retrieveByCredentials($credentials);

        if ((! $user) || (! $authProvider->validateCredentials($user, $credentials))) {
            $this->userUndertakingMultiFactorAuthentication = null;

            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        if (!$user->active) {
            Filament::auth()->logout();

            throw ValidationException::withMessages([
                'data.username_email' => __('The account is locked.'),
            ]);
        }

        if (!$user->verified) {
            Filament::auth()->logout();

            throw ValidationException::withMessages([
                'data.username_email' => __('The account awaits verification.'),
            ]);
        }

        if (
            filled($this->userUndertakingMultiFactorAuthentication) &&
            (decrypt($this->userUndertakingMultiFactorAuthentication) === $user->getAuthIdentifier())
        ) {
            $this->multiFactorChallengeForm->validate();
        } else {
            foreach (Filament::getMultiFactorAuthenticationProviders() as $multiFactorAuthenticationProvider) {
                if (! $multiFactorAuthenticationProvider->isEnabled($user)) {
                    continue;
                }

                $this->userUndertakingMultiFactorAuthentication = encrypt($user->getAuthIdentifier());

                if ($multiFactorAuthenticationProvider instanceof HasBeforeChallengeHook) {
                    $multiFactorAuthenticationProvider->beforeChallenge($user);
                }

                break;
            }

            if (filled($this->userUndertakingMultiFactorAuthentication)) {
                $this->multiFactorChallengeForm->fill();

                return null;
            }
        }

        if (! $authGuard->attemptWhen($credentials, function (Authenticatable $user): bool {
            if (! ($user instanceof FilamentUser)) {
                return true;
            }

            return $user->canAccessPanel(Filament::getCurrentOrDefaultPanel());
        }, $data['remember'] ?? false)) {
            $this->fireFailedEvent($authGuard, $user, $credentials);
            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username_email' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

    public function getSubheading(): string | Htmlable | null
    {
        if (filled($this->userUndertakingMultiFactorAuthentication)) {
            return __('filament-panels::auth/pages/login.multi_factor.subheading');
        }

        if (! filament()->hasRegistration()) {
            return null;
        }

        if (session('message')) $msg = '<x-message type="warning">' . session('message') . '</x-message>';
        else $msg = '';

        return new HtmlString(__('filament-panels::auth/pages/login.actions.register.before') . ' ' . $this->registerAction->toHtml() . Blade::render(<<<BLADE
                    $msg
                    BLADE));
    }
}
