<?php

namespace App\Filament\Resources\CategoryFieldGroupResource\Pages;

use App\Filament\Resources\CategoryFieldGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoryFieldGroups extends ListRecords
{
    protected static string $resource = CategoryFieldGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
