<?php

namespace App\Filament\Resources\ProductReviews\Schemas;

use App\Models\Product;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Choose user (review by)
            Select::make('user_id')
                ->label('Review By')
                ->options(User::query()->pluck('name', 'id')->toArray())
                ->searchable()
                ->preload()
                ->required(),

            // Choose product
            Select::make('product_id')
                ->label('Product')
                ->options(Product::query()->pluck('name', 'id')->toArray())
                ->searchable()
                ->preload()
                ->required(),

            // Rating (1-5)
            Select::make('rate')
                ->label('Rate')
                ->options([
                    1 => '1',
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                ])
                ->required(),

            // Review text
            Textarea::make('review')
                ->label('Review')
                ->rows(4)
                ->nullable(),

            // Status
            Select::make('status')
                ->label('Status')
                ->options([
                    'active' => 'active',
                    'inactive' => 'inactive',
                ])
                ->default('active')
                ->required(),
        ]);
    }
}
