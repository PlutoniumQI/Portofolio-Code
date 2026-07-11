<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Support\Icons\Heroicon;
use App\Models\User;
use UnitEnum;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;
    protected static ?string $navigationLabel = 'Photo Profile About';
    protected static ?string $title = 'Photo Profile About';
    protected static string | UnitEnum | null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $user = User::first();
        if ($user) {
            // If the stored value is a full URL, we show it as an external URL
            // If it's a relative path, pass it to the file upload field
            $isExternalUrl = Str::startsWith($user->about_photo, ['http://', 'https://', '//']);
            $this->form->fill([
                'about_photo'          => $isExternalUrl ? null : $user->about_photo,
                'about_photo_external' => $isExternalUrl ? $user->about_photo : null,
            ]);
        } else {
            $this->form->fill();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('about_photo')
                    ->label('Upload Photo Profile')
                    ->image()
                    ->disk('public')
                    ->directory('about-photos')
                    ->imageEditor()
                    ->maxSize(5120)
                    ->helperText('Upload an image (JPG, PNG, GIF, WebP — max 5MB). On production, images are stored on Supabase Storage.'),
                \Filament\Forms\Components\TextInput::make('about_photo_external')
                    ->label('Or Paste External Photo URL')
                    ->placeholder('e.g. https://imgur.com/avatar.png')
                    ->helperText('Optional: Paste a direct link to a profile photo hosted externally. This will override the uploaded photo if filled.'),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Form::make([
                    \Filament\Schemas\Components\EmbeddedSchema::make('form'),
                ])
                ->livewireSubmitHandler('submit')
                ->footer([
                    \Filament\Schemas\Components\Actions::make([
                        \Filament\Actions\Action::make('save')
                            ->label('Save Settings')
                            ->submit('submit'),
                    ]),
                ]),
            ]);
    }

    public function submit(): void
    {
        $user = User::first();
        if (!$user) {
            return;
        }

        $state = $this->form->getState();

        // External URL takes priority if filled
        $externalUrl = trim($state['about_photo_external'] ?? '');
        if ($externalUrl) {
            $user->update(['about_photo' => $externalUrl]);
            Notification::make()
                ->title('Settings saved successfully')
                ->success()
                ->send();
            return;
        }

        // Handle uploaded file — generate full public URL so it works anywhere
        $uploadedPath = $state['about_photo'] ?? null;
        if ($uploadedPath) {
            // Generate the full public URL from the disk
            $fullUrl = Storage::disk('public')->url($uploadedPath);
            $user->update(['about_photo' => $fullUrl]);
        } else {
            // No change — keep existing
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
