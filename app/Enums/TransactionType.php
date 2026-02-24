<?php

namespace App\Enums;

use App\Foundations\Enum;

enum TransactionType: string
{
    use Enum;

    // Case section started
    case INCOME = 'income';
    case EXPENSE = 'outcome';

    // Translated case started
    public static function fromCase(string $case): ?string
    {
        return match (strtolower($case)) {
            'income' => "Pemasukan",
            'outcome' => "Pengeluaran",
            default => null,
        };
    }

    // convert from label to case
    public static function fromLabel(string $label): ?self
    {
        return match (strtolower($label)) {
            'pemasukan' => self::INCOME,
            'pengeluaran' => self::EXPENSE,
            default => null,
        };
    }
}
