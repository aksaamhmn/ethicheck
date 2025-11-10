<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EtipadNews;

class EtipadNewsCleanupSeeder extends Seeder
{
    public function run(): void
    {
        $slugs = [
            'judul-utama-berita-contoh-hari-ini',
            'update-kebijakan-media-terbaru',
            'tren-konsumsi-berita-milenial',
            'literasi-informasi-di-sekolah',
            'teknologi-moderasi-konten-ai',
            'kolaborasi-media-lokal',
            'pelatihan-verifikasi-fakta',
            'ekonomi-iklan-digital',
            'transparansi-algoritma-portal',
        ];

        EtipadNews::whereIn('slug', $slugs)->delete();
    }
}
