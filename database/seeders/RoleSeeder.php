<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Role::query()->count() > 0) return;

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = config('rbac.list.roles');
        if (empty($roles)) {
            throw new \Exception('Error: config/rbac.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        $time = now();

        foreach ($roles as $role) {
            $roleData = is_string($role) ? ['name' => $role] : $role;
            $roleData['guard_name'] = 'api';
            $roleData['created_at'] = $time;
            $roleData['updated_at'] = $time;

            $roleName = $roleData['name'];
            $rolePermissions = config('rbac.permissions.' . $roleName, []);

            if (is_string($rolePermissions) && in_array(strtolower($rolePermissions), ['*', 'all'])) {
                $rolePermissions = Permission::pluck('name')->toArray();
                Role::create($roleData)->syncPermissions($rolePermissions);
                continue;
            }

            $crudPermissions = config('rbac.list.crud_permissions', []);
            $dynamicPermissions = config('rbac.list.dynamic_permissions.' . $roleName, []);

            foreach ($dynamicPermissions as $action) {
                foreach ($crudPermissions as $ability) {
                    $rolePermissions[] = "{$roleName}.{$action}.{$ability}";
                }
            }

            if ($roleName === 'bumdes') {
                foreach (config('rbac.list.dynamic_permissions.pokdarwis', []) as $action) {
                    foreach ($crudPermissions as $ability) {
                        $rolePermissions[] = "pokdarwis.{$action}.{$ability}";
                    }
                }
            }

            Role::create($roleData)->syncPermissions($rolePermissions);
        }
    }
}
