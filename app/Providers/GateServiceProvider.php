<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class GateServiceProvider extends ServiceProvider
{
    /**
     * Scan the policies directory and return an array of policy files.
     *
     * @param string $dir
     * @return array
     */
    protected function scanPolicies(string $dir): array
    {
        $files = [];
        $directories = [];

        if (!is_dir($dir)) return $files;

        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') continue;

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
            $directories[] = $path;
            } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $files[] = $path;
            }
        }

        // Sort directories to ensure consistent order
        sort($directories);

        // Process each directory completely before moving to the next
        foreach ($directories as $directory) {
            $files = array_merge($files, $this->scanPolicies($directory));
        }

        return $files;
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        // Only when server environment is production
        if (app()->isProduction()) {
            Gate::before(function ($user, $ability) {
                $highestRole = config('rbac.role.highest');
                return $user->hasRole($highestRole) ?: null;
            });
        }

        $policyPath = app_path('Policies');
        $modelPath = app_path('Models');

        $policyFiles = $this->scanPolicies($policyPath);

        foreach ($policyFiles as $file) {
            // Get relative path and class name
            $relativePath = str_replace([$policyPath . DIRECTORY_SEPARATOR, '.php'], '', $file);
            if (strpos($relativePath, 'Install') === 0) continue;

            $class = 'App\\Policies\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

            // Get policy name (without "Policy" suffix)
            $policyName = basename($file, '.php');
            $modelName = preg_replace('/Policy$/', '', $policyName);

            // Check if model exists
            $modelFile = $modelPath . DIRECTORY_SEPARATOR . $modelName . '.php';

            if (file_exists($modelFile)) {
                $modelClass = 'App\\Models\\' . $modelName;
                Gate::policy($modelClass, $class);
            } else {
                Gate::policy($class, $class);
            }
        }
    }
}
