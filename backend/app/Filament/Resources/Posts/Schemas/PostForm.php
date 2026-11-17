<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use Filament\Forms\Components\DateTimePicker;
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

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Used in /blog/{slug}. Lowercase letters, numbers, and hyphens.'),
                                TextInput::make('category')
                                    ->maxLength(255)
                                    ->placeholder('e.g. Design notes'),
                                Select::make('status')
                                    ->options(Post::statuses())
                                    ->default(Post::STATUS_DRAFT)
                                    ->required()
                                    ->native(false),
                                DateTimePicker::make('published_at')
                                    ->label('Publish date')
                                    ->helperText('Required before a published post appears on the site.'),
                                TagsInput::make('tags')
                                    ->separator(',')
                                    ->columnSpanFull(),
                                Toggle::make('is_featured')
                                    ->label('Feature on the home page')
                                    ->default(false),
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
                                        Textarea::make('excerpt_id')
                                            ->label('Excerpt (ID)')
                                            ->rows(3)
                                            ->maxLength(500),
                                        RichEditor::make('body_id')
                                            ->label('Body (ID)')
                                            ->required()
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('title_en')
                                            ->label('Title (EN)')
                                            ->maxLength(255),
                                        Textarea::make('excerpt_en')
                                            ->label('Excerpt (EN)')
                                            ->rows(3)
                                            ->maxLength(500),
                                        RichEditor::make('body_en')
                                            ->label('Body (EN)')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),

                Section::make('Cover image')
                    ->schema([
                        TextInput::make('cover_url')
                            ->label('Cover image URL')
                            ->url()
                            ->maxLength(2048)
                            ->placeholder('https://raw.githubusercontent.com/...'),
                    ]),
            ]);
    }
}
