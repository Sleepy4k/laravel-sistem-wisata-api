<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Foundations\Controller;
use App\Services\Api\Dashboard\SummaryService;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SummaryService $service, Request $request)
    {
        return $service->invoke($request);
    }
}
