<?php

namespace App\Filament\Resources\ProductVariantTypes\Pages;

use App\Filament\Resources\ProductVariantTypes\ProductVariantTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductVariantType extends EditRecord
{
    protected static string $resource = ProductVariantTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
