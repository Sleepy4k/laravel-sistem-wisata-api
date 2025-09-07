<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBasicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $role = $this->whenLoaded('roles') ? $this->roles->pluck('name')->first() : null;
        $permissions = $this->whenLoaded('permissions') ? $this->permissions->pluck('name') : [];

        if ($role) {
            $rolePermissions = $this->roles->first()->permissions?->pluck('name') ?? [];
            $permissions = $permissions->merge($rolePermissions)->unique()->values();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'role' => $role,
            'permissions' => $permissions,
        ];
    }
}
