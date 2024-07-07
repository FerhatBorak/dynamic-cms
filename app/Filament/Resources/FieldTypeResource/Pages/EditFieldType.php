<?php

namespace App\Filament\Resources\FieldTypeResource\Pages;

use App\Filament\Resources\FieldTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFieldType extends EditRecord
{
    protected static string $resource = FieldTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
