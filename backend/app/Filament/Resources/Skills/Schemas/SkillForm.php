<?php

namespace App\Filament\Resources\Skills\Schemas;

use App\Models\Skill;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Skill')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. UI/UX Design'),
                                Select::make('category')
                                    ->options(Skill::categories())
                                    ->default(Skill::CATEGORY_HARD)
                                    ->required()
                                    ->native(false),
                                TextInput::make('percentage')
                                    ->required()
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%')
                                    ->default(80),
                                TextInput::make('icon')
                                    ->maxLength(255)
                                    ->placeholder('e.g. palette, code, figma')
                                    ->helperText('Icon key used by the frontend. Leave blank for a default icon.'),
                                TextInput::make('sort_order')
                                    ->label('Sort order')
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0),
                                Toggle::make('is_active')
                                    ->label('Show on the public site')
                                    ->default(true),
                            ]),
                        Tabs::make('Translations')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('Indonesia')
                                    ->schema([
                                        Textarea::make('description_id')
                                            ->label('Description (ID)')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        Textarea::make('description_en')
                                            ->label('Description (EN)')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
