<?php

namespace App\Http\Controllers\Web;

use App\Foundations\Controller;
use App\Services\Web\ServeService;

class ServeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ServeService $service, string $path)
    {
        return $service->invoke($path);
    }
}
