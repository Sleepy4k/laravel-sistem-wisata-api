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

        User::create([
            'name' => 'Pemdes',
            'email' => 'pemdes@gmail.com',
            'password' => 'password',
        ])->assignRole('pemdes');

        User::create([
            'name' => 'Pokdarwis',
            'email' => 'pokdarwis@gmail.com',
            'password' => 'password',
        ])->assignRole('pokdarwis');

        User::create([
            'name' => 'Bumdes',
            'email' => 'bumdes@gmail.com',
            'password' => 'password',
        ])->assignRole('bumdes');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'password',
        ])->assignRole('admin');

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
