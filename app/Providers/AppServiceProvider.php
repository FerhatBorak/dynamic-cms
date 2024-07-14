<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Panel; // Bu satırı ekleyin
use Filament\Facades\Filament; // Bu satırı da ekleyin
use App\Models\Language;
use App\Observers\LanguageObserver;

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

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                'Content Management',
                'System',
            ]);
        });

        Language::observe(LanguageObserver::class);
    }
}
