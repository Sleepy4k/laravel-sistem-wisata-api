<?php

namespace App\Http\Controllers\Api\Admin;

use App\Foundations\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
use App\Policies\UserManagementPolicy;
use App\Services\Api\Admin\UserManagementService;
use App\Traits\Authorizable;

class UserManagementController extends Controller
{
    use Authorizable;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private UserManagementService $service,
        private $policy = UserManagementPolicy::class,
        private $abilities = [
            'index'   => 'viewAny',
            'show'    => 'view',
            'store'   => 'store',
            'update'  => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display a paginated listing of users.
     */
    public function index()
    {
        return $this->service->index();
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return $this->service->show($user);
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreRequest $request)
    {
        return $this->service->store($request->validated());
    }

    /**
     * Update the specified user.
     */
    public function update(User $user, UpdateRequest $request)
    {
        return $this->service->update($user, $request->validated());
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        return $this->service->destroy($user);
    }
}
