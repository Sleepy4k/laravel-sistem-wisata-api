<?php

namespace App\Enums;

use App\Foundations\Enum;

enum FieldInputType: string
{
    use Enum;

    // Case section started
    case TEXT = 'text';
    case NUMBER = 'number';
    case DATE = 'date';
    case SELECT = 'select';
    case RADIO = 'radio';
    case CHECKBOX = 'checkbox';
    case TEXTAREA = 'textarea';
}
