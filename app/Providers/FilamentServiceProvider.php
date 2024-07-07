<?php

namespace App\Providers;

use App\Models\Category;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\ServiceProvider;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\ContentResource;
use App\Filament\Resources\FieldTypeResource;

class FilamentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerNavigationItems($this->getNavigationItems());
        });
    }

    protected function getNavigationItems(): array
    {
        $items = [];

        $categories = Category::all();

        foreach ($categories as $category) {
            $items[] = NavigationItem::make($category->name)
                ->icon('heroicon-o-document-text')
                ->url(ContentResource::getUrl('index', ['category' => $category->id]))
                ->isActiveWhen(fn (): bool => request()->input('category') == $category->id);
        }

        return $items;
    }
}
