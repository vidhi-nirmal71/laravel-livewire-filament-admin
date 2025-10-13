<?php

namespace App\Filament\Resources\FileManagement;

use App\Filament\Resources\FileManagement\Pages\CreateFileManagement;
use App\Filament\Resources\FileManagement\Pages\EditFileManagement;
use App\Filament\Resources\FileManagement\Pages\ListFileManagement;
use App\Filament\Resources\FileManagement\Schemas\FileManagementForm;
use App\Filament\Resources\FileManagement\Tables\FileManagementTable;
use App\Models\Filter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FileManagementResource extends Resource
{
    protected static ?string $model = Filter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'File Management';

    public static function form(Schema $schema): Schema
    {
        return FileManagementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FileManagementTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFileManagement::route('/'),
            'edit' => EditFileManagement::route('/{record}/edit'),
        ];
    }
}
