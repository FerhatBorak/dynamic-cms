<?php

namespace App\Filament\Resources\SiteFieldResource\Pages;

use App\Filament\Resources\SiteFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiteFields extends ListRecords
{
    protected static string $resource = SiteFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
