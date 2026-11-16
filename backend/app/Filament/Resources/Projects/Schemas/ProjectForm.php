<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Support\PortfolioUpload;
use App\Models\Project;
use Filament\Forms\Components\RichEditor;
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

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project details')
                    ->description('Bilingual content shown on the project card and detail page.')
                    ->schema([
                        Tabs::make('Translations')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('Indonesia')
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Title (ID)')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g. Platform Ujian Online'),
                                        Textarea::make('description')
                                            ->label('Short description (ID)')
                                            ->rows(3)
                                            ->maxLength(500)
                                            ->helperText('Shown on the project card.'),
                                        Textarea::make('excerpt_id')
                                            ->label('Excerpt (ID)')
                                            ->rows(3)
                                            ->maxLength(500),
                                        RichEditor::make('content_id')
                                            ->label('Case study / process story (ID)')
                                            ->helperText('Explain the problem, your process, and the result.')
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('title_en')
                                            ->label('Title (EN)')
                                            ->maxLength(255)
                                            ->placeholder('Falls back to Indonesian when empty.'),
                                        Textarea::make('description_en')
                                            ->label('Short description (EN)')
                                            ->rows(3)
                                            ->maxLength(500),
                                        Textarea::make('excerpt_en')
                                            ->label('Excerpt (EN)')
                                            ->rows(3)
                                            ->maxLength(500),
                                        RichEditor::make('content_en')
                                            ->label('Case study / process story (EN)')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),

                Section::make('Links and classification')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Used in /projects/{slug}. Lowercase letters, numbers, and hyphens.'),
                                Select::make('category')
                                    ->options(Project::categories())
                                    ->searchable()
                                    ->native(false),
                                TextInput::make('url')
                                    ->label('Live URL')
                                    ->url()
                                    ->maxLength(2048)
                                    ->placeholder('https://example.com'),
                                TextInput::make('repo_url')
                                    ->label('Repository URL')
                                    ->url()
                                    ->maxLength(2048)
                                    ->placeholder('https://github.com/...'),
                                TagsInput::make('tags')
                                    ->label('Tech stack / tags')
                                    ->separator(',')
                                    ->placeholder('Add a tag and press Enter')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Media')
                    ->description('Upload an image or paste a public URL. An upload wins when both are filled.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                PortfolioUpload::image('image_path', 'projects', 'Cover image')
                                    ->helperText('Stored in the GitHub assets repository in production.'),
                                TextInput::make('image_url')
                                    ->label('External image URL')
                                    ->url()
                                    ->maxLength(2048)
                                    ->placeholder('https://raw.githubusercontent.com/...'),
                            ]),
                        PortfolioUpload::gallery('gallery', 'projects/gallery', 'Gallery images')
                            ->columnSpanFull(),
                        PortfolioUpload::diskField(),
                    ]),

                Section::make('Publishing')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->options(Project::statuses())
                                    ->default(Project::STATUS_DRAFT)
                                    ->required()
                                    ->native(false),
                                TextInput::make('sort_order')
                                    ->label('Sort order')
                                    ->integer()
                                    ->minValue(0)
                                    ->default(0)
                                    ->helperText('Lower numbers appear first. Drag rows in the list to reorder.'),
                                Toggle::make('is_featured')
                                    ->label('Feature on the home page')
                                    ->default(false),
                            ]),
                    ]),
            ]);
    }
}
