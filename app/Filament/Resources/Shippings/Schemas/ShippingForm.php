<?php

namespace App\Filament\Resources\Shippings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ShippingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    TextInput::make('type')
                      ->label('Type')
                    ->required()
                    ->length('191'),
                    TextInput::make('price')
                      ->label('Price')
                     ->required()
                     ->length('191'),
                     Select::make('status')
                    ->label('Status')
                    ->required()
                     ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
            ]);
    }
}
