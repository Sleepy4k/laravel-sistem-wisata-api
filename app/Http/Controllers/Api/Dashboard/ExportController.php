<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Foundations\Controller;
use App\Models\Business;
use App\Policies\ExportPolicy;
use App\Services\Api\Dashboard\ExportService;
use App\Traits\Authorizable;

class ExportController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ExportService $service,
        private $policy = ExportPolicy::class,
        private $abilities = [
            'excel' => 'excel',
            'pdf'   => 'pdf',
            'print' => 'print',
        ]
    ) {}

    /**
     * Download data as an Excel file.
     */
    public function excel(string $role, Business $business)
    {
        return $this->service->excel($role, $business);
    }

    /**
     * Download data as a PDF file.
     */
    public function pdf(string $role, Business $business)
    {
        return $this->service->pdf($role, $business);
    }

    /**
     * Return an HTML print view.
     */
    public function print(string $role, Business $business)
    {
        return $this->service->print($role, $business);
    }
}
