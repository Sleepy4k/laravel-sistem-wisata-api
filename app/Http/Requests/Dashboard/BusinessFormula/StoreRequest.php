<?php

namespace App\Http\Requests\Dashboard\BusinessFormula;

use App\Enums\FormulaOperator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'formulas'               => ['required', 'array', 'min:1'],
            'formulas.*.result'       => ['required', 'string', 'max:100', 'regex:/^[a-z_][a-z0-9_]*$/'],
            'formulas.*.result_label' => ['required', 'string', 'max:150'],
            'formulas.*.field_a'      => ['required', 'string', 'max:100'],
            'formulas.*.operator'     => ['required', 'string', Rule::in(FormulaOperator::values())],
            'formulas.*.field_b'      => ['required', 'string', 'max:100'],
            'formulas.*.order'        => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'formulas.*.result.regex' => 'Each result column must be a snake_case identifier (e.g. total_harga).',
            'formulas.*.operator.in'  => 'Operator must be one of: ' . implode(', ', FormulaOperator::values()) . '.',
        ];
    }
}
