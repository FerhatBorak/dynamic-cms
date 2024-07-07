<?php

namespace App\Filament\Resources\ContentResource\Pages;

use App\Filament\Resources\ContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContent extends EditRecord
{
    protected static string $resource = ContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['category_id'] = $this->record->category_id;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure that the 'fields' key exists in the data
        if (!isset($data['fields'])) {
            $data['fields'] = [];
        }
        return $data;
    }
}
