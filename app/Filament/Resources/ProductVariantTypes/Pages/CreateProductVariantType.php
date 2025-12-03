<?php

namespace App\Filament\Resources\ProductVariantTypes\Pages;

use App\Filament\Resources\ProductVariantTypes\ProductVariantTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductVariantType extends CreateRecord
{
    protected static string $resource = ProductVariantTypeResource::class;
}
