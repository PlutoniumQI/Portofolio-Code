<?php

namespace App\Filament\Resources\Messages\Schemas;

use App\Models\Message;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Message')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('type')
                                    ->options(Message::types())
                                    ->disabled(),
                                TextInput::make('created_at')
                                    ->label('Received')
                                    ->disabled(),
                                TextInput::make('name')
                                    ->disabled(),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->disabled(),
                                TextInput::make('subject')
                                    ->disabled()
                                    ->columnSpanFull(),
                                Textarea::make('message')
                                    ->rows(8)
                                    ->disabled()
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Moderation')
                    ->schema([
                        Toggle::make('is_read')
                            ->label('Mark as read'),
                        Toggle::make('is_approved')
                            ->label('Approved for the public guestbook')
                            ->visible(fn (?Message $record): bool => $record?->type === Message::TYPE_GUESTBOOK),
                    ]),

                Section::make('Metadata')
                    ->collapsed()
                    ->schema([
                        TextInput::make('ip_address')->disabled(),
                        Textarea::make('user_agent')->disabled()->columnSpanFull(),
                    ]),
            ]);
    }
}
