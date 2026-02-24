<?php

namespace App\Enums;

use App\Foundations\Enum;

enum FormulaOperator: string
{
    use Enum;

    case ADD      = '+';
    case SUBTRACT = '-';
    case MULTIPLY = '*';
    case DIVIDE   = '/';
    case MODULO   = '%';
    case POWER    = '^';

    /**
     * Return a human-readable label for each operator.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADD      => 'Tambah (+)',
            self::SUBTRACT => 'Kurang (-)',
            self::MULTIPLY => 'Kali (*)',
            self::DIVIDE   => 'Bagi (/)',
            self::MODULO   => 'Modulo (%)',
            self::POWER    => 'Pangkat (^)',
        };
    }

    /**
     * Return all operator values (for validation rules).
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
