<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Foundations\Controller;
use App\Http\Requests\Dashboard\Business\StoreRequest;
use App\Http\Requests\Dashboard\Business\UpdateRequest;
use App\Models\Business;
use App\Policies\ManageBusinessPolicy;
use App\Services\Api\Dashboard\BusinessService;
use App\Traits\Authorizable;

class BusinessController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private BusinessService $service,
        private $policy = ManageBusinessPolicy::class,
        private $abilities = [
            'store' => 'store',
            'update' => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $role, StoreRequest $request)
    {
        return $this->service->store($role, $request->validated());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $role, Business $business, UpdateRequest $request)
    {
        return $this->service->update($role, $business, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $role, Business $business)
    {
        return $this->service->destroy($role, $business);
    }
}
