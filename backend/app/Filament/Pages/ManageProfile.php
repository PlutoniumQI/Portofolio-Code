<?php

namespace App\Filament\Pages;

use App\Models\Profile;
use BackedEnum;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageProfile extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserCircle;

    protected static ?string $navigationLabel = 'Profile & CV';

    protected static ?string $title = 'Profile & CV';

    protected static ?string $slug = 'site-profile';

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.manage-profile';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Profile::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identity')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('full_name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('location')
                                    ->maxLength(255)
                                    ->placeholder('e.g. Banjarmasin, Indonesia'),
                                TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(50),
                                TextInput::make('years_learning')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(80)
                                    ->helperText('Used by the home page stats.'),
                                TextInput::make('tools_mastered')
                                    ->integer()
                                    ->minValue(0)
                                    ->maxValue(100),
                                Toggle::make('is_available')
                                    ->label('Available for work / collaboration')
                                    ->default(true),
                            ]),
                        Tabs::make('Translations')
                            ->columnSpanFull()
                            ->tabs([
                                Tab::make('Indonesia')
                                    ->schema([
                                        TextInput::make('headline_id')
                                            ->label('Headline (ID)')
                                            ->maxLength(255),
                                        TextInput::make('availability_id')
                                            ->label('Availability label (ID)')
                                            ->maxLength(255)
                                            ->placeholder('e.g. Terbuka untuk kolaborasi'),
                                        Textarea::make('intro_id')
                                            ->label('Hero intro (ID)')
                                            ->rows(3)
                                            ->maxLength(500),
                                        RichEditor::make('about_id')
                                            ->label('About (ID)')
                                            ->columnSpanFull(),
                                    ]),
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('headline_en')
                                            ->label('Headline (EN)')
                                            ->maxLength(255),
                                        TextInput::make('availability_en')
                                            ->label('Availability label (EN)')
                                            ->maxLength(255),
                                        Textarea::make('intro_en')
                                            ->label('Hero intro (EN)')
                                            ->rows(3)
                                            ->maxLength(500),
                                        RichEditor::make('about_en')
                                            ->label('About (EN)')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                    ]),

                Section::make('Links')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('linkedin_url')->label('LinkedIn')->url()->maxLength(2048),
                                TextInput::make('github_url')->label('GitHub')->url()->maxLength(2048),
                                TextInput::make('instagram_url')->label('Instagram')->url()->maxLength(2048),
                                TextInput::make('website_url')->label('Website')->url()->maxLength(2048),
                            ]),
                    ]),

                Section::make('Media')
                    ->description('Paste public URLs. GitHub raw links work for both the avatar and the CV PDF.')
                    ->schema([
                        TextInput::make('avatar_url')
                            ->label('Avatar image URL')
                            ->url()
                            ->maxLength(2048)
                            ->placeholder('https://raw.githubusercontent.com/...'),
                        TextInput::make('cv_url')
                            ->label('CV PDF URL')
                            ->url()
                            ->maxLength(2048)
                            ->helperText('Visitors download the CV from this link.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $profile = Profile::current();
        $profile->update($this->form->getState());

        Notification::make()
            ->title('Profile saved')
            ->success()
            ->send();
    }
}
