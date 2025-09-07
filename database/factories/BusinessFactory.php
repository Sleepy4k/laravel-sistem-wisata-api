<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $data = [
            [
                'name' => 'Tiket Wisata'
            ],
            [
                'name' => 'River Tubing'
            ],
            [
                'name' => 'Sewa Warung'
            ],
            [
                'name' => 'Sewa UMKM'
            ],
            [
                'name' => 'Pakan Ikan'
            ],
            [
                'name' => 'Sewa Resto'
            ],
            [
                'name' => 'Sewa Internet'
            ]
        ];

        $currentTime = now();

        foreach ($data as &$item) {
            $item = array_merge([
                'id' => Str::uuid(),
                'is_active' => true,
            ], $item);

            $item['slug'] = Str::slug($item['name']);
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
