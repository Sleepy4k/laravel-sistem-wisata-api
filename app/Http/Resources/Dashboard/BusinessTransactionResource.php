<?php

namespace App\Http\Resources\Dashboard;

use App\Http\Resources\Profile\UserBasicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'transaction_date' => $this->transaction_date,
            'detail' => new BusinessTransactionDetailResource($this->whenLoaded('detail')),
            'user' => new UserBasicResource($this->whenLoaded('user'))
        ];
    }
}
