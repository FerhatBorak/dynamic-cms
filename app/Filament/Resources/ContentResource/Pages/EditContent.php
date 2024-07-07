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
        $data['fields'] = $this->record->fields;
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Ensure fields are properly formatted
        $data['fields'] = is_array($data['fields']) ? $data['fields'] : [];
        return $data;
    }
}
