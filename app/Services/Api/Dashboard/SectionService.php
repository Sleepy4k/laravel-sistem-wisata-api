<?php

namespace App\Services\Api\Dashboard;

use App\Enums\TransactionType;
use App\Facades\ApiResponse;
use App\Foundations\Service;
use App\Http\Resources\Dashboard\BusinessResource;
use App\Http\Resources\Dashboard\BusinessTransactionResource;
use App\Models\Business;
use App\Models\Transaction;
use App\Models\TransactionDetail;

class SectionService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $role, Business $business): mixed
    {
        $business->load('fields', 'transactions', 'transactions.detail', 'transactions.user');

        return ApiResponse::success(new BusinessResource($business), 'Sections retrieved successfully.', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $role, Business $business, array $request): mixed
    {
        $transaction = Transaction::create([
            'business_id' => $business->id,
            'type' => TransactionType::INCOME->value,
            'transaction_date' => date('Y-m-d'),
            'user_id' => auth('api')->id(),
        ]);

        $amount = $request['amount'] ?? 0;
        $note = $request['note'] ?? null;
        unset($request['amount'], $request['note']);

        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'amount' => $amount,
            'note' => $note,
            'detail' => json_encode($request),
        ]);

        return ApiResponse::success(new BusinessTransactionResource($transaction->load('detail', 'user')), 'Section created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $role, Business $business, Transaction $transaction)
    {
        if ($transaction->business_id !== $business->id) {
            return ApiResponse::error('Transaction not found in this business.', 404);
        }

        return ApiResponse::success(new BusinessTransactionResource($transaction->load('detail', 'user')), 'Section retrieved successfully.', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $role, Business $business, Transaction $transaction, array $request): mixed
    {
        if ($transaction->business_id !== $business->id) {
            return ApiResponse::error('Transaction not found in this business.', 404);
        }

        $amount = $request['amount'] ?? 0;
        $note = $request['note'] ?? null;
        unset($request['amount'], $request['note']);

        $detail = $transaction->detail;
        if ($detail) {
            $detail->amount = $amount;
            $detail->note = $note;
            $detail->detail = json_encode($request);
            $detail->save();
        } else {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'amount' => $amount,
                'note' => $note,
                'detail' => json_encode($request),
            ]);
        }

        return ApiResponse::success(new BusinessTransactionResource($transaction->load('detail', 'user')), 'Section updated successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $role, Business $business, Transaction $transaction): mixed
    {
        if ($transaction->business_id !== $business->id) {
            return ApiResponse::error('Transaction not found in this business.', 404);
        }

        $transaction->delete();

        return ApiResponse::success(null, 'Section deleted successfully.', 200);
    }
}
