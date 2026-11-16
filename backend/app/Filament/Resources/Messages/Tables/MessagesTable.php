<?php

namespace App\Filament\Resources\Messages\Tables;

use App\Models\Message;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Message::types()[$state] ?? $state)
                    ->color(fn (string $state): string => $state === Message::TYPE_GUESTBOOK ? 'info' : 'success'),
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Message $record): string => $record->email),
                TextColumn::make('subject')
                    ->searchable()
                    ->placeholder('—')
                    ->limit(40),
                TextColumn::make('message')
                    ->limit(60)
                    ->toggleable(),
                IconColumn::make('is_read')
                    ->label('Read')
                    ->boolean(),
                ToggleColumn::make('is_approved')
                    ->label('Approved')
                    ->visible(fn (?Message $record): bool => $record?->type === Message::TYPE_GUESTBOOK),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')->options(Message::types()),
                TernaryFilter::make('is_read')->label('Read status'),
                TernaryFilter::make('is_approved')->label('Guestbook approval'),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('toggleRead')
                    ->label(fn (Message $record): string => $record->is_read ? 'Mark unread' : 'Mark read')
                    ->icon('heroicon-o-envelope')
                    ->action(fn (Message $record) => $record->update(['is_read' => ! $record->is_read])),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Message $record): bool => $record->type === Message::TYPE_GUESTBOOK && ! $record->is_approved)
                    ->action(fn (Message $record) => $record->update(['is_approved' => true])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
