<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Experience details')
                    ->description('Shown in the timeline on the public site.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Front-end Developer'),
                                TextInput::make('company')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. SMKN 2 Banjarmasin'),
                                TextInput::make('date_range')
                                    ->label('Date range')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. 2024 — 2025'),
                                TextInput::make('sort_order')
                                    ->label('Sort order')
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText('Lower numbers appear first. You can also drag rows in the list.'),
                                Textarea::make('description')
                                    ->rows(4)
                                    ->maxLength(500)
                                    ->columnSpanFull(),
                                TagsInput::make('tags')
                                    ->separator(',')
                                    ->placeholder('Add a tag and press Enter')
                                    ->columnSpanFull(),
                            ]),
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
