<?php

namespace App\Filament\Resources\Skills\Tables;

use App\Models\Skill;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated(false)
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Skill::categories()[$state] ?? $state)
                    ->color(fn (string $state): string => $state === Skill::CATEGORY_HARD ? 'success' : 'info'),
                TextColumn::make('level')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Skill::levels()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Skill::LEVEL_BEGINNER => 'gray',
                        Skill::LEVEL_INTERMEDIATE => 'info',
                        Skill::LEVEL_ADVANCED => 'success',
                        Skill::LEVEL_EXPERT => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('#')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('category')->options(Skill::categories()),
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
