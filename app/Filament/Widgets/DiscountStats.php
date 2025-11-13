<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DiscountStats extends BaseWidget
{
    protected int|string|array $columnSpan = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('DISCOUNT', 0)
                ->color('danger')
                ->icon('heroicon-o-tag'),
        ];
    }
}