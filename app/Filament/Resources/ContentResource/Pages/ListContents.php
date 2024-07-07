<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->url(fn () => ContentResource::getUrl('create', ['category' => request()->query('category')])),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['category' => request()->query('category')]);
    }

    public function getTitle(): string
    {
        $categoryId = request()->query('category');
        $category = Category::find($categoryId);
        return $category ? $category->name . ' Contents' : 'Contents';
    }
}
