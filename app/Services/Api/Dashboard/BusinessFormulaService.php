<?php

namespace App\Services\Api\Dashboard;

use App\Enums\FormulaOperator;
use App\Facades\ApiResponse;
use App\Foundations\Service;
use App\Http\Resources\Dashboard\BusinessFormulaResource;
use App\Models\Business;
use App\Models\BusinessFormula;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
     * Update an existing formula.
     */
    public function update(string $role, Business $business, array $data): mixed
    {
        try {
            return DB::transaction(function () use ($business, $data) {
                $items = collect($data['formulas'] ?? [])->values();

                $existingFormulas = $business->formulas()->get()->keyBy('id');
                $incomingIds = $items->pluck('id')->filter()->map(fn($id) => (int) $id)->values();

                if ($incomingIds->isNotEmpty()) {
                    $business->formulas()->whereNotIn('id', $incomingIds)->delete();
                } else {
                    $business->formulas()->delete();
                }

                foreach ($items as $index => $item) {
                    $this->validateFields($business, $item['field_a'], $item['field_b']);

                    $payload = [
                        'business_id'  => $business->id,
                        'result'       => $item['result'],
                        'result_label' => $item['result_label'],
                        'field_a'      => $item['field_a'],
                        'operator'     => $item['operator'],
                        'field_b'      => $item['field_b'],
                        'order'        => $item['order'] ?? ($index + 1),
                    ];

                    if (!empty($item['id'])) {
                        $formulaToUpdate = $existingFormulas->get((int) $item['id']);

                        if (!$formulaToUpdate) {
                            throw ValidationException::withMessages([
                                'id' => ["Formula id '{$item['id']}' does not exist in business '{$business->slug}'."],
                            ]);
                        }

                        $formulaToUpdate->update($payload);
                    } else {
                        BusinessFormula::create($payload);
                    }
                }

                $formulas = $business->formulas()->orderBy('order')->get();

                return ApiResponse::success(
                    BusinessFormulaResource::collection($formulas),
                    'Formulas synced successfully.'
                );
            });
        } catch (\Throwable $th) {
            return ApiResponse::error("Something went wrong", 500, 'Failed to update formulas: ' . $th->getMessage());
        }
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
            throw ValidationException::withMessages([
                'field_a' => ["Field '{$fieldA}' does not exist in business '{$business->slug}'."],
            ]);
        }

        if (!in_array($fieldB, $validFieldNames, true)) {
            throw ValidationException::withMessages([
                'field_b' => ["Field '{$fieldB}' does not exist in business '{$business->slug}'."],
            ]);
        }
    }
}
