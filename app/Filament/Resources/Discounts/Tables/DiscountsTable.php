<?php

namespace App\Filament\Resources\Discounts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;

class DiscountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
             ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),

                // Use BadgeColumn + getStateUsing() instead of enum()
                BadgeColumn::make('type')
                    ->label('Type')
                    ->getStateUsing(fn ($record) => $record->type === 'percentage' ? 'Percentage' : 'Amount')
                    ->colors([
                        // map color => displayed state (no closures required)
                        'warning' => 'Percentage',
                        'success' => 'Amount',
                    ])
                    ->sortable(),

                // Format value depending on type (percentage shows %)
                TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record?->type === 'percentage') {
                            // trim unnecessary zeros like 10.00 -> 10
                            $s = number_format((float)$state, 2, '.', '');
                            $s = rtrim(rtrim($s, '0'), '.');
                            return $s . '%';
                        }
                        return number_format((float)$state, 2);
                    })
                    ->sortable(),

                TextColumn::make('starts_at')->label('Starts At')->dateTime()->sortable(),
                TextColumn::make('ends_at')->label('Ends At')->dateTime()->sortable(),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')->label('Created')->dateTime()->sortable(),
            ])
            ->filters([
                // ...
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
