<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Category;
use App\Models\FieldType;
use Filament\Notifications\Notification;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    public function mount(): void
    {
        parent::mount();
        $this->form->fill();
    }

    protected function afterCreate(): void
    {
        $category = $this->record;

        $this->handleTranslations($category);
        $this->handleMetaFields($category);
        $this->handleCloneFields($category);
    }

    protected function handleTranslations($category): void
    {
        foreach ($this->data['translations'] ?? [] as $locale => $translation) {
            $category->translations()->create([
                'locale' => $locale,
                'name' => $translation['name'],
                'slug' => $translation['slug'],
                'description' => $translation['description'] ?? null,
            ]);
        }
    }

    protected function handleMetaFields($category): void
    {
        if (!empty($this->data['include_meta'])) {
            $fieldTypeId = FieldType::where('slug', 'text')->value('id');

            $metaFields = [
                [
                    'field_type_id' => $fieldTypeId,
                    'name' => 'keyword',
                    'slug' => 'keyword',
                    'label' => 'Meta keyword',
                ],
                [
                    'field_type_id' => $fieldTypeId,
                    'name' => 'description',
                    'slug' => 'description',
                    'label' => 'Meta description',
                ],
            ];

            foreach ($metaFields as $field) {
                $category->fields()->create($field);
            }
        }
    }
    protected function handleCloneFields($category): void
    {
        if (!empty($this->data['clone_from_category'])) {
            $sourceCategory = Category::findOrFail($this->data['clone_from_category']);

            // Hedef kategorinin mevcut alanlarını sil
            $category->fields()->delete();

            // Kaynak kategorinin alanlarını kopyala
            foreach ($sourceCategory->fields as $field) {
                $newField = $field->replicate();
                $newField->category_id = $category->id;

                // Aynı slug'a sahip alan var mı kontrol et
                $slug = $newField->slug;
                $counter = 1;
                while ($category->fields()->where('slug', $slug)->exists()) {
                    $slug = $field->slug . '_' . $counter;
                    $counter++;
                }
                $newField->slug = $slug;

                $newField->save();
            }

            Notification::make()
                ->title('Kategori alanları başarıyla kopyalandı')
                ->success()
                ->send();
        }
    }
}
