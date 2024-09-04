<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use App\Models\SiteField;
use Filament\Forms;
use Filament\Resources\Resource;

class SiteSettingResource extends Resource

{    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Yönetim';

    protected static ?int $navigationSort = 9999; // En alta yerleştirmek için yüksek bir değer

    public static function getNavigationLabel(): string
    {
        return 'Site Ayarları';
    }
    public static function getFormSchema(): array
    {
        $fields = SiteField::all();

        return [
            Forms\Components\Grid::make()
                ->schema(
                    $fields->map(function ($field) {
                        $componentClass = match ($field->type) {
                            'text' => Forms\Components\TextInput::class,
                            'textarea' => Forms\Components\Textarea::class,
                            'rich_text' => Forms\Components\RichEditor::class,
                            'number' => Forms\Components\TextInput::class,
                            'date' => Forms\Components\DatePicker::class,
                            'file' => Forms\Components\FileUpload::class,
                            default => Forms\Components\TextInput::class,
                        };

                        return $componentClass::make($field->key)
                            ->label($field->label)
                            ->columnSpan($field->column);
                    })->toArray()
                )
                ->columns(12) // Toplam 12 sütunluk bir grid kullanıyoruz
        ];
    }
    public static function canViewAny(): bool
{
    return auth()->user()->hasPermission('manage_site_settings');
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSiteSettings::route('/'),
        ];
    }
    public static function afterSave($record, $data): void
    {
        Cache::forget("site_setting.{$record->key}");
    }

    public static function afterDelete($record): void
    {
        Cache::forget("site_setting.{$record->key}");
    }


}
