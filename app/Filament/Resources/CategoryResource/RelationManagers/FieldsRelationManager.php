<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'fields';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Select::make('field_type_id')
                            ->relationship('fieldType', 'name')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('options', null))
                            ->label('Field Type'),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->label('Field Name'),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->label('Field Slug'),

                        Forms\Components\TextInput::make('label')
                            ->maxLength(255)
                            ->label('Field Label'),

                        Forms\Components\Toggle::make('is_required')
                            ->required()
                            ->label('Is this field required?'),

                        Forms\Components\Toggle::make('is_unique')
                            ->required()
                            ->label('Should this field be unique?'),

                        Forms\Components\Textarea::make('help_text')
                            ->maxLength(65535)
                            ->label('Help Text'),
                    ])->columnSpan(1),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Placeholder::make('type_specific_settings')
                            ->visible(fn (callable $get) => !$get('field_type_id')),

                        // Text Input specific fields
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('placeholder')
                                    ->maxLength(255)
                                    ->label('Placeholder Text'),
                                Forms\Components\TextInput::make('min_length')
                                    ->numeric()
                                    ->label('Minimum Length'),
                                Forms\Components\TextInput::make('max_length')
                                    ->numeric()
                                    ->label('Maximum Length'),
                            ])
                            ->visible(fn (callable $get) => $get('field_type_id') == 1)
                            ->columnSpan(2),

                        // Textarea specific fields
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('rows')
                                    ->numeric()
                                    ->label('Number of Rows'),
                            ])
                            ->visible(fn (callable $get) => $get('field_type_id') == 2)
                            ->columnSpan(2),

                        // Number Input specific fields
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('min')
                                    ->numeric()
                                    ->label('Minimum Value'),
                                Forms\Components\TextInput::make('max')
                                    ->numeric()
                                    ->label('Maximum Value'),
                                Forms\Components\TextInput::make('step')
                                    ->numeric()
                                    ->label('Step'),
                            ])
                            ->visible(fn (callable $get) => $get('field_type_id') == 3)
                            ->columnSpan(2),

                        // Date Input specific fields
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\DatePicker::make('min_date')
                                    ->label('Minimum Date'),
                                Forms\Components\DatePicker::make('max_date')
                                    ->label('Maximum Date'),
                            ])
                            ->visible(fn (callable $get) => $get('field_type_id') == 4)
                            ->columnSpan(2),

                        // Select and Checkbox specific fields
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\KeyValue::make('options')
                                    ->label('Options'),
                            ])
                            ->visible(fn (callable $get) => in_array($get('field_type_id'), [5, 6]))
                            ->columnSpan(2),

                        // File Upload specific fields
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TagsInput::make('allowed_file_types')
                                    ->placeholder('jpg,png,pdf'),
                                Forms\Components\TextInput::make('max_file_size')
                                    ->numeric()
                                    ->suffix('MB')
                                    ->label('Maximum File Size (MB)'),
                            ])
                            ->visible(fn (callable $get) => $get('field_type_id') == 7)
                            ->columnSpan(2),
                    ])->columnSpan(1),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('default_value')
                            ->maxLength(255)
                            ->label('Default Value'),

                        Forms\Components\KeyValue::make('validation_rules')
                            ->label('Additional Validation Rules'),

                        Forms\Components\TextInput::make('order')
                            ->integer()
                            ->default(0)
                            ->label('Display Order'),
                    ])->columnSpan(1),
            ])
            ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fieldType.name')
                    ->label('Field Type'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Field Name'),
                Tables\Columns\TextColumn::make('label')
                    ->label('Display Label'),
                Tables\Columns\IconColumn::make('is_required')
                    ->boolean()
                    ->label('Required'),
                Tables\Columns\TextColumn::make('order')
                    ->label('Display Order'),
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
