<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\CategoryField;
use App\Models\FieldType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ManageCategoryFieldsRelation extends ManageRelatedRecords
{
    protected static string $resource = CategoryResource::class;

    protected static string $relationship = 'fields';

    protected static ?string $navigationLabel = 'Manage Fields';
    protected static ?string $breadcrumb = 'Manage Fields';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('field_type_id')
                    ->label('Field Type')
                    ->options(FieldType::pluck('name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $state, callable $set) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_required')
                    ->required(),
                Forms\Components\KeyValue::make('options')
                    ->visible(function (callable $get) {
                        $fieldType = FieldType::find($get('field_type_id'));
                        return $fieldType && in_array($fieldType->slug, ['select', 'checkbox']);
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('fieldType.name'),
                Tables\Columns\IconColumn::make('is_required')
                    ->boolean(),
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
