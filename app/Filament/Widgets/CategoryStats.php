<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class CategoryStats extends BaseWidget
{
    protected int|string|array $columnSpan = 1; 
    protected function getStats(): array
    {
        return [
            Stat::make('CATEGORY', DB::table('categories')->count())
                ->color('success')
                ->icon('heroicon-o-folder'),
        ];
    }
}

