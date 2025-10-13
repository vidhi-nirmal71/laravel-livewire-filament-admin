<?php

namespace App\Filament\Resources\FileManagement\Pages;

use App\Filament\Resources\FileManagement\FileManagementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFileManagement extends ListRecords
{
    protected static string $resource = FileManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
