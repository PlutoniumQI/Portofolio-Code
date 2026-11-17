<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use App\Models\Testimonial;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Testimonial')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('company')
                                    ->maxLength(255),
                                Select::make('category')
                                    ->options(Testimonial::categories())
                                    ->default(Testimonial::CATEGORY_CLIENT)
                                    ->required()
                                    ->native(false),
                                TextInput::make('rating')
                                    ->integer()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->default(5)
                                    ->suffix('/ 5'),
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
                                        TextInput::make('role_id')
                                            ->label('Role (ID)')
                                            ->maxLength(255)
                                            ->placeholder('e.g. Dosen Pembimbing'),
                                        Textarea::make('quote_id')
                                            ->label('Quote (ID)')
                                            ->required()
                                            ->rows(4)
                                            ->maxLength(1000),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('role_en')
                                            ->label('Role (EN)')
                                            ->maxLength(255),
                                        Textarea::make('quote_en')
                                            ->label('Quote (EN)')
                                            ->rows(4)
                                            ->maxLength(1000),
                                    ]),
                            ]),
                    ]),

                Section::make('Avatar')
                    ->schema([
                        TextInput::make('avatar_url')
                            ->label('Avatar image URL')
                            ->url()
                            ->maxLength(2048)
                            ->placeholder('https://raw.githubusercontent.com/...'),
                    ]),
            ]);
    }
}
