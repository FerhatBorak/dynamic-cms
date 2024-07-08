<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class FieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'fields';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\Textarea::make('help_text')
                    ->maxLength(65535),
                Forms\Components\Toggle::make('is_required')
                    ->required(),
                Forms\Components\Toggle::make('is_unique')
                    ->required(),
                Forms\Components\TextInput::make('min')
                    ->numeric()
                    ->visible(fn (callable $get) => in_array($get('field_type_id'), [4, 5])),
                Forms\Components\TextInput::make('max')
                    ->numeric()
                    ->visible(fn (callable $get) => in_array($get('field_type_id'), [4, 5])),
                Forms\Components\TextInput::make('step')
                    ->numeric()
                    ->visible(fn (callable $get) => $get('field_type_id') == 4),
                Forms\Components\TagsInput::make('allowed_file_types')
                    ->visible(fn (callable $get) => $get('field_type_id') == 8),
                Forms\Components\TextInput::make('max_file_size')
                    ->numeric()
                    ->visible(fn (callable $get) => $get('field_type_id') == 8),
                Forms\Components\KeyValue::make('options')
                    ->visible(fn (callable $get) => in_array($get('field_type_id'), [6, 7])),
                Forms\Components\TextInput::make('default_value')
                    ->maxLength(255),
                Forms\Components\KeyValue::make('validation_rules'),
                Forms\Components\TextInput::make('order')
                    ->integer()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
