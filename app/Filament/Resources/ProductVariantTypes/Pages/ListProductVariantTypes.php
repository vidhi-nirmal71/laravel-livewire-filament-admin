<?php

namespace App\Filament\Resources\ProductVariantTypes\Pages;

use App\Filament\Resources\ProductVariantTypes\ProductVariantTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductVariantTypes extends ListRecords
{
    protected static string $resource = ProductVariantTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
