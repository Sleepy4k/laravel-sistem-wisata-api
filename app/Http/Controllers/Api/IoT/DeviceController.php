<?php

namespace App\Http\Controllers\Api\IoT;

use App\Facades\ApiResponse;
use App\Foundations\Controller;
use App\Http\Requests\IoT\Device\StoreRequest;
use App\Policies\DevicePolicy;
use App\Services\Api\IoT\DeviceService;
use App\Traits\Authorizable;

class DeviceController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private DeviceService $service,
        private $policy = DevicePolicy::class,
        private $abilities = [
            'index' => 'viewAny',
            'store' => 'store',
        ]
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::success($this->service->index(), 'Successfully retrieved device data.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if ($this->service->store($request->validated())) {
            return ApiResponse::success([], 'Successfully stored device data.', 200);
        }

        return ApiResponse::error('Failed to store device data.', 422);
    }
}
