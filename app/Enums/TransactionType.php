<?php

namespace App\Enums;

use App\Foundations\Enum;

enum TransactionType: string
{
    use Enum;

    // Case section started
    case INCOME = 'income';
    case EXPENSE = 'outcome';
}
