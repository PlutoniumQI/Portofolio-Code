<?php

namespace App\Filament\Resources\Experiences\Schemas;

use App\Models\Experience;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Experience details')
                    ->description('Timeline entries for work, organisations, competitions, and PLK.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('type')
                                    ->options(Experience::types())
                                    ->default(Experience::TYPE_WORK)
                                    ->required()
                                    ->native(false),
                                TextInput::make('date_range')
                                    ->label('Date range')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. 2024 — Now'),
                                TextInput::make('company')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. SMKN 2 Banjarmasin'),
                                TextInput::make('location')
                                    ->maxLength(255)
                                    ->placeholder('e.g. Banjarmasin, Indonesia'),
                                TextInput::make('url')
                                    ->label('Related URL')
                                    ->url()
                                    ->maxLength(2048),
                                TextInput::make('sort_order')
                                    ->label('Sort order')
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText('Lower numbers appear first. Drag rows in the list to reorder.'),
                            ]),
                        Tabs::make('Translations')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('Indonesia')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Title (ID)')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g. Front-end Developer'),
                                        Textarea::make('description')
                                            ->label('Description (ID)')
                                            ->rows(4)
                                            ->maxLength(1000),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('title_en')
                                            ->label('Title (EN)')
                                            ->maxLength(255),
                                        Textarea::make('description_en')
                                            ->label('Description (EN)')
                                            ->rows(4)
                                            ->maxLength(1000),
                                    ]),
                            ]),
                        TagsInput::make('tags')
                            ->separator(',')
                            ->placeholder('Add a tag and press Enter')
                            ->columnSpanFull(),
                    ]),

                Section::make('Visibility')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Show on the public site')
                            ->default(true),
                    ]),
            ]);
    }
}
