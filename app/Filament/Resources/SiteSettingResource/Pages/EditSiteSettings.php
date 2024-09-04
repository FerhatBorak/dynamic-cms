<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\SiteSetting;

class EditSiteSettings extends EditRecord
{
    protected static string $resource = SiteSettingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('save')
                ->action('save'),
        ];
    }

    public function getRecord(): SiteSetting
    {
        return SiteSetting::firstOrCreate([]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $settings = collect($data['settings'])->mapWithKeys(function ($item) {
            return [$item['key'] => $item['value']];
        })->toArray();

        return ['settings' => $settings];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $settings = collect($data['settings'] ?? [])->map(function ($value, $key) {
            return [
                'key' => $key,
                'value' => $value,
                'type' => $this->guessFieldType($value),
            ];
        })->values()->toArray();

        return ['settings' => $settings];
    }

    private function guessFieldType($value): string
    {
        if (is_numeric($value)) return 'number';
        if (strtotime($value) !== false) return 'date';
        if (strlen($value) > 255) return 'textarea';
        return 'text';
    }
}
