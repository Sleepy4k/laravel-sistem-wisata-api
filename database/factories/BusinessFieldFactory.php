<?php

namespace Database\Factories;

use App\Enums\FieldInputType;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessField>
 */
class BusinessFieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $businessSlugs = Business::select('id', 'slug')->get();

        $findSlug = function (string $name) use ($businessSlugs): ?string {
            $name = strtolower($name);
            return collect($businessSlugs)->first(fn($item) => str_contains($name, $item->slug))?->id;
        };

        $data = [
            [
                'slug' => 'tiket-wisata',
                'name' => 'date_of_visit',
                'label' => 'Tanggal Kunjungan',
                'type' => FieldInputType::DATE,
                'validation_rules' => ['required', 'date'],
                'placeholder' => 'Masukkan tanggal kunjungan',
                'order' => 1,
            ],
            [
                'slug' => 'tiket-wisata',
                'name' => 'number_of_visitors',
                'label' => 'Jumlah Pengunjung',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan jumlah pengunjung',
                'order' => 2,
            ],
            [
                'slug' => 'tiket-wisata',
                'name' => 'amount',
                'label' => 'Total Harga',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan total harga',
                'order' => 3,
            ],
            [
                'slug' => 'tiket-wisata',
                'name' => 'note',
                'label' => 'Catatan',
                'type' => FieldInputType::TEXTAREA,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'placeholder' => 'Masukkan catatan (opsional)',
                'order' => 4,
            ],
            [
                'slug' => 'river-tubing',
                'name' => 'date_of_activity',
                'label' => 'Tanggal Aktivitas',
                'type' => FieldInputType::DATE,
                'validation_rules' => ['required', 'date'],
                'placeholder' => 'Masukkan tanggal aktivitas',
                'order' => 1,
            ],
            [
                'slug' => 'river-tubing',
                'name' => 'number_of_participants',
                'label' => 'Jumlah Peserta',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan jumlah peserta',
                'order' => 2,
            ],
            [
                'slug' => 'river-tubing',
                'name' => 'amount',
                'label' => 'Total Harga',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan total harga',
                'order' => 3,
            ],
            [
                'slug' => 'river-tubing',
                'name' => 'note',
                'label' => 'Catatan',
                'type' => FieldInputType::TEXTAREA,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'placeholder' => 'Masukkan catatan (opsional)',
                'order' => 4,
            ],
            [
                'slug' => 'sewa-warung',
                'name' => 'rental_date',
                'label' => 'Tanggal Sewa',
                'type' => FieldInputType::DATE,
                'validation_rules' => ['required', 'date'],
                'placeholder' => 'Masukkan tanggal sewa',
                'order' => 1,
            ],
            [
                'slug' => 'sewa-warung',
                'name' => 'amount',
                'label' => 'Total Harga',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan total harga',
                'order' => 2,
            ],
            [
                'slug' => 'sewa-warung',
                'name' => 'note',
                'label' => 'Catatan',
                'type' => FieldInputType::TEXTAREA,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'placeholder' => 'Masukkan catatan (opsional)',
                'order' => 3,
            ],
            [
                'slug' => 'sewa-umkm',
                'name' => 'rental_date',
                'label' => 'Tanggal Sewa',
                'type' => FieldInputType::DATE,
                'validation_rules' => ['required', 'date'],
                'placeholder' => 'Masukkan tanggal sewa',
                'order' => 1,
            ],
            [
                'slug' => 'sewa-umkm',
                'name' => 'amount',
                'label' => 'Total Harga',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan total harga',
                'order' => 2,
            ],
            [
                'slug' => 'sewa-umkm',
                'name' => 'note',
                'label' => 'Catatan',
                'type' => FieldInputType::TEXTAREA,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'placeholder' => 'Masukkan catatan (opsional)',
                'order' => 3,
            ],
            [
                'slug' => 'pakan-ikan',
                'name' => 'purchase_date',
                'label' => 'Tanggal Pembelian',
                'type' => FieldInputType::DATE,
                'validation_rules' => ['required', 'date'],
                'placeholder' => 'Masukkan tanggal pembelian',
                'order' => 1,
            ],
            [
                'slug' => 'pakan-ikan',
                'name' => 'quantity',
                'label' => 'Jumlah (kg)',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan jumlah (kg)',
                'order' => 2,
            ],
            [
                'slug' => 'pakan-ikan',
                'name' => 'amount',
                'label' => 'Total Harga',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan total harga',
                'order' => 3,
            ],
            [
                'slug' => 'pakan-ikan',
                'name' => 'note',
                'label' => 'Catatan',
                'type' => FieldInputType::TEXTAREA,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'placeholder' => 'Masukkan catatan (opsional)',
                'order' => 4,
            ],
            [
                'slug' => 'sewa-resto',
                'name' => 'rental_date',
                'label' => 'Tanggal Sewa',
                'type' => FieldInputType::DATE,
                'validation_rules' => ['required', 'date'],
                'placeholder' => 'Masukkan tanggal sewa',
                'order' => 1,
            ],
            [
                'slug' => 'sewa-resto',
                'name' => 'amount',
                'label' => 'Total Harga',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan total harga',
                'order' => 2,
            ],
            [
                'slug' => 'sewa-resto',
                'name' => 'note',
                'label' => 'Catatan',
                'type' => FieldInputType::TEXTAREA,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'placeholder' => 'Masukkan catatan (opsional)',
                'order' => 3,
            ],
            [
                'slug' => 'sewa-internet',
                'name' => 'rental_date',
                'label' => 'Tanggal Sewa',
                'type' => FieldInputType::DATE,
                'validation_rules' => ['required', 'date'],
                'placeholder' => 'Masukkan tanggal sewa',
                'order' => 1,
            ],
            [
                'slug' => 'sewa-internet',
                'name' => 'amount',
                'label' => 'Total Harga',
                'type' => FieldInputType::NUMBER,
                'validation_rules' => ['required', 'integer', 'min:0'],
                'placeholder' => 'Masukkan total harga',
                'order' => 2,
            ],
            [
                'slug' => 'sewa-internet',
                'name' => 'note',
                'label' => 'Catatan',
                'type' => FieldInputType::TEXTAREA,
                'validation_rules' => ['nullable', 'string', 'max:255'],
                'placeholder' => 'Masukkan catatan (opsional)',
                'order' => 3,
            ],
        ];

        $currentTime = now();

        foreach ($data as &$item) {
            $item = array_merge([
                'id' => Str::uuid(),
                'options' => [],
                'validation_rules' => [],
                'placeholder' => null,
                'type' => FieldInputType::TEXT,
            ], $item);

            $item['type'] = $item['type']->value;
            $item['options'] = json_encode($item['options']);
            $item['validation_rules'] = json_encode($item['validation_rules']);
            $item['business_id'] = $findSlug($item['slug']) ?? null;
            $item['created_at'] = $currentTime;
            $item['updated_at'] = $currentTime;

            unset($item['slug']);
        }

        unset($item);

        return $data;
    }
}
