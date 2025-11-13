<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kost Putra',
                'slug' => 'kost-putra',
                'description' => 'Kost khusus untuk pria/laki-laki'
            ],
            [
                'name' => 'Kost Putri',
                'slug' => 'kost-putri',
                'description' => 'Kost khusus untuk wanita/perempuan'
            ],
            [
                'name' => 'Kost Campur',
                'slug' => 'kost-campur',
                'description' => 'Kost untuk pria dan wanita (terpisah)'
            ],
            [
                'name' => 'Studio Room',
                'slug' => 'studio-room',
                'description' => 'Kamar studio dengan fasilitas lengkap'
            ],
            [
                'name' => 'Sharing Room',
                'slug' => 'sharing-room',
                'description' => 'Kamar berbagi dengan penghuni lain'
            ]
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
