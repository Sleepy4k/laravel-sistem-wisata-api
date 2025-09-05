<?php

namespace App\Enums;

use App\Foundations\Enum;

enum ReportLogType: string
{
    use Enum;

    // Case section started
    case DEBUG = 'debug';
    case ERROR = 'error';
    case ALERT = 'alert';
    case INFO = 'info';
    case WARNING = 'warning';
}
