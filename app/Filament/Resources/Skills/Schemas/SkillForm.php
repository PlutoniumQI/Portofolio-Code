<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Skill Details')
                    ->description('Name and category for this skill.')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. UI/UX Design'),
                                TextInput::make('subtitle')
                                    ->maxLength(255)
                                    ->placeholder('e.g. Design Systems & Smart Interfaces'),
                            ]),
                    ]),

                Section::make('Icon')
                    ->description('Choose how the skill icon is displayed.')
                    ->icon('heroicon-o-paint-brush')
                    ->schema([
                        Select::make('icon_type')
                            ->label('Icon Type')
                            ->options([
                                'svg' => 'SVG Code',
                                'text' => 'Text',
                                'image' => 'Image URL',
                            ])
                            ->live()
                            ->placeholder('Select an icon type...'),
                        Textarea::make('icon_data')
                            ->label(fn ($get) => match ($get('icon_type')) {
                                'svg' => 'SVG Code',
                                'text' => 'Display Text',
                                'image' => 'Image URL',
                                default => 'Icon Data',
                            })
                            ->placeholder(fn ($get) => match ($get('icon_type')) {
                                'svg' => 'Paste your SVG markup here...',
                                'text' => 'Enter display text...',
                                'image' => 'https://example.com/icon.png',
                                default => 'Select an icon type first',
                            })
                            ->rows(4)
                            ->columnSpanFull(),
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
                                    ->helperText('Inactive skills are hidden from the portfolio.'),
                            ]),
                    ]),
            ]);
    }
}
