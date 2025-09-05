<?php

namespace App\Enums;

use App\Foundations\Enum;

enum ActivityEventType: string
{
    use Enum;

    // Case section started
    case MODEL = 'model';
    case LOGIN = 'login';
    case LOGOUT = 'logout';
    case REGISTER = 'register';
}
