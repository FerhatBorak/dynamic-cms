<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Category;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Services\SiteSettingsService;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('categories', [])),
                Forms\Components\TextInput::make('facebook'),
                Forms\Components\TextInput::make('instagram'),
                Forms\Components\TextInput::make('twitter'),
                Forms\Components\TextInput::make('meta_title'),
                Forms\Components\Textarea::make('meta_description'),
                Forms\Components\TextInput::make('phone1'),
                Forms\Components\TextInput::make('phone2'),
                Forms\Components\CheckboxList::make('categories')
                    ->options(fn () => Category::pluck('name', 'id')->toArray())
                    ->columns(2)
                    ->hidden(fn (callable $get) => !in_array('editor', $get('roles') ?? []))
                    ->dehydrated(fn (callable $get) => in_array('editor', $get('roles') ?? [])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('roles.name'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('System');
    }

    public static function getNavigationLabel(): string
    {
        return __('Users');
    }

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Users');
    }

    public static function afterSave(Model $record, array $data): void
    {
        if ($record->hasRole('editor')) {
            $record->categories()->sync($data['categories'] ?? []);
            app(SiteSettingsService::class)->refreshCache();
        }
    }
}
