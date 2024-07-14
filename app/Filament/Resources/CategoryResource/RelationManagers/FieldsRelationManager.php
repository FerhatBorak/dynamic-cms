<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource;

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
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        $set('advanced_options_visible', !empty($state));
                        $fieldType = \App\Models\FieldType::find($state);
                        if ($fieldType) {
                            $set('type_specific_config', $get('type_specific_config') ?? []);
                        }
                    })
                    ->live(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('help_text')
                    ->maxLength(65535),
                Forms\Components\Toggle::make('is_required')
                    ->required(),
                Forms\Components\Toggle::make('is_unique')
                    ->required(),
                Forms\Components\KeyValue::make('validation_rules'),
                Forms\Components\TextInput::make('order')
                    ->integer(),
                Forms\Components\Section::make('Advanced Options')
                    ->schema(fn (Forms\Get $get): array => CategoryResource::getAdvancedFieldOptions($get('field_type_id')))
                    ->columns(2)
                    ->collapsed(false)
                    ->visible(fn (Forms\Get $get): bool => $get('advanced_options_visible') ?? false),
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
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        $data['type_specific_config'] = $data['type_specific_config'] ?? [];
                        return $model::create($data);
                    }),
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
