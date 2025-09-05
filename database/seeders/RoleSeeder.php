<?php

namespace Database\Seeders;

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

            $rolePermissions = config('rbac.permissions.' . $roleData['name'], []);

            if (is_string($rolePermissions) && in_array(strtolower($rolePermissions), ['*', 'all'])) {
                $rolePermissions = config('rbac.list.permissions', []);
            }

            Role::create($roleData)->syncPermissions($rolePermissions);
        }
    }
}
