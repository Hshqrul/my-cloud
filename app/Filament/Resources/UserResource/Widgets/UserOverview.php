<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserOverview extends BaseWidget
{
    // use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListUsers::class;
    }

    protected function getStats(): array
    {
        // Get total users in the database
        $totalUsers = User::count();
        // Get total verified users in the database
        $totalVerifiedUsers = User::whereNotNull('email_verified_at')->count();
        $totalUnverifiedUsers = User::whereNull('email_verified_at')->count();
        // Calculate the percentage of verified users
        $percentageVerified = $totalUsers > 0 ? round(($totalVerifiedUsers / $totalUsers) * 100) : 0;
        $percentageUnverified = $totalUsers > 0 ? round(($totalUnverifiedUsers / $totalUsers) * 100) : 0;

        return [
            Stat::make('Total Users Verified', User::where('id', '!=', auth()->user()->id)->whereNotNull('email_verified_at')->count())
                ->description($percentageVerified . '% from total users')
                ->descriptionIcon('heroicon-c-check-badge')
                ->descriptionColor('success')
                ->chart(
                    User::whereBetween('email_verified_at', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
                        ->selectRaw('DATE(email_verified_at) as date, COUNT(*) as count')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('count', 'date')
                        ->toArray()
                )
                ->chartColor('info'),
            Stat::make('Recently Analytics', User::where('id', '!=', auth()->user()->id)->count())
                ->descriptionIcon('heroicon-c-user-group')
                ->description('Total Users')
                ->descriptionColor('info')
                ->chart(
                    User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('count', 'date')
                        ->toArray()
                )
                ->color('success'),
            Stat::make('Need Approval', User::where('id', '!=', auth()->user()->id)->whereNull('email_verified_at')->count())
                ->description($percentageUnverified . '% increase')
                ->descriptionIcon('heroicon-c-clipboard-document-check')
                ->descriptionColor('warning')
                ->chart(
                    User::whereBetween('email_verified_at', [now()->subDays(7)->startOfDay(), now()->endOfDay()])
                        ->selectRaw('DATE(email_verified_at) as date, COUNT(*) as count')
                        ->groupBy('date')
                        ->orderBy('date')
                        ->pluck('count', 'date')
                        ->toArray()
                )
                ->color('danger'),
            Stat::make('Total Users Suspended', User::where('id', '!=', auth()->user()->id)->onlyTrashed()->count())
                ->description($totalUsers > 0 ? round((User::where('id', '!=', auth()->user()->id)->onlyTrashed()->count() / $totalUsers) * 100) . '% from total users' : '0% from total users')
                ->descriptionIcon('heroicon-c-x-circle')
                ->chart([0,0]),
            // ->color('success'),
        ];
    }
}
