<?php

namespace App\Providers;

use App\Models\Category;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\LanguageResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\SiteFieldResource;
use App\Filament\Resources\ContentResource;
use App\Filament\Resources\SiteSettingResource;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {
            $this->registerNavigationItems();
        });
    }

    protected function registerNavigationItems(): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        $isSuperAdmin = $user->hasRole('super_admin');

        Filament::registerNavigationItems([
            NavigationItem::make('Genel Bakış')
                ->icon('heroicon-o-home')
                ->url('/admin'),
        ]);
        if ($user->hasPermission('manage_site_fields')) {
            Filament::registerNavigationItems([
                NavigationItem::make('Site Fields')
                    ->icon('heroicon-o-cog')
                    ->url(SiteFieldResource::getUrl('index'))
                    ->group('Yönetim'),
            ]);
        }

        if ($user->hasPermission('manage_site_settings')) {
            Filament::registerNavigationItems([
                NavigationItem::make('Site Ayarları')
                    ->icon('heroicon-o-cog')
                    ->url(SiteSettingResource::getUrl('index'))
                    ->group('Yönetim'),
            ]);
        }
        if ($isSuperAdmin) {
            Filament::registerNavigationItems([
                NavigationItem::make('Category')
                    ->icon('heroicon-o-folder')
                    ->url(CategoryResource::getUrl('index'))
                    ->group('Yönetim'),
                NavigationItem::make('Language')
                    ->icon('heroicon-o-folder')
                    ->url(LanguageResource::getUrl('index'))
                    ->group('Yönetim'),
                NavigationItem::make('User')
                    ->icon('heroicon-o-user')
                    ->url(UserResource::getUrl('index'))
                    ->group('Yönetim'),
                NavigationItem::make('Role')
                    ->icon('heroicon-o-key')
                    ->url(RoleResource::getUrl('index'))
                    ->group('Yönetim'),
            ]);
        }

        $categories = Category::with('translations')->get();

        $categoryItems = [];
        foreach ($categories as $category) {
            if ($isSuperAdmin || $user->hasPermission('edit_' . $category->slug)) {
                $categoryItems[] = NavigationItem::make($category->name)
                    ->icon('heroicon-o-document-text')
                    ->url(ContentResource::getUrl('index', ['category' => $category->id]))
                    ->group('İçerikler');

            }
        }

        if (!empty($categoryItems)) {
            Filament::registerNavigationItems($categoryItems);
        }
    }
}
