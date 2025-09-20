<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SidebarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $metaRoute = $this->whenLoaded('meta')?->route ?? null;

        return [
            'name'          => $this->resource->name,
            'slug'          => Str::slug($this->resource->name),
            'order'         => $this->resource->order,
            'is_spacer'     => $this->resource->is_spacer,
            'is_datatable'  => $metaRoute && $metaRoute !== 'dashboard.role.business.store' && !$this->resource->is_spacer,
            'meta'          => new SidebarMetaResource($this->whenLoaded('meta')),
        ];
    }
}
