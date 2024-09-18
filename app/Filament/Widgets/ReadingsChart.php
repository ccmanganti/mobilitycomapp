<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Readings;
use App\Models\Gloves;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReadingsChart extends ChartWidget
{
    protected static ?string $heading = 'Glove Realtime Flex Values';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '1s';
    protected static string $color = 'info';

    protected function getData(): array
    {
        // Get the current authenticated user
        $user = Auth::user();

        // Find the default glove for the current user
        $defaultGlove = $user->gloves()->where('default', true)->first();

        // If no default glove is found, return an empty dataset
        if (!$defaultGlove) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Get the latest reading associated with the default glove
        $latestReading = Readings::where('gloves_id', $defaultGlove->id)
            ->where('created_at', '>=', Carbon::now()->subMinutes(2)) // Check if created within the last 2 minutes
            ->latest()
            ->first();

        // If there's no reading within the last 2 minutes, return an empty dataset
        if (!$latestReading) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Use the reading's finger values
        $data = [
            $latestReading->finger_1,
            $latestReading->finger_2,
            $latestReading->finger_3,
            $latestReading->finger_4,
            $latestReading->finger_5,
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Finger Flex Values',
                    'data' => $data,
                    'backgroundColor' => 'orange',
                    'borderColor' => 'white', // Optionally set a color for the chart
                ],
            ],
            'labels' => ['Pinky', 'Ring', 'Middle', 'Index', 'Thumb'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // You can also return 'line' if you want a line chart
    }
}
