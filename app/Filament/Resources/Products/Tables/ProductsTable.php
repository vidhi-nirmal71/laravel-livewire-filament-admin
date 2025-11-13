<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Support\Str;

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

                // Photo — fixed to handle 'storage/p1/...' DB values
                ImageColumn::make('image')
                    ->label('Photo')
                    ->getStateUsing(function ($record) {
                        // if null
                        $img = $record->image ?? null;
                        if (! $img) {
                            return null;
                        }

                        // full URL already (http/https)
                        if (Str::startsWith($img, ['http://', 'https://'])) {
                            return $img;
                        }

                        // stored as 'storage/p1/...' (public path) -> strip 'storage/' and use public disk
                        if (Str::startsWith($img, 'storage/')) {
                            return Str::after($img, 'storage/'); // returns 'p1/download (76).png'
                        }

                        // stored as 'public/p1/...' -> strip 'public/' if present
                        if (Str::startsWith($img, 'public/')) {
                            return Str::after($img, 'public/');
                        }

                        // otherwise assume it's already relative to the public disk
                        return $img;
                    })
                    ->disk('public') // ensures files are read from storage/app/public (symlinked to public/storage)
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
