<?php

namespace App\Filament\Resources\Settings\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('S.N.'),
                TextColumn::make('short_des')->label('Short Description')->limit(60),
                ImageColumn::make('logo')->label('Logo')->disk('public')->height(40)->width(40),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('phone')->label('Phone'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([]); // no bulk delete
    }
}
