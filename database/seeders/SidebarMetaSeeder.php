<?php

namespace Database\Seeders;

use App\Models\SidebarMeta;
use Illuminate\Database\Seeder;

class SidebarMetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (SidebarMeta::query()->withoutCache()->count() > 0) return;

        $metas = SidebarMeta::factory()->make();

        SidebarMeta::insert($metas->toArray());
    }
}
