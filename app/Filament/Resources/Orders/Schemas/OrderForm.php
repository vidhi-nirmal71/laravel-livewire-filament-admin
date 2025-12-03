<?php

namespace App\Filament\Resources\Orders\Schemas;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\Order;


class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        // get valid statuses from Order model
        $statuses = Order::getValidStatuses();

        // convert to ['new' => 'New', 'process' => 'Process', ...] format
        $options = array_combine(
            $statuses,
            array_map(fn ($s) => ucfirst($s), $statuses)
        );

        return $schema
            ->components([
                Select::make('status')
                    ->label('Status')
                    ->options($options)
                    ->required()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }
}
