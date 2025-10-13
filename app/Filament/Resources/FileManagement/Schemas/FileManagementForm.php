<?php

namespace App\Filament\Resources\FileManagement\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FileManagementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),

            TextInput::make('title')
                ->label('Title')
                ->required()
                ->maxLength(255),

            Select::make('status')
                ->label('Status')
                ->options([
                    'Active' => 'Active',
                    'Inactive' => 'Inactive',
                ])
                ->required(),
        ]);
    }
}
