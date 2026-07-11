<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')
                    ->description('Login credentials and identity.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Full name'),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('user@example.com'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->helperText('Leave blank to keep current password.'),
                                DateTimePicker::make('email_verified_at')
                                    ->label('Email Verified At'),
                            ]),
                    ]),

                Section::make('Profile Photo')
                    ->description('Photo displayed in the About section of your portfolio.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('about_photo')
                            ->label('About Photo (Hover Image)')
                            ->image()
                            ->disk('public')
                            ->directory('about-photos')
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
