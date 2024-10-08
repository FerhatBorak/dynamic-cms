<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Panel; // Bu satırı ekleyin
use Filament\Facades\Filament; // Bu satırı da ekleyin
use App\Models\Language;
use App\Models\SiteSetting;
use App\Observers\LanguageObserver;
use App\Observers\SiteSettingObserver;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Support\Facades\Route;
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
        require_once app_path('Helpers/SiteSettingHelper.php');
        require_once app_path('Helpers/HomepageHelper.php');
        require_once app_path('Helpers/ContentHelper.php');
        require_once app_path('Helpers/LanguageHelper.php');
        require_once app_path('Helpers/CategoryHelper.php');



        Route::middleware([HandleCors::class]);

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                'Content Management',
                'System',
            ]);
        });

        Language::observe(LanguageObserver::class);
        SiteSetting::observe(SiteSettingObserver::class);
    }
}
