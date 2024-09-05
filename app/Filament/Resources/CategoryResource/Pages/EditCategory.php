<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Category;
use App\Models\FieldType;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $data = $this->form->getState();
        \Log::info('Form data in EditCategory:', $data);

        try {
            $this->callHook('beforeValidate');
            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $model = $this->getRecord();
            $model->update($data);

            if (isset($data['translations'])) {
                foreach ($data['translations'] as $locale => $translation) {
                    $model->translations()->updateOrCreate(
                        ['locale' => $locale],
                        [
                            'name' => $translation['name'],
                            'slug' => $translation['slug'],
                            'description' => $translation['description'] ?? null,
                        ]
                    );
                }
            }

            // Yeni özellikler için ek işlemler
            $this->handleMetaFields($model, $data);
            $this->handleCloneFields($model, $data);

            $this->callHook('afterSave');
        } catch (\Exception $e) {
            \Log::error('Error in save method: ' . $e->getMessage());
            Notification::make()
                ->title('Error')
                ->body('There was an error saving the category. Please try again.')
                ->danger()
                ->send();

            return;
        }

        if ($shouldSendSavedNotification) {
            $this->getSavedNotification()?->send();
        }

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl);
        }
    }

    protected function handleMetaFields($model, $data)
    {
        if (!empty($data['include_meta'])) {
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
                $model->fields()->updateOrCreate(
                    ['slug' => $field['slug']],
                    $field
                );
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        \Log::info('Raw Form Data in EditCategory:', $data);

        $defaultLocale = config('app.fallback_locale', 'en');
        if (isset($data['translations'][$defaultLocale])) {
            $data['name'] = $data['translations'][$defaultLocale]['name'];
            $data['slug'] = $data['translations'][$defaultLocale]['slug'];
            $data['description'] = $data['translations'][$defaultLocale]['description'] ?? null;
        }

        \Log::info('Mutated Form Data in EditCategory:', $data);

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['translations'] = $this->record->translations->keyBy('locale')->map(function ($translation) {
            return [
                'name' => $translation->name,
                'slug' => $translation->slug ?? Str::slug($translation->name), // Eğer slug boşsa, isimden oluştur
                'description' => $translation->description,
            ];
        })->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->getRecord();
        $record->load('translations');
        \Log::info('Category After Save:', $record->fresh()->toArray());
        \Log::info('Category Translations After Save:', $record->translations->toArray());
    }
}
