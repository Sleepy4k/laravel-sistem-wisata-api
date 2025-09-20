<?php

namespace App\Http\Requests\Dashboard\Business;

use App\Enums\FieldInputType;
use App\Models\Business;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:30', Rule::unique(Business::class, 'name')],
            'icon' => ['sometimes', 'string', 'max:15'],
            'is_active' => ['sometimes', 'boolean'],
            'fields' => ['required', 'array', 'min:2'],
            'fields.*.name' => ['required', 'string', 'max:100'],
            'fields.*.label' => ['required', 'string', 'max:150'],
            'fields.*.type' => ['required', 'string', Rule::in(FieldInputType::toArray())],
            'fields.*.options' => ['sometimes', 'array'],
            'fields.*.validation_rules' => ['sometimes', 'array'],
            'fields.*.placeholder' => ['sometimes', 'string', 'max:200'],
        ];
    }
}
