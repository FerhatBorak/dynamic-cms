<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Models\Content;
use App\Models\Category;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ContentResource extends Resource
{
    protected static ?string $model = Content::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('id'),
                Forms\Components\Hidden::make('category_id'),
                Forms\Components\Tabs::make('Translations')
                    ->tabs(function (Get $get) {
                        $categoryId = $get('category_id');
                        $category = Category::find($categoryId);
                        $languages = Language::where('is_active', true)->get();
                        $defaultLanguage = $languages->first();

                        return $languages->map(function ($language) use ($category, $defaultLanguage, $get) {
                            return Forms\Components\Tabs\Tab::make($language->name)
                                ->schema([
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make("translations.{$language->code}.title")
                                                ->label('Title')
                                                ->required($language->code === $defaultLanguage->code)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (string $state, Set $set) use ($language) {
                                                    $set("translations.{$language->code}.slug", Str::slug($state));
                                                })
                                                ->rules(['required', 'string', 'max:255']),
                                            Forms\Components\TextInput::make("translations.{$language->code}.slug")
                                                ->label('Slug')
                                                ->required($language->code === $defaultLanguage->code)
                                                ->rules(['required', 'string', 'max:255']),
                                        ]),
                                    Forms\Components\Section::make('Content')
                                        ->schema(self::getDynamicFields($category, $language->code))
                                        ->columns(12),
                                ]);
                        })->toArray();
                    })
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('translations.title')
                    ->label('Title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('translations.slug')
                    ->label('Slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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

    protected static function getDynamicFields($category, $languageCode): array
    {
        if (!$category) {
            return [];
        }

        $fields = [];
        foreach ($category->fields as $field) {
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
            'select' => Forms\Components\Select::make("translations.{$languageCode}.fields.{$field->slug}")
            ->options($field->type_specific_config['options'] ?? [])
            ->label($field->label),
            'checkbox' => Forms\Components\Checkbox::make("translations.{$languageCode}.fields.{$field->slug}"),
            'file' => Forms\Components\FileUpload::make("translations.{$languageCode}.fields.{$field->slug}")
            ->disk('public')
            ->directory('uploads/' . $field->category->slug)
            ->visibility('public'),
            default => null,
        };

        if (!$baseField) {
            return null;
        }

        return $baseField
        ->label($field->label)
        ->helperText($field->help_text ?? '')
        ->required($field->is_required)
        ->columnSpan($field->column_span ?? 12);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
