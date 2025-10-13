<?php

namespace App\Filament\Resources\ProductReviews\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class ProductReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // S.N.
                TextColumn::make('id')->label('S.N.')->sortable(),

                // Review By (user name)
                TextColumn::make('user_name')
                    ->label('Review By')
                    ->getStateUsing(fn ($record) => $record->user_info?->name ?? ($record->user?->name ?? '—'))
                    ->searchable(),

                // Product title
                TextColumn::make('product_title')
                    ->label('Product Title')
                    ->getStateUsing(fn ($record) => $record->product?->name ?? '—')
                    ->searchable(),

                // Review (short)
                TextColumn::make('review')
                    ->label('Review')
                    ->limit(60),

                // Rate as stars (HTML)
                TextColumn::make('rate')
                    ->label('Rate')
                    ->formatStateUsing(fn ($state) => self::renderStars($state))
                    ->html()
                    ->sortable(),

                // Date
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),

                // Status badge
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    protected static function renderStars($state): string
    {
        $count = (int) $state;
        $count = max(0, min(5, $count));
        $filled = str_repeat('<span style="color:#f59e0b; font-size:14px;">★</span>', $count);
        $empty  = str_repeat('<span style="color:#ddd; font-size:14px;">★</span>', 5 - $count);
        return $filled . $empty;
    }
}
