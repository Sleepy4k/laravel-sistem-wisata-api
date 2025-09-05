<?php

namespace Modules\Parse;

use Illuminate\Support\Carbon;

class FormatManager
{
    /**
     * The size units.
     *
     * @var array
     */
    protected static array $sizeUnits = ['B', 'KB', 'MB', 'GB', 'TB'];

    /**
     * Format the file size.
     *
     * @param int|float $bytes
     * @param int $precision
     *
     * @return string
     */
    public function formatFileSize(int|float $bytes, int $precision = 2): string
    {
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count(static::$sizeUnits) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.static::$sizeUnits[$pow];
    }

    /**
     * Format the number.
     *
     * @param int|float $number
     * @param int $precision
     *
     * @return string
     */
    public function formatNumber(int|float $number, int $precision = 2): string
    {
        return number_format($number, $precision);
    }

    /**
     * Format the date.
     *
     * @param string $date
     * @param string $format
     *
     * @return string
     */
    public function formatDate(string $date, string $format = 'd-m-Y'): string
    {
        return Carbon::parse($date)->format($format);
    }
}
