<?php

namespace Database\Seeders;

use App\Models\Sidebar;
use Illuminate\Database\Seeder;

class SidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Sidebar::query()->withoutCache()->count() > 0) return;

        $metas = Sidebar::factory()->make();

        Sidebar::insert($metas->toArray());
    }
}
