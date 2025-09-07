<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Foundations\Controller;
use App\Http\Requests\Dashboard\Section\StoreRequest;
use App\Http\Requests\Dashboard\Section\UpdateRequest;
use App\Models\Business;
use App\Models\Transaction;
use App\Services\Api\Dashboard\SectionService;

class SectionController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SectionService $service,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(string $role, Business $business)
    {
        return $this->service->index($role, $business);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $role, Business $business, StoreRequest $request)
    {
        return $this->service->store($role, $business, $request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $role, Business $business, Transaction $transaction)
    {
        return $this->service->show($role, $business, $transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $role, Business $business, Transaction $transaction, UpdateRequest $request)
    {
        return $this->service->update($role, $business, $transaction, $request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $role, Business $business, Transaction $transaction)
    {
        return $this->service->destroy($role, $business, $transaction);
    }
}
