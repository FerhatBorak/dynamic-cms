<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use App\Models\SiteSetting;
use App\Models\SiteField;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class ManageSiteSettings extends Page
{
    protected static string $resource = SiteSettingResource::class;

    protected static string $view = 'filament.resources.site-setting-resource.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getFormData());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(SiteSettingResource::getFormSchema())
            ->statePath('data');
    }

    public function save()
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }

    protected function getFormData(): array
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $fields = SiteField::pluck('key')->toArray();

        foreach ($fields as $field) {
            if (!isset($settings[$field])) {
                $settings[$field] = null;
            }
        }

        return $settings;
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('save')
                ->action('save')
        ];
    }
}
