<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        $categoryId = request()->query('category');
        if (!$categoryId || !Category::find($categoryId)) {
            return [];
        }

        return [
            Actions\CreateAction::make()
                ->url(fn () => $this->getResource()::getUrl('create', ['category' => $categoryId])),
        ];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();
        $categoryId = request()->query('category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query;
    }

    public function getTitle(): string
    {
        $categoryId = request()->query('category');
        $category = Category::find($categoryId);
        return $category ? $category->name . ' Contents' : 'Contents';
    }
}
