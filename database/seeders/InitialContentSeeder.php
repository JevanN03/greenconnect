<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\CollectionPoint;

class InitialContentSeeder extends Seeder
{
    public function run(): void
    {
        Article::factory()->create([
            'title' => 'Mengelola Sampah Rumah Tangga dengan 3R',
            'excerpt' => 'Pengantar 3R: Reduce, Reuse, Recycle.',
            'content' => 'Konten lengkap tentang 3R...',
        ]);

        CollectionPoint::create([
            'name' => 'TPS Banyuwangi',
            'contact' => '0812-0000-0000',
            'address' => 'Jl. Contoh No. 1, Banyuwangi',
            'map_link' => 'https://maps.google.com/?q=-8.2,114.3'
        ]);
    }
}
