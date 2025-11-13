<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OrderStats extends BaseWidget
{
    protected int|string|array $columnSpan = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('ORDER', DB::table('orders')->count())
                ->color('warning')
                ->icon('heroicon-o-shopping-cart'),
        ];
    }
}