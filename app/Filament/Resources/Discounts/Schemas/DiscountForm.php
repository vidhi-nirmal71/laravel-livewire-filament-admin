<?php

namespace App\Filament\Resources\Discounts\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->label('Type')
                    ->options([
                        'percentage' => 'Percentage',
                        'amount'     => 'Amount',
                    ])
                    ->required(),

                TextInput::make('value')
                    ->label('Value')
                    ->numeric()
                    ->required()
                    ->minValue(0),

                DateTimePicker::make('starts_at')
                    ->label('Start Date & Time')
                    ->required(),

                DateTimePicker::make('ends_at')
                    ->label('End Date & Time')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

                // ✅ Multi-select for categories from DB
                Select::make('categories')
                    ->label('Categories')
                    ->multiple()
                    ->relationship('categories', 'title') // uses Discount::categories()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
