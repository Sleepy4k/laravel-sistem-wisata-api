<?php

namespace Database\Factories;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SidebarMeta>
 */
class SidebarMetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessSlugs = Business::select('name', 'slug')->get();

        $findSlug = function (string $name) use ($businessSlugs): ?string {
            $name = strtolower($name);
            return collect($businessSlugs)->first(fn($item) => str_contains($name, $item->name))?->slug;
        };

        $data = [
            [
                'permissions'   => [
                    'pokdarwis.tourist_ticket.viewAny',
                    'pokdarwis.river_tubing.viewAny',
                    'pokdarwis.stall_rental.viewAny',
                    'pokdarwis.umkm_rental.viewAny',
                    'pokdarwis.fish_feed.viewAny',
                    'manage.pokdarwis.business'
                ],
            ],
            [
                'icon'          => 'box',
                'route'         => 'dashboard.role.section',
                'permissions'   => ['pokdarwis.tourist_ticket.viewAny'],
                'parameters'    => ['role' => 'pokdarwis', 'section' => $findSlug('Tiket Wisata') ?? 'tiket-wisata'],
            ],
            [
                'icon'          => 'activity',
                'route'         => 'dashboard.role.section',
                'permissions'   => ['pokdarwis.river_tubing.viewAny'],
                'parameters'    => ['role' => 'pokdarwis', 'section' => $findSlug('River Tubing') ?? 'river-tubing'],
            ],
            [
                'icon'          => 'shopping-bag',
                'route'         => 'dashboard.role.section',
                'permissions'   => ['pokdarwis.stall_rental.viewAny'],
                'parameters'    => ['role' => 'pokdarwis', 'section' => $findSlug('Sewa Warung') ?? 'sewa-warung'],
            ],
            [
                'icon'          => 'shopping-cart',
                'route'         => 'dashboard.role.section',
                'permissions'   => ['pokdarwis.umkm_rental.viewAny'],
                'parameters'    => ['role' => 'pokdarwis', 'section' => $findSlug('Sewa UMKM') ?? 'sewa-umkm'],
            ],
            [
                'icon'          => 'fish',
                'route'         => 'dashboard.role.section',
                'permissions'   => ['pokdarwis.fish_feed.viewAny'],
                'parameters'    => ['role' => 'pokdarwis', 'section' => $findSlug('Pakan Ikan') ?? 'pakan-ikan'],
            ],
            [
                'icon'          => 'plus-circle',
                'permissions'   => ['manage.pokdarwis.business'],
            ],
            [
                'permissions'   => [
                    'bumdes.restaurant_rental.viewAny',
                    'bumdes.internet_rental.viewAny',
                    'manage.bumdes.business'
                ],
            ],
            [
                'icon'          => 'coffee',
                'route'         => 'dashboard.role.section',
                'permissions'   => ['bumdes.rent_restaurant.viewAny'],
                'parameters'    => ['role' => 'bumdes', 'section' => $findSlug('Sewa Resto') ?? 'sewa-resto'],
            ],
            [
                'icon'          => 'wifi',
                'route'         => 'dashboard.role.section',
                'permissions'   => ['bumdes.rent_internet.viewAny'],
                'parameters'    => ['role' => 'bumdes', 'section' => $findSlug('Sewa Internet') ?? 'sewa-internet'],
            ],
            [
                'icon'          => 'plus-circle',
                'permissions'   => ['manage.bumdes.business'],
            ],
        ];

        $uuids = collect(range(1, count($data)))
            ->map(fn() => (string) \Illuminate\Support\Str::uuid())
            ->sort()
            ->values()
            ->all();

        $currentTime = now();

        foreach ($data as $index => &$item) {
            $item = array_merge([
                'icon' => null,
                'route' => null,
                'permissions' => [],
                'parameters' => [],
            ], $item);

            $item['id'] = $uuids[$index];
            $item['permissions'] = json_encode($item['permissions']);
            $item['parameters'] = json_encode($item['parameters']);
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;
        }

        unset($item);

        return $data;
    }
}
