<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Storage;
use Termwind\Enums\Color as EnumsColor;
use App\Filament\Pages\Auth\CustomLogin;
use Awcodes\LightSwitch\Enums\Alignment;
use Filament\Navigation\NavigationGroup;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Pages\Auth\CustomRegister;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Clusters\AccountSetting\Pages\Profile;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

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
            // ->passwordReset(RequestPasswordReset::class)
            ->profile()
            ->userMenuItems([
                'profile' => \Filament\Navigation\MenuItem::make()->url(fn(): string => Profile::getUrl())->label(fn(): string => auth()->user()->name)->icon('heroicon-c-user-circle'),
                \Filament\Navigation\MenuItem::make()->label('Go to Website')->url(config('app.url'))->icon('heroicon-c-window'),
                'logout' => \Filament\Navigation\MenuItem::make()->url(fn(): string => filament()->getLogoutUrl())->icon('heroicon-c-arrow-right-start-on-rectangle')->color('danger'),
            ])
            ->colors([
                'primary' => EnumsColor::SLATE_700,
            ])
            ->darkModeBrandLogo(fn() => view('filament.logo'))
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
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(__('menu.nav_group.access'))
                    ->icon('heroicon-o-user-group')
                    ->collapsible(true),
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
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
            ]);
    }
}
