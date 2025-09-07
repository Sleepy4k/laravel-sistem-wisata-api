<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Foundations\Controller;
use App\Services\Api\Dashboard\SidebarService;
use Illuminate\Http\Request;

class SidebarController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SidebarService $service, Request $request)
    {
        return $service->invoke($request);
    }
}
