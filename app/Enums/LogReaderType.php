<?php

namespace App\Enums;

use App\Foundations\Enum;

enum LogReaderType: string
{
    use Enum;

    // Case section started
    case DAILY = 'daily';
    case SINGLE = 'single';
}
