<?php

namespace App\Services\Api\Dashboard;

use App\Enums\FormulaOperator;
use App\Facades\ApiResponse;
use App\Foundations\Service;
use App\Http\Resources\Dashboard\BusinessFormulaResource;
use App\Models\Business;
use App\Models\BusinessFormula;

class BusinessFormulaService extends Service
{
    /**
     * Return all formulas for a business, together with a list of
     * supported operators (useful for building a formula editor UI).
     */
    public function index(string $role, Business $business): mixed
    {
        $business->loadMissing('formulas', 'fields');

        $operators = collect(FormulaOperator::cases())->map(fn($op) => [
            'value' => $op->value,
            'label' => $op->label(),
        ])->values();

        return ApiResponse::success([
            'formulas'  => BusinessFormulaResource::collection($business->formulas),
            'operators' => $operators,
            'fields'    => $business->fields->map(fn($f) => [
                'name'  => $f->name,
                'label' => $f->label,
            ])->values(),
        ], 'Formulas retrieved successfully.');
    }

    /**
     * Create a new formula for the business.
     */
    public function store(string $role, Business $business, array $data): mixed
    {
        $nextOrder = $business->formulas()->max('order') + 1;

        $created = collect($data['formulas'])->map(function (array $item, int $i) use ($business, &$nextOrder) {
            $this->validateFields($business, $item['field_a'], $item['field_b']);

            $formula = BusinessFormula::create([
                'business_id'  => $business->id,
                'result'       => $item['result'],
                'result_label' => $item['result_label'],
                'field_a'      => $item['field_a'],
                'operator'     => $item['operator'],
                'field_b'      => $item['field_b'],
                'order'        => $item['order'] ?? $nextOrder++,
            ]);

            return new BusinessFormulaResource($formula);
        })->values();

        return ApiResponse::success($created, 'Formula(s) created successfully.', 201);
    }

    /**
     * Update an existing formula.
     */
    public function update(string $role, Business $business, BusinessFormula $formula, array $data): mixed
    {
        if (isset($data['field_a']) || isset($data['field_b'])) {
            $this->validateFields(
                $business,
                $data['field_a'] ?? $formula->field_a,
                $data['field_b'] ?? $formula->field_b,
            );
        }

        $formula->fill(array_filter([
            'result'       => $data['result']       ?? null,
            'result_label' => $data['result_label'] ?? null,
            'field_a'      => $data['field_a']      ?? null,
            'operator'     => $data['operator']     ?? null,
            'field_b'      => $data['field_b']      ?? null,
            'order'        => $data['order']        ?? null,
        ], fn($v) => !is_null($v)));

        $formula->save();

        return ApiResponse::success(new BusinessFormulaResource($formula), 'Formula updated successfully.');
    }

    /**
     * Delete a formula.
     */
    public function destroy(string $role, Business $business, BusinessFormula $formula): mixed
    {
        $formula->delete();

        return ApiResponse::success(null, 'Formula deleted successfully.');
    }

    /**
     * Validate that field_a and field_b reference real fields on this business.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateFields(Business $business, string $fieldA, string $fieldB): void
    {
        $business->loadMissing('fields');
        $validFieldNames = $business->fields->pluck('name')->toArray();

        if (!in_array($fieldA, $validFieldNames, true)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'field_a' => ["Field '{$fieldA}' does not exist in business '{$business->slug}'."],
            ]);
        }

        if (!in_array($fieldB, $validFieldNames, true)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'field_b' => ["Field '{$fieldB}' does not exist in business '{$business->slug}'."],
            ]);
        }
    }
}
