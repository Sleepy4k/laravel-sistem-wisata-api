<?php

namespace App\Http\Requests\Dashboard\Section;

use App\Models\BusinessField;
use Illuminate\Foundation\Http\FormRequest;

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
        $fields = BusinessField::select('name', 'type', 'options', 'validation_rules')->where('business_id', $this->route('business')->id)->get();
        $rules = [];

        foreach ($fields as $field) {
            $rules[$field->name] = $field->validation_rules ?? [];

            switch ($field->type) {
            case 'select':
                $options = is_array($field->options) ? $field->options : [];
                $rules[$field->name] .= '|in:' . implode(',', $options);
                break;
            default:
                break;
            }
        }

        return $rules;
    }
}
