<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessTransactionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $detail = json_decode($this->detail, true);

        return array_merge([
            'amount' => $this->amount,
            'note' => $this->note,
        ], is_array($detail) ? $detail : []);
    }
}
