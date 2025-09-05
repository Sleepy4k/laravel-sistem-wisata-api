<?php

namespace App\Services\Web;

use App\Facades\ApiResponse;
use App\Foundations\Service;

class ServeService extends Service
{
    /**
     * Blocked file names.
     */
    protected static array $blockedFiles = [
        '.gitignore', '.env', '.htaccess', 'composer.json', 'composer.lock',
    ];

    /**
     * Blocked file extensions.
     */
    protected static array $blockedExtensions = [
        'php', 'env', 'json', 'lock', 'htaccess', 'git', 'md', 'yml', 'yaml',
    ];

    /**
     * Handle the incoming request.
     */
    public function invoke(string $path): mixed
    {
        $type = config('filesystems.default') == 'local' ? 'private' : 'public';
        $safePath = str_replace(['../', '..\\'], '', $path);
        $fullPath = storage_path("app/$type/$safePath");

        $realBase = realpath(storage_path("app/$type"));
        $realFile = realpath($fullPath);

        $basename = strtolower(basename($realFile));
        $extension = strtolower(pathinfo($realFile, PATHINFO_EXTENSION));

        if (in_array($basename, self::$blockedFiles) || in_array($extension, self::$blockedExtensions)) {
            return ApiResponse::error('Forbidden: Access to this file is not allowed', 403);
        }

        if (!$realFile || strpos($realFile, $realBase) !== 0) {
            return ApiResponse::error('Forbidden: Invalid file path', 403);
        }

        if (!is_file($realFile) || !is_readable($realFile)) {
            return ApiResponse::error('File not found or not accessible', 404);
        }

        return response()->file($realFile, [
            'Content-Type' => mime_content_type($realFile),
            'Content-Disposition' => 'inline; filename="' . basename($realFile) . '"',
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    }
}
