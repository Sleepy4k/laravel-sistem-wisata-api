<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $isMustSilent = app()->environment('production');

        Artisan::call('optimize:clear', [
            '--no-interaction' => true,
            '--quiet' => $isMustSilent,
        ]);

        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            BusinessSeeder::class,
            BusinessFieldSeeder::class,
            SidebarMetaSeeder::class,
            SidebarSeeder::class,
        ], $isMustSilent);
    }
}
