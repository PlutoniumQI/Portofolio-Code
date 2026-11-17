<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Certificate')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('issuer')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Dicoding Indonesia'),
                                TextInput::make('credential_id')
                                    ->label('Credential ID')
                                    ->maxLength(255),
                                DatePicker::make('issued_at')
                                    ->label('Issued at'),
                                DatePicker::make('expires_at')
                                    ->label('Valid until'),
                                TextInput::make('credential_url')
                                    ->label('Credential URL')
                                    ->url()
                                    ->maxLength(2048),
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
                                        TextInput::make('title_id')
                                            ->label('Title (ID)')
                                            ->required()
                                            ->maxLength(255),
                                        Textarea::make('description_id')
                                            ->label('Description (ID)')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('title_en')
                                            ->label('Title (EN)')
                                            ->maxLength(255),
                                        Textarea::make('description_en')
                                            ->label('Description (EN)')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),
                            ]),
                    ]),

                Section::make('Preview image')
                    ->schema([
                        TextInput::make('image_url')
                            ->label('Certificate image URL')
                            ->url()
                            ->maxLength(2048)
                            ->placeholder('https://raw.githubusercontent.com/...'),
                        Toggle::make('is_active')
                            ->label('Show on the public site')
                            ->default(true),
                    ]),
            ]);
    }
}
