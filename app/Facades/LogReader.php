<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Storage\LogReaderManager;

/**
 * @method static array getFileList(LogReaderType $type, string $channel)
 * @method static array getFileContent(string $name)
 * @method static string getAllFileContent(LogReaderType $type, string $channel, ?string $date)
 *
 * @see \Modules\Storage\LogReaderManager
 *
 * @mixins \Modules\Storage\LogReaderManager
 */
class LogReader extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return LogReaderManager::class;
    }
}
