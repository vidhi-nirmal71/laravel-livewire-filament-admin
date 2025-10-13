<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')
                ->label('Coupon Code')
                ->required()
                ->maxLength(191)
                ->unique(ignoreRecord: true),

            Select::make('type')
                ->label('Type')
                ->options([
                    'fixed' => 'Fixed',
                    'percent' => 'Percent',
                ])
                ->required(),

            TextInput::make('value')
                ->label('Value')
                ->numeric()
                ->required()
                ->minValue(0)
                ->helperText('Enter an absolute value for fixed or percent number for percent type'),

            Select::make('status')
                ->label('Status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->default('active')
                ->required(),
        ]);
    }
}
