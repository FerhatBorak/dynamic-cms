<?php

namespace App\Filament\Resources\SiteFieldResource\Pages;

use App\Filament\Resources\SiteFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteField extends EditRecord
{
    protected static string $resource = SiteFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
