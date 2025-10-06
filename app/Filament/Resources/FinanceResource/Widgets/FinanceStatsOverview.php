<?php

namespace App\Filament\Resources\FinanceResource\Widgets;

use App\Models\Finance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinanceStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        $currentMonth = now()->startOfMonth();
        $previousMonth = now()->subMonth()->startOfMonth();

        return [
            $this->getSalaryStat($user),
            $this->getTotalCommitmentsStat($user, $currentMonth, $previousMonth),
            $this->getMonthlySpendingStat($user, $currentMonth, $previousMonth),
            $this->getFinancialHealthStat($user, $currentMonth),
        ];
    }

    private function getSalaryStat($user): Stat
    {
        $salary = $user->profile?->salary ?? 0;
        $salaryFormatted = 'RM ' . number_format($salary, 2);

        // Analyze salary vs expenses
        $monthlyExpenses = $this->getMonthlyExpenses($user);
        $savingsRate = $salary > 0 ? ceil(($salary - $monthlyExpenses) / $salary * 100) : 0;

        $description = $this->analyzeSalaryHealth($salary, $monthlyExpenses, $savingsRate);
        $icon = $savingsRate > 20 ? 'heroicon-m-arrow-trending-up' :
                ($savingsRate > 10 ? 'heroicon-m-minus' : 'heroicon-m-arrow-trending-down');
        $color = $savingsRate > 20 ? 'success' : ($savingsRate > 10 ? 'warning' : 'danger');

        return Stat::make('Monthly Salary', $salaryFormatted)
            ->description($description)
            ->descriptionIcon($icon)
            ->color($color);
    }

    private function getTotalCommitmentsStat($user, $currentMonth, $previousMonth): Stat
    {
        $currentCommitments = $this->getTotalCommitments($user, $currentMonth);
        $previousCommitments = $this->getTotalCommitments($user, $previousMonth);

        $trend = $this->calculateTrend($currentCommitments, $previousCommitments);
        $description = $this->analyzeCommitmentTrend($trend, $currentCommitments);

        return Stat::make('Total Commitments', 'RM ' . number_format($currentCommitments, 2))
            ->description($description)
            ->descriptionIcon($trend['icon'])
            ->color($trend['color']);
    }

    private function getMonthlySpendingStat($user, $currentMonth, $previousMonth): Stat
    {
        $currentSpending = $this->getMonthlySpending($user, $currentMonth);
        $previousSpending = $this->getMonthlySpending($user, $previousMonth);

        $trend = $this->calculateTrend($currentSpending, $previousSpending);
        $description = $this->analyzeSpendingPattern($trend, $currentSpending, $user->profile?->salary ?? 0);

        return Stat::make('Monthly Spending', 'RM ' . number_format($currentSpending, 2))
            ->description($description)
            ->descriptionIcon($trend['icon'])
            ->color($trend['color']);
    }

    private function getFinancialHealthStat($user, $currentMonth): Stat
    {
        $salary = $user->profile?->salary ?? 0;
        $monthlyExpenses = $this->getMonthlyExpenses($user);
        $savings = $salary - $monthlyExpenses;
        $savingsRate = $salary > 0 ? ($savings / $salary) * 100 : 0;

        $healthScore = $this->calculateFinancialHealthScore($savingsRate, $monthlyExpenses, $salary);
        $description = $this->getFinancialHealthDescription($healthScore, $savingsRate);

        return Stat::make('Financial Health', $healthScore . '/100')
            ->description($description)
            ->descriptionIcon($this->getHealthIcon($healthScore))
            ->color($this->getHealthColor($healthScore));
    }

    // Helper methods for data analysis
    private function getMonthlyExpenses($user): float
    {
        return Finance::where('user_id', $user->id)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('amount');
    }

    private function getTotalCommitments($user, $month): float
    {
        return Finance::where('user_id', $user->id)
            ->where('created_at', '>=', $month)
            ->where('type', 'Commitment')
            ->where('created_at', '<', $month->copy()->addMonth())
            ->sum('amount');
    }

    private function getMonthlySpending($user, $month): float
    {
        return Finance::where('user_id', $user->id)
            ->where('created_at', '>=', $month)
            ->where('created_at', '<', $month->copy()->addMonth())
            ->sum('amount');
    }

    private function calculateTrend($current, $previous): array
    {
        if ($previous == 0) {
            return [
                'percentage' => $current > 0 ? 100 : 0,
                'icon' => $current > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-minus',
                'color' => $current > 0 ? 'success' : 'gray'
            ];
        }

        $percentage = (($current - $previous) / $previous) * 100;

        return [
            'percentage' => $percentage,
            'icon' => $percentage > 0 ? 'heroicon-m-arrow-trending-up' :
                     ($percentage < 0 ? 'heroicon-m-arrow-trending-down' : 'heroicon-m-minus'),
            'color' => $percentage > 0 ? 'success' : ($percentage < 0 ? 'danger' : 'gray')
        ];
    }

    private function analyzeSalaryHealth($salary, $expenses, $savingsRate): string
    {
        if ($salary == 0) return 'No salary data';

        if ($savingsRate > 30) return "Excellent savings ({$savingsRate}%)";
        if ($savingsRate > 20) return "Good savings ({$savingsRate}%)";
        if ($savingsRate > 10) return "Moderate savings ({$savingsRate}%)";
        if ($savingsRate > 0) return "Low savings ({$savingsRate}%)";

        return "Spending exceeds income";
    }

    private function analyzeCommitmentTrend($trend, $currentCommitments): string
    {
        $percentage = abs($trend['percentage']);

        if ($trend['percentage'] > 20) return "Up {$percentage}% - Monitor";
        if ($trend['percentage'] > 5) return "Up {$percentage}%";
        if ($trend['percentage'] < -10) return "Down {$percentage}% - Great!";
        if ($trend['percentage'] < -5) return "Down {$percentage}%";

        return "Stable";
    }

    private function analyzeSpendingPattern($trend, $currentSpending, $salary): string
    {
        $percentage = abs($trend['percentage']);
        $spendingRatio = $salary > 0 ? ceil(($currentSpending / $salary) * 100) : 0;

        if ($spendingRatio > 80) return "High ratio ({$spendingRatio}%)";
        if ($spendingRatio > 60) return "Moderate ({$spendingRatio}%)";

        if ($trend['percentage'] > 15) return "Up {$percentage}%";
        if ($trend['percentage'] < -15) return "Down {$percentage}%";

        return "Balanced";
    }

    private function calculateFinancialHealthScore($savingsRate, $monthlyExpenses, $salary): int
    {
        $score = 0;

        // Savings rate scoring (40 points max)
        if ($savingsRate > 30) $score += 40;
        elseif ($savingsRate > 20) $score += 30;
        elseif ($savingsRate > 10) $score += 20;
        elseif ($savingsRate > 0) $score += 10;

        // Expense ratio scoring (30 points max)
        if ($salary > 0) {
            $expenseRatio = ($monthlyExpenses / $salary) * 100;
            if ($expenseRatio < 50) $score += 30;
            elseif ($expenseRatio < 70) $score += 20;
            elseif ($expenseRatio < 90) $score += 10;
        }

        // Stability scoring (30 points max)
        $score += 30; // Base stability score

        return min(100, max(0, $score));
    }

    private function getFinancialHealthDescription($score, $savingsRate): string
    {
        if ($score >= 80) return "Excellent";
        if ($score >= 60) return "Good";
        if ($score >= 40) return "Fair";
        if ($score >= 20) return "Poor";

        return "Critical";
    }

    private function getHealthIcon($score): string
    {
        if ($score >= 80) return 'heroicon-m-heart';
        if ($score >= 60) return 'heroicon-m-check-circle';
        if ($score >= 40) return 'heroicon-m-exclamation-triangle';

        return 'heroicon-m-x-circle';
    }

    private function getHealthColor($score): string
    {
        if ($score >= 80) return 'success';
        if ($score >= 60) return 'info';
        if ($score >= 40) return 'warning';

        return 'danger';
    }
}
