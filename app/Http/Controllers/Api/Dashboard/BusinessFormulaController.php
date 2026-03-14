<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Foundations\Controller;
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
            'update'  => 'update',
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
     * Update an existing formula.
     */
    public function update(string $role, Business $business, UpdateRequest $request)
    {
        return $this->service->update($role, $business, $request->validated());
    }
}
