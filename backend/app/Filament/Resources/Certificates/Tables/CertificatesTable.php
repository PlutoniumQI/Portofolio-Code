<?php

namespace App\Filament\Resources\Certificates\Tables;

use App\Models\Certificate;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CertificatesTable
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
                    ->getStateUsing(fn (Certificate $record): ?string => $record->imageUrl())
                    ->square()
                    ->imageSize(48),
                TextColumn::make('title_id')
                    ->label('Title')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('issuer')
                    ->searchable(),
                TextColumn::make('issued_at')
                    ->label('Issued')
                    ->date()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('#')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
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
