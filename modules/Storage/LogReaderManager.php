<?php

namespace Modules\Storage;

use App\Enums\LogReaderType;
use App\Facades\Format;
use App\Facades\SystemLog;
use Illuminate\Support\Facades\File;

class LogReaderManager
{
    /**
     * Set path root for log files.
     *
     * @var string
     */
    private static string $filePath = 'logs';

    /**
     * Get file list from Laravel app log.
     *
     * @param LogReaderType $type
     * @param string $channel
     *
     * @return array
     */
    public function getFileList(LogReaderType $type = LogReaderType::SINGLE, string $channel = 'laravel'): array
    {
        try {
            $logPath = storage_path(self::$filePath);

            $pattern = match ($type) {
                LogReaderType::DAILY => "{$logPath}/{$channel}-*.log",
                default => "{$logPath}/{$channel}.log",
            };

            $files = collect(glob($pattern))
                ->filter(fn($file) => File::isFile($file))
                ->map(function ($file) {
                    return [
                        'name' => basename($file),
                        'size' => Format::formatFileSize(File::size($file)),
                        'type' => File::type($file),
                        'content' => File::mimeType($file),
                        'last_updated' => date('Y-m-d H:i:s', File::lastModified($file)),
                    ];
                })
                ->values()
                ->toArray();

            return $files;
        } catch (\Throwable $th) {
            SystemLog::error($th->getMessage());
            return [];
        }
    }

    /**
     * Read file content from Laravel app log.
     *
     * @param string $name
     *
     * @return array
     */
    public function getFileContent(string $name): array
    {
        try {
            $logPath = storage_path(self::$filePath . "/{$name}.log");

            if (!File::exists($logPath)) {
                throw new \Exception('File not found in our storage, please double check it.');
            }

            $content = File::get($logPath);
            $pattern = '/^\[(?<date>.+?)\]\s(?<env>\w+)\.(?<type>\w+):(?<message>.*)$/m';

            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

            return array_map(function ($match) {
                return [
                    'timestamp' => $match['date'],
                    'env' => $match['env'],
                    'type' => $match['type'],
                    'message' => trim($match['message']),
                ];
            }, $matches);
        } catch (\Throwable $th) {
            SystemLog::error($th->getMessage());
            return [];
        }
    }

    /**
     * Read all file content from Laravel app log.
     *
     * @param LogReaderType $type
     * @param string $channel
     * @param string|null $date
     *
     * @return array
     */
    public function getAllFileContent(LogReaderType $type = LogReaderType::SINGLE, string $channel = 'laravel', ?string $date = null): array
    {
        try {
            $logFile = match ($type) {
                LogReaderType::DAILY => storage_path(self::$filePath . "/{$channel}-" . ($date ?? now()->format('Y-m-d')) . '.log'),
                default => storage_path(self::$filePath . "/{$channel}.log"),
            };

            if (!File::exists($logFile)) {
                throw new \Exception('File not found in our storage, please double check it.');
            }

            $content = File::get($logFile);
            $pattern = '/^\[(?<date>.+?)\]\s(?<env>\w+)\.(?<type>\w+):(?<message>.*)$/m';

            preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

            return array_map(function ($match) {
                return [
                    'timestamp' => $match['date'],
                    'env' => $match['env'],
                    'type' => $match['type'],
                    'message' => trim($match['message']),
                ];
            }, $matches);
        } catch (\Throwable $th) {
            SystemLog::error($th->getMessage());
            return [];
        }
    }
}
