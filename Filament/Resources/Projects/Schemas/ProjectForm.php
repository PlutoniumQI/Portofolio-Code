<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project Details')
                    ->description('Basic information about the project.')
                    ->icon('heroicon-o-rectangle-stack')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Portfolio Website'),
                                TextInput::make('url')
                                    ->label('Project URL')
                                    ->url()
                                    ->maxLength(255)
                                    ->placeholder('https://example.com'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'published' => 'Published',
                                        'draft' => 'Draft',
                                        'coming_soon' => 'Coming Soon',
                                    ])
                                    ->default('published')
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label('Display Order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Lower numbers appear first.'),
                            ]),
                        TagsInput::make('tags')
                            ->separator(',')
                            ->placeholder('Add a tag and press Enter')
                            ->helperText('Technologies, tools, or categories.'),
                    ]),

                Section::make('Content')
                    ->description('Description and media for the project.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        RichEditor::make('description')
                            ->placeholder('Describe what this project is about...')
                            ->columnSpanFull(),
                        FileUpload::make('image_upload')
                            ->label('Project Image')
                            ->image()
                            ->disk('public')
                            ->directory('projects')
                            ->imageEditor()
                            ->dehydrated(false)
                            ->formatStateUsing(function ($record) {
                                if ($record && $record->image_url && !\Illuminate\Support\Str::startsWith($record->image_url, ['http://', 'https://', '//'])) {
                                    return $record->image_url;
                                }
                                return null;
                            })
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('image_url', $state);
                                }
                            }),
                        TextInput::make('image_url')
                            ->label('Or Paste External Image URL')
                            ->placeholder('e.g. https://imgur.com/xyz.png')
                            ->helperText('Optional: You can paste a direct link to an image hosted on Imgur, Postimages, etc. (Direct link must end with .jpg, .png, etc.)'),
                    ]),
            ]);
    }
}
