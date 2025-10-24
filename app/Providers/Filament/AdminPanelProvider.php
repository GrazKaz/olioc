<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\ChangePassword;
use App\Filament\Pages\EditProfile;
use App\Filament\Pages\Info;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->brandLogo(fn () => view('filament.logo'))
            ->favicon(asset('images/favicon.svg'))
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset()
            ->darkMode(false)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->maxContentWidth('max-w-full')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->navigationGroups([
                NavigationGroup::make(__('Administration')),
                NavigationGroup::make(__('Dictionaries')),
            ])
            ->userMenuItems([
                Action::make('edit-profile')
                    ->label(__('Edit profile'))
                    ->url(fn (): string => EditProfile::getUrl())
                    ->icon('heroicon-o-user'),
                Action::make('change-password')
                    ->label(__('Change password'))
                    ->url(fn (): string => ChangePassword::getUrl())
                    ->icon('heroicon-o-lock-closed'),
                Action::make('information')
                    ->label(__('Information'))
                    ->url(fn (): string => Info::getUrl())
                    ->icon('heroicon-o-information-circle'),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
