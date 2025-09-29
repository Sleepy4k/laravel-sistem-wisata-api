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
use Illuminate\Support\Str;

class BusinessService extends Service
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(string $role, array $request): mixed
    {
        try {
            // handle create business, create permission based on business slug, assign permission to role
            $business = Business::create([
                'name' => $request['name'],
                'slug' => Str::slug($request['name']),
                'is_active' => $request['is_active'] ?? true,
            ]);

            // create business fields
            if (isset($request['fields']) && is_array($request['fields'])) {
                foreach ($request['fields'] as $index => $field) {
                    BusinessField::create([
                        'business_id' => $business->id,
                        'name' => $field['name'],
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'options' => $field['options'] ?? [],
                        'validation_rules' => $field['validation_rules'] ?? ['nullable', 'string', 'max:255'],
                        'placeholder' => $field['placeholder'] ?? null,
                        'order' => $index + 1,
                    ]);
                }
            }

            // create permission
            $permissions = [];

            foreach (config('rbac.list.crud_permissions') as $ability) {
                $permission = Permission::firstOrCreate([
                    'name' => "{$role}.{$business->slug}.{$ability}",
                    'guard_name' => 'api',
                ]);
                $permissions[] = $permission->id;
            }

            // assign permission to all roles except pemdes
            $roles = Role::where('name', '!=', 'pemdes')->get();
            foreach ($roles as $roleModel) {
                $roleModel->permissions()->syncWithoutDetaching($permissions);
            }

            //create sidebar meta
            $sidebarMeta = SidebarMeta::firstOrCreate([
                'icon' => $request['icon'] ?? 'briefcase',
                'route' => 'dashboard.role.section.index',
                'permissions' => ["{$role}.{$business->slug}.viewAny"],
                'parameters' => ['role' => $role, 'business' => $business->slug],
            ]);

            // Find the insertion position for the new business menu
            $sidebars = Sidebar::select('id', 'sidebar_meta_id', 'order')->with('meta:id,parameters')->orderBy('order')->get();
            $roleBasedSidebars = $sidebars->filter(function ($sidebar) use ($role) {
                return $sidebar->meta &&
                    isset($sidebar->meta->parameters['role']) &&
                    $sidebar->meta->parameters['role'] === $role;
            });

            // Determine the order position for the new sidebar
            $insertOrder = $roleBasedSidebars->isEmpty() ? 0 : $roleBasedSidebars->max('order');

            // Shift existing sidebars if necessary
            Sidebar::where('order', '>=', $insertOrder)->increment('order');

            // Create the new sidebar
            Sidebar::create([
                'name' => $business->name,
                'order' => $insertOrder,
                'is_spacer' => false,
                'sidebar_meta_id' => $sidebarMeta->id,
            ]);

            return ApiResponse::success(null, 'Business created successfully', 201);
        } catch (\Throwable $th) {
            return ApiResponse::error(null, 'Failed to create business: ' . $th->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $role, Business $business, array $request): mixed
    {
        return ApiResponse::error(null, 'Not implemented', 501);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $role, Business $business): mixed
    {
        return ApiResponse::error(null, 'Not implemented', 501);
    }
}
