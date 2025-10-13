<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Brand;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                Select::make('cat_id')
                    ->label('Category')
                    ->options(fn () => Category::whereNull('parent_id')->pluck('title', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('child_cat_id')
                    ->label('Sub Category')
                    ->options(fn () => Category::whereNotNull('parent_id')->pluck('title', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Select::make('brand_id')
                    ->label('Brand')
                    ->options(fn () => Brand::pluck('title', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->nullable(),

                Textarea::make('summary')
                    ->label('Summary')
                    ->columnSpanFull()
                    ->nullable(),

                Textarea::make('description')
                    ->label('Description')
                    ->columnSpanFull()
                    ->nullable(),

                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->required()
                    ->default(0)
                    ->prefix('$. '),

                TextInput::make('discount')
                    ->label('Discount (absolute)')
                    ->numeric()
                    ->default(0)
                    ->helperText('If you store absolute discount, the table displays percent computed from price.'),

                TextInput::make('size')
                    ->label('Size')
                    ->maxLength(50)
                    ->default('M'),

                Select::make('condition')
                    ->label('Condition')
                    ->options([
                        'default' => 'Default',
                        'new' => 'New',
                        'hot' => 'Hot',
                    ])
                    ->default('default')
                    ->required(),

                TextInput::make('stock')
                    ->label('Stock')
                    ->numeric()
                    ->default(1),

                Toggle::make('is_featured')
                    ->label('Is Featured')
                    ->default(false),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('inactive')
                    ->required(),

                FileUpload::make('image')
                    ->label('Photo')
                    ->image()
                    ->directory('products')
                    ->disk('public')
                    ->imagePreviewHeight('80'),
            ]);
    }
}
