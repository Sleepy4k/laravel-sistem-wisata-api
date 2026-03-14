<?php

namespace App\Services\Api\Dashboard;

use App\Facades\ApiResponse;
use App\Foundations\Service;
use App\Models\Business;
use App\Models\BusinessField;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Sidebar;
use App\Models\SidebarMeta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusinessService extends Service
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(string $role, array $request): mixed
    {
        try {
            return DB::transaction(function () use ($role, $request) {
                $business = Business::create([
                    'name' => $request['name'],
                    'slug' => Str::slug($request['name']),
                    'is_active' => $request['is_active'] ?? true,
                ]);

                $this->syncFields($business, $request['fields'] ?? []);

                $permissions = collect(config('rbac.list.crud_permissions'))
                    ->map(fn ($ability) => Permission::firstOrCreate([
                        'name' => "{$role}.{$business->slug}.{$ability}",
                        'guard_name' => 'api',
                    ])->id)
                    ->all();

                Role::where('name', '!=', 'pemdes')
                    ->get()
                    ->each(fn ($roleModel) => $roleModel->permissions()->syncWithoutDetaching($permissions));

                $sidebarMeta = SidebarMeta::firstOrCreate([
                    'icon' => $request['icon'] ?? 'briefcase',
                    'route' => 'dashboard.role.section.index',
                    'permissions' => ["{$role}.{$business->slug}.viewAny"],
                    'parameters' => ['role' => $role, 'business' => $business->slug],
                ]);

                $insertOrder = Sidebar::select('sidebars.order')
                    ->join('sidebar_metas', 'sidebars.sidebar_meta_id', '=', 'sidebar_metas.id')
                    ->whereJsonContains('sidebar_metas.parameters->role', $role)
                    ->max('sidebars.order') ?? 0;

                Sidebar::where('order', '>=', $insertOrder)->increment('order');

                Sidebar::create([
                    'name' => $business->name,
                    'order' => $insertOrder,
                    'is_spacer' => false,
                    'sidebar_meta_id' => $sidebarMeta->id,
                ]);

                return ApiResponse::success($business, 'Business created successfully', 201);
            });
        } catch (\Throwable $th) {
            return ApiResponse::error("Something went wrong", 500, 'Failed to create business: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $role, Business $business): mixed
    {
        $name = $business->name;
        $sidebar = Sidebar::query()->select('id', 'name', 'sidebar_meta_id')->with('meta:id,icon')->where('name', $name)->first();

        return ApiResponse::success([
            'name' => $name,
            'icon' => $sidebar->meta->icon
        ], "Business fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $role, Business $business, array $request): mixed
    {
        try {
            return DB::transaction(function () use ($role, $business, $request) {
                $oldSlug = $business->slug;

                $business->update([
                    'name' => $request['name'],
                    'slug' => Str::slug($request['name']),
                    'is_active' => $request['is_active'] ?? true,
                ]);

                $this->syncFields($business, $request['fields'] ?? []);

                if ($business->wasChanged('slug')) {
                    $this->updateSlugReferences($role, $oldSlug, $business->slug, $business->name);
                }

                return ApiResponse::success($business, 'Business updated successfully');
            });
        } catch (\Throwable $th) {
            return ApiResponse::error("Something went wrong", 500, 'Failed to update business: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $role, Business $business): mixed
    {
        try {
            return DB::transaction(function () use ($role, $business) {
                $oldSlug = $business->slug;

                Sidebar::query()->where('name', $business->name)->delete();
                Permission::query()->where('name', 'like', "{$role}.{$oldSlug}.%")->delete();

                return ApiResponse::success($business, 'Business updated successfully');
            });
        } catch (\Throwable $th) {
            return ApiResponse::error("Something went wrong", 500, 'Failed to update business: ' . $th->getMessage());
        }
    }

    /**
     * Sync business fields: create new ones, update existing, delete removed.
     */
    private function syncFields(Business $business, array $fields): void
    {
        $keepIds = collect($fields)->pluck('id')->filter()->values()->all();

        empty($keepIds)
            ? $business->fields()->delete()
            : $business->fields()->whereNotIn('id', $keepIds)->delete();

        if (empty($fields)) {
            return;
        }

        // Preload existing fields in one query to avoid N+1
        $existingFields = BusinessField::whereIn('id', $keepIds)->get()->keyBy('id');

        foreach ($fields as $index => $field) {
            $data = [
                'name'             => $field['name'],
                'label'            => $field['label'],
                'type'             => $field['type'],
                'options'          => $field['options'] ?? [],
                'validation_rules' => $field['validation_rules'] ?? ['nullable', 'string', 'max:255'],
                'placeholder'      => $field['placeholder'] ?? null,
                'order'            => $index + 1,
            ];

            if (!empty($field['id']) && $existingFields->has($field['id'])) {
                $existingFields->get($field['id'])->update($data);
            } else {
                $business->fields()->create($data);
            }
        }
    }

    /**
     * Rename permission slugs and sidebar references after a business slug change.
     */
    private function updateSlugReferences(string $role, string $oldSlug, string $newSlug, string $businessName): void
    {
        $from = "{$role}.{$oldSlug}";
        $to   = "{$role}.{$newSlug}";

        Permission::where('name', 'like', "{$from}.%")
            ->get()
            ->each(function ($permission) use ($from, $to) {
                $permission->update(['name' => str_replace($from, $to, $permission->name)]);
            });

        SidebarMeta::where('permissions', 'like', "%{$from}.viewAny%")
            ->get()
            ->each(function ($meta) use ($from, $to) {
                $meta->update([
                    'permissions' => array_map(fn ($p) => str_replace($from, $to, $p), $meta->permissions),
                ]);
            });

        $sidebar = Sidebar::whereHas('meta', fn ($q) => $q->where('permissions', 'like', "%{$to}.viewAny%"))->first();
        $sidebar?->update(['name' => $businessName]);
    }
}
