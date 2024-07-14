<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return CategoryResource::mutateFormDataBeforeCreate($data);
    }

    protected function afterCreate(): void
    {
        $category = $this->record;

        foreach ($this->data['translations'] as $locale => $translation) {
            $category->translations()->create([
                'locale' => $locale,
                'name' => $translation['name'],
                'slug' => $translation['slug'],
                'description' => $translation['description'] ?? null,
            ]);
        }
    }
}
