<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'is_active' => $this->is_active,
            'fields' => BusinessFieldResource::collection($this->whenLoaded('fields')),
            'transactions' => BusinessTransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
