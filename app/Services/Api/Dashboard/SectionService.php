<?php

namespace App\Services\Api\Dashboard;

use App\Enums\TransactionType;
use App\Facades\ApiResponse;
use App\Foundations\Service;
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

        $size = request()->input('length', 10);
        $start = request()->input('start', 0);
        $orderInput = request()->input('order', []);
        $columnsInput = request()->input('columns', []);
        $order = !empty($orderInput) && isset($columnsInput[$orderInput[0]['column']]['data'])
            ? $columnsInput[$orderInput[0]['column']]['data']
            : 'id';
        $direction = !empty($orderInput) && isset($orderInput[0]['dir'])
            ? $orderInput[0]['dir']
            : 'asc';
        $searchInput = request()->input('search', []);
        $search = isset($searchInput['value']) ? $searchInput['value'] : null;

        $date_from = request()->input('date_from', null);
        $date_to = request()->input('date_to', null);
        $type = request()->input('filter_type', null);

        $query = $business->transactions()->with('detail');

        if ($date_from) {
            $query->whereDate('transaction_date', '>=', $date_from);
        }

        if ($date_to) {
            $query->whereDate('transaction_date', '<=', $date_to);
        }

        $filters = request()->except(['date_from', 'date_to', 'filter_type', 'search', 'length', 'start', 'order', 'columns', 'draw']);

        foreach ($filters as $key => $value) {
            if (!$value) {
                continue;
            }

            $query->whereHas('detail', function ($detailQuery) use ($key, $value) {
                if (is_array($value)) {
                    $detailQuery->whereJsonContains("detail->{$key}", $value);
                } else {
                    $detailQuery->whereJsonPath("detail->{$key}", '=', $value);
                }
            });
        }

        $loweredType = $type ? strtolower($type) : null;
        if ($type && in_array($loweredType, [TransactionType::INCOME->value, TransactionType::EXPENSE->value])) {
            $query->where('type', $loweredType);
        } else if ($type && TransactionType::fromLabel($loweredType)) {
            $transactionType = TransactionType::fromLabel($loweredType);
            $query->where('type', $transactionType->value);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('detail', function ($detailQuery) use ($search) {
                    $detailQuery->where('note', 'like', "%{$search}%")
                        ->orWhere('amount', 'like', "%{$search}%")
                        ->orWhereRaw("detail LIKE ?", ["%{$search}%"]);
                })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        $totalFiltered = $query->count();

        if (in_array($order, ['note', 'amount'])) {
            $query->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
                ->select('transactions.*')
                ->orderBy('transaction_details.' . $order, $direction);
        } else {
            $column = in_array($order, ['id', 'created_at', 'updated_at']) ? $order : 'id';
            $query->orderBy($column, $direction);
        }

        $transactions = $query->skip($start)
            ->take($size)
            ->get();

        $totalRecords = $business->transactions()->count();

        $data = BusinessTransactionResource::collection($transactions);

        return ApiResponse::custom([
            'data' => $data,
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $role, Business $business, array $request): mixed
    {
        $transaction = Transaction::create([
            'business_id' => $business->id,
            'type' => $request['type'] ?? TransactionType::INCOME->value,
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

        $response = new BusinessTransactionResource($transaction->load('detail', 'user'));

        return ApiResponse::success($response, 'Section created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $role, Business $business, Transaction $transaction)
    {
        if ($transaction->business_id !== $business->id) {
            return ApiResponse::error('Transaction not found in this business.', 404);
        }

        $response = new BusinessTransactionResource($transaction->load('detail', 'user'));

        return ApiResponse::success($response, 'Section retrieved successfully.', 200);
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

        $response = new BusinessTransactionResource($transaction->load('detail', 'user'));

        return ApiResponse::success($response, 'Section updated successfully.', 200);
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
