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
    public function show(IoT $iot)
    {
        return $this->service->show($iot);
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
    public function update(IoT $iot, UpdateRequest $request)
    {
        return $this->service->update($iot, $request->validated());
    }

    /**
     * Remove the specified device.
     */
    public function destroy(IoT $iot)
    {
        return $this->service->destroy($iot);
    }
}
