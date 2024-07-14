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

        $this->fillForm();

        $this->previousUrl = url()->previous();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $categoryId = request()->query('category') ?? session('content_category_id');
        if (!$categoryId) {
            throw new \Exception('Category ID is required.');
        }
        $data['category_id'] = $categoryId;
        return $data;
    }

    protected function beforeCreate(): void
    {
        $categoryId = request()->query('category') ?? session('content_category_id');
        if (!$categoryId || !Category::find($categoryId)) {
            Notification::make()
                ->title('Error')
                ->body('Invalid or missing category ID.')
                ->danger()
                ->send();

            $this->halt();
        }
    }



    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index', ['category' => $this->record->category_id]);
    }

    protected function afterCreate(): void
    {
        session()->forget('content_category_id');
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
