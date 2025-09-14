<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Permission::query()->count() > 0) return;

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = config('rbac.list.permissions');

        if (empty($permissions)) {
            throw new \Exception('Error: config/rbac.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        $dynamicPermissions = config('rbac.list.dynamic_permissions', []);

        foreach ($dynamicPermissions as $entity => $actions) {
            foreach ($actions as $action) {
                $permissions[] = "{$entity}.{$action}.viewAny";
                $permissions[] = "{$entity}.{$action}.store";
                $permissions[] = "{$entity}.{$action}.view";
                $permissions[] = "{$entity}.{$action}.update";
                $permissions[] = "{$entity}.{$action}.delete";
            }
        }

        $time = now();

        $permission = collect($permissions)->map(function ($name) use ($time) {
            return [
                'name' => $name,
                'guard_name' => 'api',
                'created_at' => $time,
                'updated_at' => $time,
            ];
        });

        Permission::insert($permission->toArray());

    }
}
