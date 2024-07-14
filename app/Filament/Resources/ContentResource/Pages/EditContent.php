<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EditContent extends EditRecord
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        Log::info('Updating record', ['data' => $data]);

        // Mevcut kaydı güncelle
        $record->update([
            'category_id' => $data['category_id'],
        ]);

        // Çevirileri güncelle
        foreach ($data['translations'] as $locale => $translationData) {
            $record->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $translationData['title'],
                    'slug' => $translationData['slug'],
                    'fields' => $translationData['fields'] ?? [],
                ]
            );
        }

        Log::info('Record updated', ['record' => $record->toArray()]);

        return $record->fresh();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $content = $this->record;
        $data['id'] = $content->id;
        $data['category_id'] = $content->category_id;
        $data['translations'] = $content->translations->mapWithKeys(function ($translation) {
            return [$translation->locale => [
                'title' => $translation->title,
                'slug' => $translation->slug,
                'fields' => $translation->fields ?? [],
            ]];
        })->toArray();

        return $data;
    }
}
