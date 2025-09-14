<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Facades\ApiResponse;
use App\Foundations\Controller;
use App\Models\Business;
use App\Policies\SectionMiscPolicy;
use App\Services\Api\Dashboard\SectionMiscService;
use App\Traits\Authorizable;

class SectionMiscController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SectionMiscService $service,
        private $policy = SectionMiscPolicy::class,
        private $abilities = [
            'export' => 'export',
            'fields' => 'viewFields',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function columns(string $role, Business $business)
    {
        $data = $this->service->columns($role, $business);

        return ApiResponse::success($data, 'Columns retrieved successfully.', 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function fields(string $role, Business $business)
    {
        $data = $this->service->fields($role, $business);

        return ApiResponse::success($data, 'Fields retrieved successfully.', 200);
    }
}
