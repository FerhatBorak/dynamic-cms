<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSectionResource\Pages;
use App\Filament\Resources\HomepageSectionResource\RelationManagers;
use App\Models\HomepageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Str;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $set('slug', Str::slug($state));
                    })
                    ->lazy(),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(HomepageSection::class, 'slug', fn ($record) => $record),
                Forms\Components\Repeater::make('fields')
                    ->relationship('fields')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Field Label'),
                        Forms\Components\TextInput::make('slug')
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'text' => 'Text',
                                'textarea' => 'Textarea',
                                'rich_text' => 'Rich Text',
                                'image' => 'Image',
                                'link' => 'Link',
                            ])
                            ->required(),
                            Forms\Components\Select::make('column_span')
                            ->label('Genişlik')
                            ->options([
                                'full' => 'Tam Genişlik',
                                '1/2' => 'Yarım Genişlik',
                                '1/3' => 'Üçte Bir Genişlik',
                                '2/3' => 'Üçte İki Genişlik',
                            ])
                            ->default('full')
                            ->required(),
                    ])
                    ->columns(4),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
    return auth()->user()->hasRole('super_admin');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('fields_count')->counts('fields'),
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
            'index' => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit' => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }
}
