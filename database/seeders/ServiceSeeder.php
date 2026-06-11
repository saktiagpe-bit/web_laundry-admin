<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'Laundry Kiloan', 'slug' => 'laundry-kiloan', 'description' => 'Cuci dan setrika harian', 'price' => 7000, 'estimate_hours' => 48],
            ['name' => 'Cuci Kering', 'slug' => 'cuci-kering', 'description' => 'Cuci dan keringkan saja (tanpa setrika)', 'price' => 5000, 'estimate_hours' => 24],
            ['name' => 'Cuci Setrika', 'slug' => 'cuci-setrika', 'description' => 'Cuci bersih dan setrika rapi', 'price' => 8000, 'estimate_hours' => 48],
            ['name' => 'Setrika Saja', 'slug' => 'setrika-saja', 'description' => 'Hanya setrika (baju sudah dicuci)', 'price' => 4000, 'estimate_hours' => 24],
            ['name' => 'Laundry Sepatu', 'slug' => 'laundry-sepatu', 'description' => 'Cuci bersih semua jenis sepatu', 'price' => 25000, 'estimate_hours' => 72],
            ['name' => 'Laundry Boneka', 'slug' => 'laundry-boneka', 'description' => 'Cuci boneka bulu kecil atau sedang', 'price' => 20000, 'estimate_hours' => 48],
            ['name' => 'Laundry Karpet', 'slug' => 'laundry-karpet', 'description' => 'Cuci karpet ukuran sedang/besar', 'price' => 15000, 'estimate_hours' => 96],
            ['name' => 'Laundry Bed Cover', 'slug' => 'laundry-bed-cover', 'description' => 'Cuci bed cover ukuran besar', 'price' => 20000, 'estimate_hours' => 48],
        ];

        foreach ($services as $service) {
            \App\Models\Service::create($service);
        }
    }
}
