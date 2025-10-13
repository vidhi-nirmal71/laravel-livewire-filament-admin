<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // S.N.
                TextColumn::make('id')->label('S.N.')->sortable(),

                // Title
                TextColumn::make('title')->label('Title')->searchable()->wrap()->limit(50),

                // Category path (from accessor)
                TextColumn::make('category_path')
                    ->label('Category')
                    ->getStateUsing(fn ($record) => $record->category_path ?? '-')
                    ->searchable()
                    ->wrap(),
   
                    
                // Is featured (Yes/No)
               TextColumn::make('is_active')
                ->label('Featured')
                ->getStateUsing(fn ($r) => $r && $r->is_active ? 'Yes' : 'No'),


                // Price (formatted like screenshot)
                TextColumn::make('price')
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => '$. ' . number_format((float) $state, 2) . ' /-')
                    ->sortable(),

                // Discount (show percent OFF when price present)
                TextColumn::make('discount')
                    ->label('Discount')
                    ->formatStateUsing(function ($state, $record) {
                        if (! $state || ! (float) $record->price) {
                            return '-';
                        }
                        $pct = round(((float) $state / max(1, (float) $record->price)) * 100);
                        return $pct . '% OFF';
                    })
                    ->sortable(),

                TextColumn::make('size')->label('Size'),
                TextColumn::make('condition')->label('Condition'),
                TextColumn::make('brand.title')->label('Brand')->default('-'),

                // SKU (not in migration — show N/A when not present)
                TextColumn::make('sku')
                    ->label('SKU')
                    ->getStateUsing(fn ($record) => $record->sku ?? 'N/A'),

                // Stock as small badge
                TextColumn::make('stock')
                    ->label('Stock')
                    ->formatStateUsing(fn ($s) => "<span class='inline-flex items-center px-2 py-1 rounded text-white bg-blue-600'>$s</span>")
                    ->html()
                    ->sortable(),

                // Photo
                ImageColumn::make('image')
                    ->label('Photo')
                    ->disk('public')
                    ->width(60)
                    ->height(60)
                    ->rounded(),

                // Status badge
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ])
                    ->sortable(),

                // Created date (optional toggle)
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
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
