<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            FileUpload::make('photo')
                ->image()
                ->directory('banners')
                ->disk('public')
                ->preserveFilenames()
                ->maxSize(2048),

            Textarea::make('description')
                ->columnSpanFull(),

            Select::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->default('inactive')
                ->required(),
            Select::make('link_type')
                ->options([
                    'product' => 'Product',
                    'category' => 'Category',
                    'url' => 'URL',
                    'discount' => 'Discount',
                ])
                ->nullable(),
            TextInput::make('link')
                ->label('Link')
                ->maxLength(255)
                ->nullable(),

        ]);
    }
}
