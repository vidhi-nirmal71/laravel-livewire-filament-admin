<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

class SettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('short_des')
                ->label('Short Description')
                ->required()
                ->rows(3),

            Textarea::make('description')
                ->label('Description')
                ->required()
                ->rows(6),

            FileUpload::make('logo')
                ->label('Logo')
                ->image()
                ->directory('photos')
                ->disk('public'),

            FileUpload::make('photo')
                ->label('Photo')
                ->image()
                ->directory('photos')
                ->disk('public'),

            TextInput::make('address')->label('Address')->required(),
            TextInput::make('email')->label('Email')->email()->required(),
            TextInput::make('phone')->label('Phone')->required(),
        ]);
    }
}
