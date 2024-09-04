<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteFieldResource\Pages;
use App\Filament\Resources\SiteFieldResource\RelationManagers;
use App\Models\SiteField;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Str;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class SiteFieldResource extends Resource
{
    protected static ?string $model = SiteField::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Yönetim';
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    public static function canViewAny(): bool
    {
        return auth()->user()->hasPermission('manage_site_fields');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) {
                            $set('key', null);
                            $set('label', null);
                            return;
                        }
                        $set('key', Str::slug($state));
                        $set('label', $state);
                    })
                    ->lazy(),
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('label')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'rich_text' => 'Rich Text',
                        'number' => 'Number',
                        'date' => 'Date',
                        'file' => 'File',
                    ])
                    ->required(),
                Forms\Components\Select::make('column')
                    ->options([
                        3 => '1/4 Width',
                        4 => '1/3 Width',
                        6 => '1/2 Width',
                        8 => '2/3 Width',
                        9 => '3/4 Width',
                        12 => 'Full Width',
                    ])
                    ->default(12)
                    ->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('type'),
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



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteFields::route('/'),
            'create' => Pages\CreateSiteField::route('/create'),
            'edit' => Pages\EditSiteField::route('/{record}/edit'),
        ];
    }
}
