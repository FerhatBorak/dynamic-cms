<?php
public static function form(Form $form): Form
{
    $languages = Language::where('is_active', true)->get();
    $defaultLocale = config('app.fallback_locale', 'en');

    return $form
        ->schema([
            Forms\Components\Tabs::make('Category')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Basic Information')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->unique(Category::class, 'slug', fn ($record) => $record),
                            Forms\Components\Tabs::make('Translations')
                                ->tabs(
                                    $languages->map(function ($language) use ($defaultLocale) {
                                        return Forms\Components\Tabs\Tab::make($language->name)
                                            ->schema([
                                                Forms\Components\TextInput::make("translations.{$language->code}.name")
                                                    ->label('Name')
                                                    ->required()
                                                    ->reactive()
                                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) use ($language, $defaultLocale) {
                                                        if ($language->code === $defaultLocale) {
                                                            $set('name', $state);
                                                        }
                                                    }),
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
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')
                                            ->required(),
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
                                ->columns(2)
                                ->collapsible()
                                ->collapsed()
                                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
}
