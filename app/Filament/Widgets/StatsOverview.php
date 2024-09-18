<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Gloves;
use App\Models\Readings;
use App\Models\Actions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '1s';
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $user = Auth::user();
        $gloves = $user->gloves()->count();
        $actionsCount = $user->gloves()->withCount('actions')->count();
        $averageActions = $gloves > 0 ? round($actionsCount / $gloves) : 0;
        $recentlyActiveGlovesCount = $user->gloves()
        ->whereHas('readings', function ($query) {
            $query->where('created_at', '>=', Carbon::now()->subMinute(2));
        })
        ->distinct()
        ->count();
        $transmitting = $recentlyActiveGlovesCount > 0 ? 'danger' : 'gray';
        return [
            Stat::make('Gloves', $gloves)
                ->description('No. of Registered Gloves')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('success'),
            Stat::make('Current Notification', number_format($averageActions, 0))
                ->description('Avg. No. of Actions Registered on Gloves')
                ->descriptionIcon('heroicon-s-circle-stack')
                ->color('primary'),
            Stat::make('Gloves General Status', $recentlyActiveGlovesCount)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('No. of Gloves Currently Active')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color($transmitting),
        ];
    }
}
