<?php

namespace App\Filament\Resources\ProductVariantTypes\Schemas;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductVariantTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(191)
                    ->placeholder('Enter internal name'),

                TextInput::make('display_name')
                    ->label('Display Name')
                    ->required()
                    ->maxLength(191)
                    ->placeholder('Enter display name'),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active'),

            ]);
    }
}
