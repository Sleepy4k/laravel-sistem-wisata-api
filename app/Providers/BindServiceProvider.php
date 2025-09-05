<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider
{
    /**
     * The model observers.
     *
     * @var array
     */
    protected array $modelObservers = [];

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
        if (app()->runningInConsole() || !empty($this->modelObservers)) {
            return;
        }

        $modelsPath = app_path('Models');
        $observersPath = app_path('Observers');

        foreach (glob("{$modelsPath}/*.php") as $modelFile) {
            $modelName = pathinfo($modelFile, PATHINFO_FILENAME);
            $observerFile = "{$observersPath}/{$modelName}Observer.php";

            if (!file_exists($observerFile)) continue;

            $modelClass = "App\Models\\{$modelName}";
            $observerClass = "App\Observers\\{$modelName}Observer";

            $this->modelObservers[$modelClass] = $observerClass;
        }

        foreach ($this->modelObservers as $model => $observer) {
            if (class_exists($model) && class_exists($observer)) {
                $model::observe($observer);
            }
        }
    }
}
