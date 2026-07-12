<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Experience Details')
                    ->description('Role and company information.')
                    ->icon('heroicon-o-briefcase')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->label('Role / Title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. UI/UX Designer'),
                                TextInput::make('company')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Freelance'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('date_range')
                                    ->label('Period')
                                    ->placeholder('e.g. 2025 — Now')
                                    ->required()
                                    ->maxLength(100),
                                TextInput::make('url')
                                    ->label('Company URL')
                                    ->url()
                                    ->maxLength(255)
                                    ->placeholder('https://company.com'),
                            ]),
                        TagsInput::make('tags')
                            ->separator(',')
                            ->placeholder('Add a skill tag and press Enter')
                            ->helperText('e.g. Figma, UI/UX, Prototyping'),
                    ]),

                Section::make('Settings')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->label('Display Order')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Lower numbers appear first.'),
                                Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Inactive experiences are hidden from the portfolio.'),
                            ]),
                        Textarea::make('description')
                            ->placeholder('Briefly describe your role and achievements...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
