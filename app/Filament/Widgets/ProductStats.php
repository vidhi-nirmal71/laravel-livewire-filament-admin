<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class ProductStats extends BaseWidget
{
     protected int|string|array $columnSpan = 1;
    protected function getStats(): array
    {
        
        return [
            Stat::make('PRODUCTS', DB::table('products')->count())
                ->color('primary')
                ->icon('heroicon-o-shopping-bag'),
        ];
    }
}