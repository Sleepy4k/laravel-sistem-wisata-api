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
        $isDetailLoaded = $this->whenLoaded('detail');
        $detail = $isDetailLoaded ? json_decode($this->detail->detail, true) : null;

        $result = [
            'id' => $this->id,
            'type' => $this->type,
            'transaction_date' => $this->transaction_date,
            'user' => new UserBasicResource($this->whenLoaded('user')),
            'created_at' => $this->created_at->format('d-m-Y H:i:s'),
            'updated_at' => $this->updated_at->format('d-m-Y H:i:s'),
        ];

        if ($isDetailLoaded) {
            $result['amount'] = $this->detail->amount;
            $result['note'] = $this->detail->note;
        }

        if ($detail && is_array($detail)) {
            foreach ($detail as $key => $value) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
