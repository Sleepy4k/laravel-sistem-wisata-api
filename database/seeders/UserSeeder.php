<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->withoutCache()->count() > 0) return;
        if (!app()->environment('local')) return;

        User::factory()->count(10)->create()->each(function ($user) {
            $user->assignRole(Arr::random(config('rbac.list.roles', [])));
        });

        User::create([
            'name' => 'Apri Pandu Wicaksono',
            'email' => 'pandu300478@gmail.com',
            'password' => 'password',
        ])->assignRole('admin');
    }
}
