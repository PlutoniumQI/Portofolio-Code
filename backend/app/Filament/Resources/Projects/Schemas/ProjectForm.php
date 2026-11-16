<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Project details')
                    ->description('Content shown on the project card.')
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
                                    ->maxLength(2048)
                                    ->placeholder('https://example.com')
                                    ->helperText('Leave blank if the card should not link anywhere.'),
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

                Section::make('Image')
                    ->description('Upload a local file or paste a public image URL. An upload takes priority when both are filled.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Upload image')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/avif'])
                                    ->disk('public')
                                    ->directory('projects')
                                    ->visibility('public')
                                    ->maxSize(config('portfolio.max_upload_kb'))
                                    ->helperText('Stored locally and copied into the site on export. Compress large images first.'),
                                TextInput::make('image_url')
                                    ->label('Image URL')
                                    ->url()
                                    ->maxLength(2048)
                                    ->placeholder('https://raw.githubusercontent.com/...')
                                    ->helperText('Use a direct raw GitHub URL, not a github.com/.../blob/... page.'),
                            ]),
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
                                    ->helperText('Lower numbers appear first. You can also drag rows in the list.'),
                            ]),
                    ]),
            ]);
    }
}
