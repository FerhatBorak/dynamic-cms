<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryFieldResource\Pages;
use App\Filament\Resources\CategoryResource\Pages\ManageCategoryFields;
use App\Models\CategoryField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CategoryFieldResource extends Resource
{
    protected static ?string $model = CategoryField::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Select::make('field_type_id')
                    ->relationship('fieldType', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('options', null)),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('label')
                    ->maxLength(255),
                Forms\Components\TextInput::make('placeholder')
                    ->maxLength(255),
                Forms\Components\Textarea::make('help_text')
                    ->maxLength(65535),
                Forms\Components\Toggle::make('is_required')
                    ->required(),
                Forms\Components\Toggle::make('is_unique')
                    ->required(),
                Forms\Components\TextInput::make('min')
                    ->numeric()
                    ->visible(function (callable $get) {
                        $fieldType = $get('field_type_id');
                        return in_array($fieldType, [2, 4]); // Assuming 2 is for number and 4 is for date
                    }),
                Forms\Components\TextInput::make('max')
                    ->numeric()
                    ->visible(function (callable $get) {
                        $fieldType = $get('field_type_id');
                        return in_array($fieldType, [2, 4]); // Assuming 2 is for number and 4 is for date
                    }),
                Forms\Components\TextInput::make('step')
                    ->numeric()
                    ->visible(function (callable $get) {
                        return $get('field_type_id') == 2; // Assuming 2 is for number
                    }),
                Forms\Components\KeyValue::make('options')
                    ->visible(function (callable $get) {
                        $fieldType = $get('field_type_id');
                        return in_array($fieldType, [5, 6]); // Assuming 5 is for select and 6 is for checkbox
                    }),
                Forms\Components\TextInput::make('default_value')
                    ->maxLength(255),
                Forms\Components\KeyValue::make('validation_rules'),
                Forms\Components\TextInput::make('order')
                    ->integer()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('fieldType.name'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\IconColumn::make('is_required')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    public static function getPages(): array
    {
        return [
            'index' => ManageCategoryFields::route('/'),
        ];
    }
}
