<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentResource\Pages;
use App\Models\Content;
use App\Models\Category;
use App\Models\CategoryField;
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
                Forms\Components\Hidden::make('category_id')
                    ->default(fn (Get $get) => $get('category_id') ?? request()->query('category')),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                        if (! $get('is_slug_changed_manually') && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set) => $set('is_slug_changed_manually', true)),
                Forms\Components\Hidden::make('is_slug_changed_manually')
                    ->default(false),
                Forms\Components\Section::make('Dynamic Fields')
                    ->schema(fn (Get $get): array => self::getDynamicFields($get('category_id')))
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(request()->has('category'), function ($query) {
                $query->where('category_id', request()->query('category'));
            });
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static function getDynamicFields($categoryId): array
    {
        if (!$categoryId) {
            return [];
        }

        $fields = [];
        $categoryFields = CategoryField::where('category_id', $categoryId)->get();

        foreach ($categoryFields as $field) {
            $fieldComponent = self::getFieldComponent($field);
            if ($fieldComponent) {
                $fields[] = $fieldComponent;
            }
        }

        return $fields;
    }

    protected static function getFieldComponent(CategoryField $field)
    {
        $baseField = match ($field->fieldType->slug) {
            'text' => Forms\Components\TextInput::make("fields.{$field->slug}"),
            'textarea' => Forms\Components\Textarea::make("fields.{$field->slug}"),
            'rich_text' => Forms\Components\RichEditor::make("fields.{$field->slug}"),
            'number' => Forms\Components\TextInput::make("fields.{$field->slug}")->numeric(),
            'date' => Forms\Components\DatePicker::make("fields.{$field->slug}"),
            'select' => Forms\Components\Select::make("fields.{$field->slug}")->options($field->options ?? []),
            'checkbox' => Forms\Components\Checkbox::make("fields.{$field->slug}"),
            'file' => Forms\Components\FileUpload::make("fields.{$field->slug}"),
            default => null,
        };

        if (!$baseField) {
            return null;
        }

        return $baseField
            ->label($field->label)
            ->placeholder($field->placeholder ?? '')
            ->helperText($field->help_text ?? '')
            ->required($field->is_required)
            ->rules($field->validation_rules ?? [])
            ->when(
                $field->fieldType->slug === 'number',
                fn ($component) => $component
                    ->min($field->min)
                    ->max($field->max)
                    ->step($field->step)
            );
    }
}
