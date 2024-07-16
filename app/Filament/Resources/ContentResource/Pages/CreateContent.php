<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Content;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class CreateContent extends CreateRecord
{
    protected static string $resource = ContentResource::class;

    public function mount(): void
    {
        $this->authorizeAccess();

        $categoryId = request()->query('category');
        if (!$categoryId || !Category::find($categoryId)) {
            Notification::make()
                ->title('Error')
                ->body('Invalid or missing category ID.')
                ->danger()
                ->send();

            $this->redirect(ContentResource::getUrl('index'));
        }

        $this->form->fill([
            'category_id' => $categoryId,
        ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['category_id'] = $this->form->getState()['category_id'];
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['category' => $this->record->category_id]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        $content = Content::create([
            'category_id' => $data['category_id'],
        ]);

        foreach ($data['translations'] as $locale => $translation) {
            $content->translations()->create([
                'locale' => $locale,
                'title' => $translation['title'],
                'slug' => $translation['slug'],
                'fields' => $translation['fields'] ?? [],
            ]);
        }

        return $content;
    }
}
