<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Foundations\Controller;
use App\Http\Requests\Dashboard\BusinessFormula\StoreRequest;
use App\Http\Requests\Dashboard\BusinessFormula\UpdateRequest;
use App\Models\Business;
use App\Models\BusinessFormula;
use App\Services\Api\Dashboard\BusinessFormulaService;
use App\Traits\Authorizable;

class BusinessFormulaController extends Controller
{
    use Authorizable;

    public function __construct(
        private BusinessFormulaService $service,
        private $policy = BusinessFormula::class,
        private $abilities = [
            'index'   => 'viewAny',
            'store'   => 'store',
            'update'  => 'update',
            'destroy' => 'delete',
        ]
    ) {}

    /**
     * Display all formulas for the given business.
     */
    public function index(string $role, Business $business)
    {
        return $this->service->index($role, $business);
    }

    /**
     * Store a new formula.
     */
    public function store(string $role, Business $business, StoreRequest $request)
    {
        return $this->service->store($role, $business, $request->validated());
    }

    /**
     * Update an existing formula.
     */
    public function update(string $role, Business $business, BusinessFormula $formula, UpdateRequest $request)
    {
        return $this->service->update($role, $business, $formula, $request->validated());
    }

    /**
     * Delete a formula.
     */
    public function destroy(string $role, Business $business, BusinessFormula $formula)
    {
        return $this->service->destroy($role, $business, $formula);
    }
}
