<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Events\QueryExecuted;

class QueryExecutedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        // Disable the listener in production
        if (app()->isProduction()) return;
    }

    /**
     * Handle the event.
     */
    public function handle(QueryExecuted $event): void
    {
        $params = [
            'bindings' => $event->bindings,
            'time' => $event->time,
        ];

        Log::channel('query')->info('query executed: '.$event->sql, $params);
    }
}
