<?php

namespace Database\Seeders;

use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Business::query()->withoutCache()->count() > 0) return;

        $business = Business::factory()->make();

        Business::insert($business->toArray());
    }
}
