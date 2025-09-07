<?php

namespace Database\Seeders;

use App\Models\BusinessField;
use Illuminate\Database\Seeder;

class BusinessFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (BusinessField::query()->withoutCache()->count() > 0) return;

        $fields = BusinessField::factory()->make();

        BusinessField::insert($fields->toArray());
    }
}
