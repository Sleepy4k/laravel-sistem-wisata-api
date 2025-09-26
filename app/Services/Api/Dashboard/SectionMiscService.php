<?php

namespace App\Services\Api\Dashboard;

use App\Foundations\Service;
use App\Http\Resources\Dashboard\BusinessFieldResource;

class SectionMiscService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function columns(string $role, $business): mixed
    {
        $business->load('fields');

        $fields = $business->fields->map(function ($field) {
            return [
                'data' => $field->name,
                'name' => $field->name,
                'title' => $field->label,
                'orderable' => true,
                'searchable' => true,
            ];
        });

        $columns = array_merge([
            [
                'data' => 'id',
                'name' => 'id',
                'title' => 'ID',
                'orderable' => true,
                'searchable' => true,
            ]
        ], $fields->toArray());

        return array_merge($columns, [
            [
                'data' => 'created_at',
                'name' => 'created_at',
                'title' => 'Created At',
                'orderable' => true,
                'searchable' => false,
            ],
            [
                'data' => 'updated_at',
                'name' => 'updated_at',
                'title' => 'Updated At',
                'orderable' => true,
                'searchable' => false,
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function fields(string $role, $business): mixed
    {
        $business->load('fields');

        return BusinessFieldResource::collection($business->fields);
    }

    /**
     * Display a listing of the resource.
     */
    public function cards(string $role, $business): mixed
    {
        $currentTime = now();
        $startOfMonth = $currentTime->copy()->startOfMonth();
        $endOfMonth = $currentTime->copy()->endOfMonth();
        $business->load('transactions');
        $transactions = $business->transactions->load('detail');

        $income = $transactions->where('type', 'income')->sum('detail.amount');
        $outcome = $transactions->where('type', 'outcome')->sum('detail.amount');
        $currentMonthTransactions = $transactions->filter(function ($transaction) use ($startOfMonth, $endOfMonth) {
            return $transaction->created_at->between($startOfMonth, $endOfMonth);
        });

        return [
            [
                'name' => 'total-transactions',
                'value' => $transactions->count(),
            ],
            [
                'name' => 'transactions-this-month',
                'value' => $currentMonthTransactions->count(),
            ],
            [
                'name' => 'total-income',
                'value' => $income,
            ],
            [
                'name' => 'total-outcome',
                'value' => $outcome,
            ],
            [
                'name' => 'net-balance',
                'value' => $income - $outcome,
            ],
        ];
    }
}
