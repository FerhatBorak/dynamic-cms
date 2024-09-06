<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageContentResource\Pages;
use App\Filament\Resources\HomepageContentResource\RelationManagers;
use App\Models\HomepageContent;
use App\Models\HomepageSection;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


    class HomepageContentResource extends Resource
    {
        protected static ?string $model = HomepageContent::class;

        protected static ?string $navigationIcon = 'heroicon-o-document-text';

        public static function form(Form $form): Form
        {
            return $form
                ->schema([
                    Forms\Components\Hidden::make('homepage_section_id'),
                    Forms\Components\Tabs::make('Translations')
                        ->tabs(function (Forms\Get $get) {
                            $sectionId = $get('homepage_section_id');
                            $section = HomepageSection::find($sectionId);
                            $languages = Language::all();

                            if (!$section) return [];

                            return $languages->map(function ($language) use ($section) {
                                return Forms\Components\Tabs\Tab::make($language->name)
                                    ->schema(
                                        $section->fields->map(function ($field) use ($language) {
                                            $componentClass = match ($field->type) {
                                                'text' => Forms\Components\TextInput::class,
                                                'textarea' => Forms\Components\Textarea::class,
                                                'rich_text' => Forms\Components\RichEditor::class,
                                                'image' => Forms\Components\FileUpload::class,
                                                'link' => Forms\Components\TextInput::class,
                                                default => Forms\Components\TextInput::class,
                                            };

                                            return $componentClass::make("content.{$language->code}.{$field->slug}")
                                                ->label($field->name)
                                                ->columnSpan($field->column_span);
                                        })->toArray()
                                    );
                            })->toArray();
                        })
                        ->columnSpanFull(),
                ]);
        }
        public static function getModelLabel(): string
        {
            return 'Anasayfa Bölümü';
        }

        public static function getPluralModelLabel(): string
        {
            return 'Anasayfa Bölümleri';
        }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([
                    Tables\Columns\TextColumn::make('section.name')
                        ->label('Bölüm Adı'),
                ])
                ->actions([
                    Tables\Actions\EditAction::make()
                        ->label(fn ($record) => "Düzenle"),
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
                'index' => Pages\ListHomepageContents::route('/'),
                'edit' => Pages\EditHomepageContent::route('/{record}/edit'),
            ];
        }

        public static function canCreate(): bool
        {
            return auth()->user()->hasRole('admin');
        }
    }
