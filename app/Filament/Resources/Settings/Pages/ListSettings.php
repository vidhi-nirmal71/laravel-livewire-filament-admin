<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingsResource;
use App\Models\Settings;
use Filament\Resources\Pages\ListRecords;

class ListSettings extends ListRecords
{
    protected static string $resource = SettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function mount(): void
    {
        parent::mount();

        $record = Settings::first();

        if (! $record) {
            // create a default/empty settings row so edit always opens
            $record = Settings::create([
                'short_des' => '',
                'description' => '',
                'logo' => '',
                'photo' => '',
                'address' => '',
                'phone' => '',
                'email' => '',
            ]);
        }

         $editUrl = SettingsResource::getUrl('edit', ['record' => $record->getKey()]);

        $this->redirect($editUrl);
    }
}
