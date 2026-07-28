<?php

namespace App\Http\Controllers\Api\IoT;

use App\Models\IoT;
use App\Facades\ApiResponse;
use App\Foundations\Controller;
use App\Http\Requests\IoT\Device\StoreRequest;
use App\Http\Requests\IoT\Device\UpdateRequest;
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
            'show' => 'view',
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
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
     * Display the specified device.
     */
    public function show(IoT $device)
    {
        return ApiResponse::success($this->service->show($device), 'Successfully retrieved device data.', 200);
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

    /**
     * Update the specified device.
     */
    public function update(IoT $device, UpdateRequest $request)
    {
        return ApiResponse::success($this->service->update($device, $request->validated()), 'Successfully updated device data.', 200);
    }

    /**
     * Remove the specified device.
     */
    public function destroy(IoT $device)
    {
        return ApiResponse::success($this->service->destroy($device), 'Successfully deleted device data.', 200);
    }
}
