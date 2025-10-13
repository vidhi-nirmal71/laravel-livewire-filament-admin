<?php

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // S.N.
                TextColumn::make('id')->label('S.N.')->sortable(),

                // Coupon code
                TextColumn::make('code')
                    ->label('Coupon Code')
                    ->searchable()
                    ->sortable(),

                // Type badge
                BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'fixed',
                        'warning' => 'percent',
                    ])
                    ->sortable(),

                // Formatted Value
                TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(fn ($state, $record) => self::formatValue($state, $record))
                    ->sortable(),

                // Status badge
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'inactive',
                    ])
                    ->sortable(),

                // Created At (optional)
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
            ]),
            ]);
    }

    protected static function formatValue($state, $record): string
    {
        if (! $record) {
            return (string) $state;
        }

        if (isset($record->type) && $record->type === 'percent') {
            return number_format((float)$state, 2) . '%';
        }

        return '$' . number_format((float)$state, 2);
    }
}
