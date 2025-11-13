<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UsersPieChart extends ChartWidget
{
    protected ?string $heading = 'Last 7 Days Registered Users';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 2;

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        try {
            // Inclusive 7-day window: from 6 days ago (start of day) until today (end of day)
            $start = Carbon::now()->subDays(6)->startOfDay();
            $end = Carbon::now()->endOfDay();

            // Query: count users per weekday, also fetch the weekday index so we can order results
            // WEEKDAY(created_at) returns 0 = Monday ... 6 = Sunday in MySQL
            $users = User::query()
                ->selectRaw("DAYNAME(created_at) as day, COUNT(*) as count, WEEKDAY(created_at) as weekday_index")
                ->whereBetween('created_at', [$start->toDateTimeString(), $end->toDateTimeString()])
                ->groupByRaw('weekday_index, day')
                ->orderByRaw('weekday_index')
                ->get()
                ->keyBy('day'); // keyed by DAYNAME

            // Ensure labels are Monday..Sunday in that exact order
            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

            // Build data array so each label corresponds to the dataset value (0 if missing)
            $data = [];
            foreach ($days as $day) {
                $data[] = isset($users[$day]) ? (int) $users[$day]->count : 0;
            }

            Log::debug('UsersPieChart getData', [
                'start' => $start->toDateTimeString(),
                'end' => $end->toDateTimeString(),
                'counts' => $users->mapWithKeys(fn($r) => [$r->day => (int)$r->count])->toArray(),
                'final_data' => $data,
            ]);

            return [
                'datasets' => [
                    [
                        'data' => $data,
                        'backgroundColor' => [
                            '#EF4444', '#F59E0B', '#10B981', '#3B82F6',
                            '#8B5CF6', '#EC4899', '#06B6B4'
                        ],
                    ],
                ],
                'labels' => $days,
            ];
        } catch (\Throwable $e) {
            Log::error('UsersPieChart exception: ' . $e->getMessage(), ['exception' => $e]);

            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
            return [
                'datasets' => [['data' => array_fill(0, 7, 0)]],
                'labels' => $days,
            ];
        }
    }
        protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],       
            ],
        ];
    }
    protected function getChartHeight(): ?int
{
    return 300; // Adjust this value to match EarningsChart height
}
}
