<?php

namespace App\Http\Requests\Dashboard\BusinessFormula;

use App\Enums\FormulaOperator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    public function rules(): array
    {
        return [
            'result'       => ['sometimes', 'string', 'max:100', 'regex:/^[a-z_][a-z0-9_]*$/'],
            'result_label' => ['sometimes', 'string', 'max:150'],
            'field_a'      => ['sometimes', 'string', 'max:100'],
            'operator'     => ['sometimes', 'string', Rule::in(FormulaOperator::values())],
            'field_b'      => ['sometimes', 'string', 'max:100'],
            'order'        => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'result.regex' => 'The result column must be a snake_case identifier (e.g. total_harga).',
            'operator.in'  => 'Operator must be one of: ' . implode(', ', FormulaOperator::values()) . '.',
        ];
    }
}
