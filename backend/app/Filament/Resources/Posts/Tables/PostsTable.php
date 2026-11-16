<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover')
                    ->label('Cover')
                    ->getStateUsing(fn (Post $record): ?string => $record->coverUrl())
                    ->square()
                    ->imageSize(48),
                TextColumn::make('title_id')
                    ->label('Title')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Post $record): ?string => $record->slug),
                TextColumn::make('category')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Post::statuses()[$state] ?? $state)
                    ->color(fn (string $state): string => $state === Post::STATUS_PUBLISHED ? 'success' : 'gray'),
                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(Post::statuses()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
