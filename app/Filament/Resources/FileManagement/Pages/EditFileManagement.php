<?php

namespace App\Filament\Resources\FileManagement\Pages;

use App\Filament\Resources\FileManagement\FileManagementResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFileManagement extends EditRecord
{
    protected static string $resource = FileManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
