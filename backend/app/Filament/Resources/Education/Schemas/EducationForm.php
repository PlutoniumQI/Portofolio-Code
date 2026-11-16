<?php

namespace App\Filament\Resources\Education\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class EducationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('School / campus')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('institution')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. SMKN 2 Banjarmasin'),
                                TextInput::make('start_date')
                                    ->required()
                                    ->maxLength(20)
                                    ->placeholder('e.g. 2024'),
                                TextInput::make('end_date')
                                    ->maxLength(20)
                                    ->placeholder('e.g. Now'),
                                TextInput::make('sort_order')
                                    ->label('Sort order')
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0),
                            ]),
                        Tabs::make('Translations')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('Indonesia')
                                    ->schema([
                                        TextInput::make('degree_id')
                                            ->label('Degree (ID)')
                                            ->maxLength(255)
                                            ->placeholder('e.g. Sekolah Menengah Kejuruan'),
                                        TextInput::make('field_id')
                                            ->label('Field (ID)')
                                            ->maxLength(255)
                                            ->placeholder('e.g. Pengembangan Perangkat Lunak dan Gim'),
                                        Textarea::make('description_id')
                                            ->label('Description (ID)')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('degree_en')
                                            ->label('Degree (EN)')
                                            ->maxLength(255),
                                        TextInput::make('field_en')
                                            ->label('Field (EN)')
                                            ->maxLength(255),
                                        Textarea::make('description_en')
                                            ->label('Description (EN)')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),
                            ]),
                        Toggle::make('is_active')
                            ->label('Show on the public site')
                            ->default(true),
                    ]),
            ]);
    }
}
