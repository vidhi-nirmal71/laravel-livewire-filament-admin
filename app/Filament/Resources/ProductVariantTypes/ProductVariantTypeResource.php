<?php

namespace App\Filament\Resources\ProductVariantTypes;

use App\Filament\Resources\ProductVariantTypes\Pages\CreateProductVariantType;
use App\Filament\Resources\ProductVariantTypes\Pages\EditProductVariantType;
use App\Filament\Resources\ProductVariantTypes\Pages\ListProductVariantTypes;
use App\Filament\Resources\ProductVariantTypes\Schemas\ProductVariantTypeForm;
use App\Filament\Resources\ProductVariantTypes\Tables\ProductVariantTypesTable;
use App\Models\ProductVariantType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductVariantTypeResource extends Resource
{
    protected static ?string $model = ProductVariantType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductVariantTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductVariantTypesTable::configure($table);
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
            'index' => ListProductVariantTypes::route('/'),
            'create' => CreateProductVariantType::route('/create'),
            'edit' => EditProductVariantType::route('/{record}/edit'),
        ];
    }
}
