<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Serial number (S.N.)
                TextColumn::make('id')
                    ->label('S.N.')
                    ->sortable(),

                // Category title
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),

                // Slug
                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable(),

                // ✅ Is Parent (computed column)
                TextColumn::make('is_parent')
                    ->label('Is Parent')
                    ->getStateUsing(fn ($record) => $record->parent_id === null ? 'Yes' : 'No'),

                // Parent Category (show parent title instead of ID)
                TextColumn::make('parent.title')
                    ->label('Parent Category')
                    ->default('-'),

                // Photo (image preview)
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->square(),

                // Status (badge style)
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'active',
                        'danger' => 'inactive',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
