<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated(false)
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->getStateUsing(fn (Project $record): ?string => $record->image_path ?: $record->image_url)
                    ->disk('public')
                    ->square()
                    ->imageSize(48),
                TextColumn::make('title')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Project $record): ?string => $record->url ?: null),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Project::statuses()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Project::STATUS_PUBLISHED => 'success',
                        Project::STATUS_COMING_SOON => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('tags')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => is_array($state) ? implode(', ', $state) : (string) $state)
                    ->toggleable(),
                TextColumn::make('sort_order')
                    ->label('#')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(Project::statuses()),
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
