<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Events\QueryExecuted;

class QueryExecutedListener
{
    /**
     * Handle the event.
     */
    public function handle(QueryExecuted $event): void
    {
        if (app()->isProduction()) return;

        $params = [
            'bindings' => $event->bindings,
            'time' => $event->time,
        ];

        Log::channel('query')->info('query executed: '.$event->sql, $params);
    }
}
