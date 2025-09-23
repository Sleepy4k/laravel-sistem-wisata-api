<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Facades\ApiResponse;
use App\Foundations\Controller;
use App\Http\Requests\Profile\UpdateRequest;
use App\Services\Api\Dashboard\ProfileService;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private ProfileService $service,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApiResponse::success($this->service->index(), 'Successfully retrieved detail profile data.', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        return ApiResponse::success($this->service->show(), 'Successfully retrieved profile data.', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        if ($this->service->update($request->validated())) {
            return ApiResponse::success([], 'Successfully updated profile data.', 200);
        }

        return ApiResponse::error('Failed to update profile data.', 500);
    }
}
