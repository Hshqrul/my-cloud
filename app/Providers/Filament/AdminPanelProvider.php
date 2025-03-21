<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Storage;
use App\Filament\Pages\Auth\CustomLogin;
use Awcodes\LightSwitch\Enums\Alignment;
use Illuminate\Validation\Rules\Password;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use App\Filament\Pages\Auth\CustomRegister;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Clusters\AccountSetting\Pages\Profile;
use Termwind\Enums\Color as EnumsColor;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomLogin::class)
            ->registration(CustomRegister::class)
            ->passwordReset(RequestPasswordReset::class)
            ->profile()
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()->url(fn (): string => Profile::getUrl())
                // \Filament\Navigation\MenuItem::make()
                // ->label('Account Settings')
                // ->url(fn (): string => Profile::getUrl())
                // ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->colors([
                'primary' => EnumsColor::SLATE_700,
            ])
            ->darkModeBrandLogo(fn () => view('filament.logo'))
            ->brandLogoHeight('3.5rem')
            ->sidebarCollapsibleOnDesktop()
            ->topNavigation()
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
            ])
            ->plugins([
                LightSwitchPlugin::make()
                    ->enabledOn([
                        'auth.email',
                        'auth.login',
                        'auth.password',
                        // 'auth.profile',
                        'auth.register',
                    ])
                    ->position(Alignment::TopCenter),
                // BreezyCore::make()
                //     ->myProfile(
                //         shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                //         userMenuLabel: 'My Profile', // Customizes the 'account' link label in the panel User Menu (default = null)
                //         shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                //         navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
                //         hasAvatars: true, // Enables the avatar upload form component (default = false)
                //         slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                //     )
                //     ->passwordUpdateRules(
                //         rules: [Password::default()->mixedCase()->uncompromised(3)], // you may pass an array of validation rules as well. (default = ['min:8'])
                //         requiresCurrentPassword: true, // when false, the user can update their password without entering their current password. (default = true)
                //     )
            ]);
    }
}
