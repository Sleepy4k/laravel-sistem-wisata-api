<?php

namespace Database\Factories;

use App\Models\SidebarMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sidebar>
 */
class SidebarFactory extends Factory
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
                'name'      => 'Pokdarwis',
                'is_spacer' => true,
            ],
            [
                'name'      => 'Tiket Wisata',
            ],
            [
                'name'      => 'River Tubing',
            ],
            [
                'name'      => 'Sewa Warung',
            ],
            [
                'name'      => 'Sewa UMKM',
            ],
            [
                'name'      => 'Pakan Ikan',
            ],
            [
                'name'      => 'Tambah Usaha',
            ],
            [
                'name'      => 'Bumdes',
                'is_spacer' => true,
            ],
            [
                'name'      => 'Sewa Resto',
            ],
            [
                'name'      => 'Sewa Internet',
            ],
            [
                'name'      => 'Tambah Usaha',
            ],
        ];

        $currentTime = now();
        $menuMetaIds = SidebarMeta::pluck('id')->toArray();

        foreach ($data as $index => &$item) {
            $item = array_merge([
                'id' => \Illuminate\Support\Str::uuid(),
                'is_spacer' => false,
            ], $item);

            $item['order'] = $index++;
            $item['sidebar_meta_id'] = $menuMetaIds[$index - 1] ?? null;
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
