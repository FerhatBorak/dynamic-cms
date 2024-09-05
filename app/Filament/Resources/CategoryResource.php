<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use App\Models\CategoryField;
use App\Models\FieldType;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

use App\Models\Permission;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Navigation\NavigationItem;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $languages = Language::where('is_active', true)->get();
        $defaultLanguage = $languages->first();

        return $form
            ->schema([
                Forms\Components\Hidden::make('name'),
                Forms\Components\Hidden::make('slug'),
                Forms\Components\Checkbox::make('include_meta')
                ->label('Meta\'ları dahil et'),

                Forms\Components\Select::make('clone_from_category')
                ->label('Alanları Kopyalanacak Kategori')
                ->options(Category::pluck('name', 'id'))
                ->searchable()
                ->placeholder('Kategori seçin (isteğe bağlı)')
                ->helperText('Seçilen kategorinin alanları bu kategoriye kopyalanacaktır.'),

                Forms\Components\Tabs::make('Category')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Information')
                            ->schema([
                                Forms\Components\Tabs::make('Translations')
                                    ->tabs(
                                        $languages->map(function ($language) use ($defaultLanguage) {
                                            return Forms\Components\Tabs\Tab::make($language->name)
                                                ->schema([
                                                    Forms\Components\TextInput::make("translations.{$language->code}.name")
                                                        ->label('Name')
                                                        ->required()
                                                        ->reactive()
                                                        ->afterStateUpdated(function (string $state, Forms\Set $set, Forms\Get $get) use ($language, $defaultLanguage) {
                                                            $slug = Str::slug($state);
                                                            $set("translations.{$language->code}.slug", $slug);
                                                            if ($language->code === $defaultLanguage->code) {
                                                                $set('name', $state);
                                                                $set('slug', $slug);
                                                            }
                                                        })
                                                        ->live(onBlur: true),
                                                    Forms\Components\TextInput::make("translations.{$language->code}.slug")
                                                        ->label('Slug')
                                                        ->required()
                                                        ->unique(Category::class, 'slug', fn ($record) => $record)
                                                        ->live(onBlur: true),
                                                    Forms\Components\Textarea::make("translations.{$language->code}.description")
                                                        ->label('Description'),
                                                ]);
                                        })->toArray()
                                    ),
                            ]),
                        Forms\Components\Tabs\Tab::make('Fields')
                            ->schema([
                                Forms\Components\Repeater::make('fields')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('field_type_id')
                                            ->label('Field Type')
                                            ->options(FieldType::pluck('name', 'id'))
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(fn(callable $set) => $set('type_specific_config', [])),
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->reactive()
                                            ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                                $set('slug', Str::slug($state));
                                            })
                                            ->live(onBlur: true),
                                            Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique(
                                                CategoryField::class,
                                                'slug',
                                                ignoreRecord: true,
                                                modifyRuleUsing: function (Unique $rule, Get $get) {
                                                    return $rule->where('category_id', $get('../../id'));
                                                }
                                            ),
                                        Forms\Components\TextInput::make('label')
                                            ->required(),
                                        Forms\Components\Textarea::make('help_text'),
                                        Forms\Components\Toggle::make('is_required'),
                                        Forms\Components\Toggle::make('is_unique'),
                                        Forms\Components\KeyValue::make('validation_rules'),
                                        Forms\Components\Select::make('column_span')
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
                                        Forms\Components\Group::make()
                                            ->schema(fn (Get $get): array => self::getTypeSpecificFields($get('field_type_id')))
                                            ->columns(2),
                                    ])
                                    ->columns(2)
                                    ->collapsible()
                                    ->collapsed()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
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

    public static function getNavigationGroup(): ?string
    {
        return auth()->user()->hasRole('super_admin') ? 'Yönetim' : null;
    }



    public static function getNavigationItems(): array
    {
        $items = parent::getNavigationItems();

        $categories = Category::all();

        foreach ($categories as $category) {
            $items[] = NavigationItem::make($category->name)
                ->icon('heroicon-o-document-text')
                ->group('İçerikler')
                ->url(ContentResource::getUrl('index', ['category' => $category->id]));
        }

        return $items;
    }
    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['fields'])) {
            foreach ($data['fields'] as &$field) {
                if (isset($field['type_specific_config']['options'])) {
                    $field['type_specific_config']['options'] = array_combine(
                        array_column($field['type_specific_config']['options'], 'key'),
                        array_column($field['type_specific_config']['options'], 'value')
                    );
                }
            }
        }
        return $data;
    }
    protected static function getTypeSpecificFields($fieldTypeId): array
    {
        if (empty($fieldTypeId)) {
            return [];
        }

        $fieldType = FieldType::find($fieldTypeId);
        if (!$fieldType) {
            return [];
        }

        $options = [];

        switch ($fieldType->slug) {
            case 'text':
            case 'textarea':
            case 'rich_text':
                $options[] = Forms\Components\TextInput::make('type_specific_config.min_length')->numeric()->label('Minimum Length');
                $options[] = Forms\Components\TextInput::make('type_specific_config.max_length')->numeric()->label('Maximum Length');
                if ($fieldType->slug === 'textarea') {
                    $options[] = Forms\Components\TextInput::make('type_specific_config.rows')->numeric()->label('Rows');
                }
                break;
            case 'number':
                $options[] = Forms\Components\TextInput::make('type_specific_config.min')->numeric()->label('Minimum');
                $options[] = Forms\Components\TextInput::make('type_specific_config.max')->numeric()->label('Maximum');
                $options[] = Forms\Components\TextInput::make('type_specific_config.step')->numeric()->label('Step');
                break;
            case 'date':
                $options[] = Forms\Components\DatePicker::make('type_specific_config.min_date')->label('Minimum Date');
                $options[] = Forms\Components\DatePicker::make('type_specific_config.max_date')->label('Maximum Date');
                break;
            case 'select':
            case 'checkbox':
                $options[] = Forms\Components\KeyValue::make('type_specific_config.options')
                    ->label('Options')
                    ->keyLabel('Value')
                    ->valueLabel('Label')
                    ->addButtonLabel('Add Option');
                break;
            case 'file':
                $options[] = Forms\Components\TagsInput::make('type_specific_config.allowed_file_types')->label('Allowed File Types');
                $options[] = Forms\Components\TextInput::make('type_specific_config.max_file_size')->numeric()->label('Max File Size (KB)');
                break;
        }

        return $options;
    }
