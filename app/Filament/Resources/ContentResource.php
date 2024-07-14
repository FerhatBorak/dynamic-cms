<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Models\Content;
use App\Models\Category;
use App\Models\Language;
use App\Models\ContentTranslation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Filament\Facades\Filament;
use Closure;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        $categoryId = request()->query('category') ?? session('content_category_id') ?? $form->getRecord()?->category_id;

        if (!$categoryId) {
            return $form->schema([
                Forms\Components\TextInput::make('error')
                    ->label('Error')
                    ->default('Invalid or missing category ID.')
                    ->disabled(),
            ]);
        }

        session(['content_category_id' => $categoryId]);

        $category = Category::findOrFail($categoryId);
        $languages = Language::where('is_active', true)->get();
        $defaultLanguage = $languages->first();

        return $form
            ->schema([
                Forms\Components\Hidden::make('id'),
                Forms\Components\Hidden::make('category_id')
                    ->default($categoryId)
                    ->required(),
                Forms\Components\Tabs::make('Translations')
                    ->tabs(
                        $languages->map(function ($language) use ($category, $defaultLanguage) {
                            return Forms\Components\Tabs\Tab::make($language->name)
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make("translations.{$language->code}.title")
                                                ->label('Title')
                                                ->required()
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (string $state, Forms\Set $set) use ($language) {
                                                    $set("translations.{$language->code}.slug", Str::slug($state));
                                                })
                                                ->rules(['required', 'string', 'max:255']),
                                            Forms\Components\TextInput::make("translations.{$language->code}.slug")
                                                ->label('Slug')
                                                ->required()
                                                ->rules(fn () => static::getSlugValidationRule($language)),
                                        ]),
                                    Forms\Components\Section::make('İçerik')
                                        ->schema(self::getDynamicFields($category->id, $language->code))
                                        ->columns(12),
                                    Forms\Components\Actions::make([
                                        Forms\Components\Actions\Action::make('copyFromDefaultLanguage')
                                            ->label('Copy from Default Language')
                                            ->action(function (Forms\Set $set, Forms\Get $get) use ($language, $defaultLanguage, $category) {
                                                $defaultContent = $get("translations.{$defaultLanguage->code}");
                                                if ($defaultContent) {
                                                    $set("translations.{$language->code}.title", $defaultContent['title'] ?? '');
                                                    $set("translations.{$language->code}.slug", $defaultContent['slug'] ?? '');
                                                    foreach ($category->fields as $field) {
                                                        $set("translations.{$language->code}.fields.{$field->slug}", $defaultContent['fields'][$field->slug] ?? '');
                                                    }
                                                }
                                            })
                                            ->visible($language->code !== $defaultLanguage->code),
                                    ]),
                                ]);
                        })->toArray()
                    )
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected static function getSlugValidationRule($language): array
    {
        return [
            'required',
            'string',
            'max:255',
            function (string $attribute, $value, Closure $fail) use ($language) {
                return function () use ($attribute, $value, $fail, $language) {
                    $rule = Unique::for(ContentTranslation::class)
                        ->where('locale', $language->code);

                    $record = Filament::getCurrentResource()::getRecord();
                    if ($record) {
                        $rule->ignore($record->getKey(), 'content_id');
                    }

                    if (!$rule->passes($attribute, $value)) {
                        $fail("The slug has already been taken for this language.");
                    }
                };
            },
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('translations.title')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContents::route('/'),
            'create' => Pages\CreateContent::route('/create'),
            'edit' => Pages\EditContent::route('/{record}/edit'),
        ];
    }

    protected static function getDynamicFields($categoryId, $languageCode): array
    {
        if (!$categoryId) {
            return [];
        }

        $category = Category::find($categoryId);
        $categoryFields = $category ? $category->fields : collect();

        $fields = [];
        foreach ($categoryFields as $field) {
            $fieldComponent = self::getFieldComponent($field, $languageCode);
            if ($fieldComponent) {
                $fields[] = $fieldComponent;
            }
        }

        return $fields;
    }

    protected static function getFieldComponent($field, $languageCode)
    {
        $baseField = match ($field->fieldType->slug) {
            'text' => Forms\Components\TextInput::make("translations.{$languageCode}.fields.{$field->slug}"),
            'textarea' => Forms\Components\Textarea::make("translations.{$languageCode}.fields.{$field->slug}"),
            'rich_text' => Forms\Components\RichEditor::make("translations.{$languageCode}.fields.{$field->slug}"),
            'number' => Forms\Components\TextInput::make("translations.{$languageCode}.fields.{$field->slug}")->numeric(),
            'date' => Forms\Components\DatePicker::make("translations.{$languageCode}.fields.{$field->slug}"),
            'select' => Forms\Components\Select::make("translations.{$languageCode}.fields.{$field->slug}")->options($field->type_specific_config['options'] ?? []),
            'checkbox' => Forms\Components\Checkbox::make("translations.{$languageCode}.fields.{$field->slug}"),
            'file' => Forms\Components\FileUpload::make("translations.{$languageCode}.fields.{$field->slug}"),
            default => null,
        };

        if (!$baseField) {
            return null;
        }

        $baseField = $baseField
            ->label($field->label)
            ->helperText($field->help_text ?? '')
            ->required($field->is_required)
            ->default(fn ($record) => $record ? $record->getTranslation($languageCode)->fields[$field->slug] ?? '' : '')
            ->columnSpan($field->column_span ?? 12);

        if (method_exists($baseField, 'placeholder')) {
            $baseField = $baseField->placeholder($field->placeholder ?? '');
        }

        $rules = $field->validation_rules ?? [];
        if ($field->type_specific_config) {
            switch ($field->fieldType->slug) {
                case 'text':
                case 'textarea':
                case 'rich_text':
                    if (isset($field->type_specific_config['min_length'])) {
                        $rules[] = "min:{$field->type_specific_config['min_length']}";
                    }
                    if (isset($field->type_specific_config['max_length'])) {
                        $rules[] = "max:{$field->type_specific_config['max_length']}";
                    }
                    break;
                case 'number':
                    if (isset($field->type_specific_config['min'])) {
                        $rules[] = "min:{$field->type_specific_config['min']}";
                        $baseField = $baseField->minValue($field->type_specific_config['min']);
                    }
                    if (isset($field->type_specific_config['max'])) {
                        $rules[] = "max:{$field->type_specific_config['max']}";
                        $baseField = $baseField->maxValue($field->type_specific_config['max']);
                    }
                    if (isset($field->type_specific_config['step'])) {
                        $baseField = $baseField->step($field->type_specific_config['step']);
                    }
                    break;
                case 'date':
                    if (isset($field->type_specific_config['min_date'])) {
                        $baseField = $baseField->minDate($field->type_specific_config['min_date']);
                    }
                    if (isset($field->type_specific_config['max_date'])) {
                        $baseField = $baseField->maxDate($field->type_specific_config['max_date']);
                    }
                    break;
                case 'file':
                    if (isset($field->type_specific_config['allowed_file_types'])) {
                        $baseField = $baseField->acceptedFileTypes($field->type_specific_config['allowed_file_types']);
                    }
                    if (isset($field->type_specific_config['max_file_size'])) {
                        $baseField = $baseField->maxSize($field->type_specific_config['max_file_size']);
                    }
                    break;
            }
        }

        $baseField = $baseField->rules($rules);

        return $baseField;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
