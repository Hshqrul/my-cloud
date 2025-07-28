<?php

namespace App\Filament\Widgets;

use App\Models\Rsvp;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $attendCount = Rsvp::where('attendence', true)->sum('no_of_pax');
        $absentCount = Rsvp::where('attendence', false)->sum('no_of_pax');
        $totalResponse = Rsvp::count();
        return [
            Stat::make('Attend', $attendCount),
                // ->description('32k increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                // ->color('success'),
            Stat::make('Absent', $absentCount),
                // ->description('7% increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-down')
                // ->color('danger'),
            Stat::make('Total Response', $totalResponse),
                // ->description('3% increase')
                // ->descriptionIcon('heroicon-m-arrow-trending-up')
                // ->color('success'),
        ];
    }
}
