<?php

namespace App\Providers;

use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Facades\FilamentView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->filtersLayout(FiltersLayout::AboveContentCollapsible)
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->extremePaginationLinks()
                ->defaultSort('created_at', 'desc');
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
            fn(): string => Blade::render('
                <x-filament::link
                    href="/"
                    tag="a"
                    icon="heroicon-m-arrow-left"
                >
                    Back to Home
                </x-filament::link>
            '),
        );
        
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_REGISTER_FORM_BEFORE,
            fn(): string => Blade::render('
                <x-filament::link
                    href="/"
                    tag="a"
                    icon="heroicon-m-arrow-left"
                >
                    Back to Home
                </x-filament::link>
            '),
        );

        Blade::if('superadmin', function () {
            return auth()->check() && auth()->user()->hasRole('super_admin');
        });
    }
}
