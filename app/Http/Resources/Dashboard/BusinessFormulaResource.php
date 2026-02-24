<?php

namespace App\Http\Resources\Dashboard;

use App\Enums\FormulaOperator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessFormulaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $op = FormulaOperator::tryFrom($this->operator ?? '');

        return [
            'id'             => $this->id,
            'result'         => $this->result,
            'result_label'   => $this->result_label,
            'field_a'        => $this->field_a,
            'operator'       => $this->operator,
            'operator_label' => $op?->label(),
            'field_b'        => $this->field_b,
            'formula_string' => "{$this->field_a} {$this->operator} {$this->field_b}",
            'order'          => $this->order,
            'created_at'     => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'     => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
