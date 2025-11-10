<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EtipadDocument;
use Illuminate\Support\Facades\Storage;

class EtipadSeeder extends Seeder
{
    public function run(): void
    {
        // Placeholder content; will be replaced with actual file text provided by user.
        $candidates = [
            [
                'slug' => 'kode-etik-jurnalistik',
                'title' => 'Kode Etik Jurnalistik (KEJ)',
                'default_name' => 'kej.pdf',
            ],
            [
                'slug' => 'uu-pers-40-1999',
                'title' => 'Undang-Undang No. 40 Tahun 1999 tentang Pers',
                'default_name' => 'uu-40-1999.pdf',
            ],
            [
                'slug' => 'pedoman-siber',
                'title' => 'Pedoman Pemberitaan Media Siber',
                'default_name' => 'pedoman-siber.pdf',
            ],
        ];

        $docs = [];
        foreach ($candidates as $c) {
            $path = null;
            $mime = null;
            // try several common names in public storage
            $try = [
                'etipad/' . $c['default_name'],
                'etipad/' . $c['slug'] . '.pdf',
                'etipad/' . $c['slug'] . '.txt',
            ];
            foreach ($try as $fp) {
                if (Storage::disk('public')->exists($fp)) {
                    $path = $fp;
                    $mime = str_ends_with($fp, '.pdf') ? 'application/pdf' : 'text/plain';
                    break;
                }
            }
            // if not found, try to guess from any file under etipad/ by keywords
            if (!$path && Storage::disk('public')->exists('etipad')) {
                $all = Storage::disk('public')->files('etipad');
                $title = strtolower($c['title']);
                $keywords = [];
                if (str_contains($title, 'kode etik')) {
                    $keywords = ['kej', 'kode', 'etik', 'jurnalistik'];
                } elseif (str_contains($title, '1999') || str_contains($title, 'uu')) {
                    $keywords = ['40', '1999', 'uu', 'pers'];
                } elseif (str_contains($title, 'siber')) {
                    $keywords = ['pedoman', 'siber'];
                }
                foreach ($all as $fp) {
                    $name = strtolower(basename($fp));
                    $hit = 0;
                    foreach ($keywords as $k) {
                        if (str_contains($name, $k)) {
                            $hit++;
                        }
                    }
                    if ($hit >= max(1, count($keywords) - 2)) { // loose match
                        $path = $fp;
                        $ext = pathinfo($fp, PATHINFO_EXTENSION);
                        $mime = ($ext === 'pdf') ? 'application/pdf' : 'text/plain';
                        break;
                    }
                }
            }
            $docs[] = [
                'slug' => $c['slug'],
                'title' => $c['title'],
                'content' => $path ? null : 'Konten akan dimuat di sini. (Placeholder)',
                'file_path' => $path,
                'mime_type' => $mime,
            ];
        }

        foreach ($docs as $doc) {
            EtipadDocument::updateOrCreate(['slug' => $doc['slug']], $doc);
        }
    }
}
