<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use App\Models\Category;
use App\Models\Brand;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Title')
                    ->required(),

                TextInput::make('seo_title')
                    ->label('SEO Title'),

                Textarea::make('summary')
                    ->label('Summary')
                    ->columnSpanFull(),

                Textarea::make('seo_description')
                    ->label('SEO Description')
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active')
                    ->required(),

                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(0),

                Toggle::make('is_featured')
                    ->label('Featured'),

                FileUpload::make('photo')
                    ->label('Photo')
                    ->image()
                    ->directory('categories'),

                Select::make('parent_id')
                    ->label('Parent Category')
                    ->options(Category::pluck('title', 'id')->toArray())
                    ->searchable()
                    ->nullable(),

               Select::make('brands')
                    ->label('Associated Brands')
                    ->multiple()
                    ->relationship('brands', 'title')
                    ->searchable()
                    ->preload()
                    ->helperText('Select one or more brands to associate with this category.'),
            ]);
    }
}
