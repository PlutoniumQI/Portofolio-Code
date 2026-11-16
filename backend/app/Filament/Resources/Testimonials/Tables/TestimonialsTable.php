<?php

namespace App\Filament\Resources\Testimonials\Tables;

use App\Models\Testimonial;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->paginated(false)
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Avatar')
                    ->getStateUsing(fn (Testimonial $record): ?string => $record->avatarUrl())
                    ->circular()
                    ->imageSize(40),
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Testimonial $record): ?string => $record->company),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Testimonial::categories()[$state] ?? $state)
                    ->color('info'),
                TextColumn::make('rating')
                    ->numeric()
                    ->suffix(' / 5'),
                TextColumn::make('sort_order')
                    ->label('#')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('category')->options(Testimonial::categories()),
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