public static function shouldRegisterNavigation(): bool
{
    return auth()->user()->hasRole('super_admin');
}
protected function afterSave()
{
    $category = $this->record;

    if (isset($this->data['fields'])) {
        foreach ($this->data['fields'] as $fieldData) {
            if (isset($fieldData['type_specific_config']['options'])) {
                $fieldData['type_specific_config']['options'] = array_combine(
                    array_column($fieldData['type_specific_config']['options'], 'key'),
                    array_column($fieldData['type_specific_config']['options'], 'value')
                );
            }

            $category->fields()->updateOrCreate(
                ['id' => $fieldData['id'] ?? null],
                $fieldData
            );
        }
    }

    // Yeni özellik: Meta alanlarını ekle
    if (!empty($this->data['include_meta'])) {
        $this->addMetaFields($category);
    }

    // Yeni özellik: Seçilen kategoriden alanları kopyala
    if (!empty($this->data['clone_from_category'])) {
        $this->handleCloneFields($category);
    }
}

protected function addMetaFields($category)
{
    $fieldTypeId = FieldType::where('slug', 'text')->value('id');

    $metaFields = [
        [
            'field_type_id' => $fieldTypeId,
            'name' => 'keyword',
            'slug' => 'keyword',
            'label' => 'Meta keyword',
        ],
        [
            'field_type_id' => $fieldTypeId,
            'name' => 'description',
            'slug' => 'description',
            'label' => 'Meta description',
        ],
    ];

    foreach ($metaFields as $field) {
        $category->fields()->updateOrCreate(
            ['slug' => $field['slug']],
            $field
        );
    }
}

protected function cloneFieldsFromCategory($category, $sourceCategoryId)
{
    $sourceCategory = Category::findOrFail($sourceCategoryId);
    foreach ($sourceCategory->fields as $field) {
        $category->fields()->updateOrCreate(
            ['slug' => $field['slug']],
            $field->toArray()
        );
    }
}
public static function afterCreate($record): void
{
    parent::afterCreate($record);

    Permission::create([
        'name' => 'edit_' . Str::slug($record->name),
        'label' => 'Edit ' . $record->name,
    ]);
}

public static function afterUpdate($record): void
{
    parent::afterUpdate($record);

    Permission::updateOrCreate(
        ['name' => 'edit_' . Str::slug($record->name)],
        ['label' => 'Edit ' . $record->name]
    );
}

public static function afterDelete($record): void
{
    parent::afterDelete($record);

    Permission::where('name', 'edit_' . Str::slug($record->name))->delete();
}
}
