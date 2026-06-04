<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    // Membuat User Biasa untuk testing
    \App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password'),
        'phone' => '08123456789',
        'phone_verified_at' => now(),
        'gender' => 'female',
        'address' => 'Jl. Mawar No 1',
        'role' => 'user',
    ]);

    // Membuat User Admin Baru
    \App\Models\User::create([
        'name' => 'Administrator',
        'email' => 'admin@laundry.com',
        'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        'phone' => '081122334455',
        'phone_verified_at' => now(),
        'gender' => 'male',
        'address' => 'Kantor Pusat Laundry',
        'role' => 'admin',
    ]);
}
}
