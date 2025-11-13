<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BlogPostsChart;
use App\Filament\Widgets\EarningsChart;
use App\Filament\Widgets\UsersPieChart;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $title = 'Dashboard';
    

    // HEADER STATS (CATEGORY, PRODUCTS, etc.)
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\CategoryStats::class,
            \App\Filament\Widgets\ProductStats::class,
            \App\Filament\Widgets\OrderStats::class,
            \App\Filament\Widgets\DiscountStats::class,
            EarningsChart::class,
            UsersPieChart::class,
        ];
    }

       protected function getWidgets(): array
    {
        return [
            EarningsChart::class,
            UsersPieChart::class,
        ];
    }
    // 2-column layout on medium screens
 public function getHeaderWidgetsColumns(): int | array
    {
        return 4;
    }

    protected function getColumns(): int | array
    {
        return 2; // Charts side by side
    }
}