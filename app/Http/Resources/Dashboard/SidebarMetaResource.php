<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SidebarMetaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'route'      => !empty($this->resource->route) ? route($this->resource->route, $this->resource->parameters) : null,
            'permissions'=> $this->resource->permissions,
            'icon'       => $this->resource->icon,
        ];
    }
}
