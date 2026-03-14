<?php

namespace App\Http\Requests\Dashboard\BusinessFormula;

use App\Enums\FormulaOperator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'formulas'               => ['nullable', 'array'],
            'formulas.*.result'       => ['sometimes', 'string', 'max:100', 'regex:/^[a-z_][a-z0-9_]*$/'],
            'formulas.*.result_label' => ['sometimes', 'string', 'max:150'],
            'formulas.*.field_a'      => ['sometimes', 'string', 'max:100'],
            'formulas.*.operator'     => ['sometimes', 'string', Rule::in(FormulaOperator::values())],
            'formulas.*.field_b'      => ['sometimes', 'string', 'max:100'],
            'formulas.*.order'        => ['sometimes', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom error messages for validation failures.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'result.regex' => 'The result column must be a snake_case identifier (e.g. total_harga).',
            'operator.in'  => 'Operator must be one of: ' . implode(', ', FormulaOperator::values()) . '.',
        ];
    }
}
