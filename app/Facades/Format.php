<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Parse\FormatManager;

/**
 * @method static string formatFileSize(int|float $bytes, int $precision)
 * @method static string formatNumber(int|float $number, int $precision)
 * @method static string formatDate(string $date, string $format)
 *
 * @see \Modules\Parse\FormatManager
 *
 * @mixins \Modules\Parse\FormatManager
 */
class Format extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return FormatManager::class;
    }
}
