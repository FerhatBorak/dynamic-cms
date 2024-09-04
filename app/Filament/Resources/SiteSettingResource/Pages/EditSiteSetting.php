<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Resources\Pages\EditRecord;
use App\Models\SiteSetting;

class EditSiteSetting extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    public function getTitle(): string
    {
        return 'Site Settings';
    }

    protected function getActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
        return array_merge($data, $settings);
    }



    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
