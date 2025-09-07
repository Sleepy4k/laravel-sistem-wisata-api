<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SidebarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'       => $this->resource->name,
            'order'      => $this->resource->order,
            'is_spacer'  => $this->resource->is_spacer,
            'meta'       => new SidebarMetaResource($this->whenLoaded('meta')),
        ];
    }
}
