<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order' => $this->order,
            'name' => $this->name,
            'label' => $this->label,
            'type' => $this->type,
            'placeholder' => $this->placeholder,
            'options' => $this->when($this->type === 'select', fn () => $this->options),
            'is_required' => $this->when($this->validation_rules !== null, fn () => in_array('required', $this->validation_rules)),
        ];
    }
}
