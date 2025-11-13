<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EarningsChart extends ChartWidget
{
    protected ?string $heading = 'Earnings Overview';
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        try {
            $data = [];
            $labels = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i)->startOfMonth();
                $labels[] = $date->format('M Y');

                $amount = DB::table('orders')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->sum('total_amount');

                $data[] = (float) $amount;
            }

            // Server log for debugging
            Log::debug('EarningsChart getData', ['labels' => $labels, 'data' => $data]);

            return [
                'datasets' => [
                    [
                        'label' => 'Monthly Earnings ($)',
                        'data' => $data,
                        'borderColor' => '#3B82F6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'fill' => true,
                        'tension' => 0.4,
                    ],
                ],
                'labels' => $labels,
            ];
        } catch (\Throwable $e) {
            // Log the exception and return empty chart so UI still renders
            Log::error('EarningsChart exception: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return [
                'datasets' => [
                    [
                        'label' => 'Monthly Earnings ($)',
                        'data' => array_fill(0, 12, 0),
                    ],
                ],
                'labels' => array_map(function ($i) { return Carbon::now()->subMonths($i)->format('M Y'); }, range(11, 0)),
            ];
        }
    }
        protected function getChartHeight(): ?int
    {
        return 300; // Adjust this value to match EarningsChart height
    }
}