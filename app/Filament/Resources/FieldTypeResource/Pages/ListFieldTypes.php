<?php

namespace App\Filament\Resources\FieldTypeResource\Pages;

use App\Filament\Resources\FieldTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFieldTypes extends ListRecords
{
    protected static string $resource = FieldTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
