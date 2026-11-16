<?php

namespace App\Filament\Resources\Experiences\Tables;

use App\Models\Experience;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExperiencesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated(false)
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Experience::types()[$state] ?? $state)
                    ->color('gray'),
                TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('company')
                    ->searchable(),
                TextColumn::make('date_range')
                    ->label('Date range')
                    ->searchable(),
                TextColumn::make('tags')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => is_array($state) ? implode(', ', $state) : (string) $state)
                    ->toggleable(),
                TextColumn::make('sort_order')
                    ->label('#')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')->options(Experience::types()),
                SelectFilter::make('is_active')
                    ->label('Visibility')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Hidden',
                    ]),
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
