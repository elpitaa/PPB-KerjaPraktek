<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class MahasiswaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->navigationItems([
                NavigationItem::make('Homepage')
                    ->url('/') // URL untuk mengarahkan ke homepage
                    ->icon('heroicon-o-home') // Ikon untuk menu
                    ->sort(3), // Urutan menu
            ])
            ->id('mahasiswa')
            ->path('mahasiswa')
            ->login()
            ->colors([
                'primary' => Color::Indigo,
            ])
            ->brandName('SiKaPe')
            ->discoverResources(in: app_path('Filament/Mahasiswa/Resources'), for: 'App\\Filament\\Mahasiswa\\Resources')
            ->discoverPages(in: app_path('Filament/Mahasiswa/Pages'), for: 'App\\Filament\\Mahasiswa\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Mahasiswa/Widgets'), for: 'App\\Filament\\Mahasiswa\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ->authGuard('mahasiswa')
            ->plugins([
                FilamentEditProfilePlugin::make()
                    // ->slug('m-profile')
                    ->setTitle('My Profile')
                    ->setNavigationLabel('My Profile')
                    ->setIcon('heroicon-o-user')
                    ->shouldShowEditProfileForm(false)
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowSanctumTokens(false)
                    ->shouldShowBrowserSessionsForm(false)
                    ->shouldShowAvatarForm(false)
            ]);
    }
}
