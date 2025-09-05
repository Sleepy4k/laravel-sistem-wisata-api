<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Storage\SystemLogManager;

/**
 * @method static bool debug(string $message, array $context = [])
 * @method static bool error(string $message, array $context = [])
 * @method static bool alert(string $message, array $context = [])
 * @method static bool info(string $message, array $context = [])
 * @method static bool warning(string $message, array $context = [])
 *
 * @see \Modules\Storage\SystemLogManager
 *
 * @mixins \Modules\Storage\SystemLogManager
 */
class SystemLog extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SystemLogManager::class;
    }
}
