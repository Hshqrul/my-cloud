<?php

namespace App\Enum;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum FinanceEnum: string implements HasLabel, HasColor
{
    case COMMITMENT = 'Commitment';
    case DEBT = 'Debt';
    case LOAN = 'Loan';
    case INCOME = 'Income';

    public function getColor(): string
    {
        return match ($this) {
            self::COMMITMENT => 'primary',
            self::DEBT => 'danger',
            self::LOAN => 'warning',
            self::INCOME => 'success',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::COMMITMENT => 'Commitment',
            self::DEBT => 'Debt',
            self::LOAN => 'Loan',
            self::INCOME => 'Income',
        };
    }
}
