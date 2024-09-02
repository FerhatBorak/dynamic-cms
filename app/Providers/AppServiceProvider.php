<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Panel; // Bu satırı ekleyin
use Filament\Facades\Filament; // Bu satırı da ekleyin
use App\Models\Language;
use App\Observers\LanguageObserver;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Support\Facades\Route;
use App\Services\SiteSettingsService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SiteSettingsService::class, function ($app) {
            return new SiteSettingsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        require_once app_path('Helpers/ContentHelper.php');
        require_once app_path('Helpers/LanguageHelper.php');
        require_once app_path('Helpers/CategoryHelper.php');
        view()->composer('*', function ($view) {
            $view->with('siteSettings', app(SiteSettingsService::class));
        });
        Route::middleware([HandleCors::class]);

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                'Content Management',
                'System',
            ]);
        });

        Language::observe(LanguageObserver::class);

    }
}
