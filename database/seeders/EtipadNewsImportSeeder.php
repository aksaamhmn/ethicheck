<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use App\Models\EtipadNews;
use Illuminate\Support\Str;

class EtipadNewsImportSeeder extends Seeder
{
    public function run(): void
    {
        $path = 'etipad/news.json'; // storage/app/etipad/news.json
        if (!Storage::disk('local')->exists($path)) {
            $this->command?->warn("No news.json found at storage/app/{$path}. Skipping import.");
            return;
        }
        $json = Storage::disk('local')->get($path);
        $data = json_decode($json, true);
        if (!is_array($data)) {
            $this->command?->error('Invalid JSON format for news import. Expecting an array of items.');
            return;
        }
        $count = 0;
        foreach ($data as $item) {
            if (!isset($item['title'])) continue;
            EtipadNews::updateOrCreate(
                ['slug' => $item['slug'] ?? Str::slug($item['title'])],
                [
                    'title' => $item['title'],
                    'tag' => $item['tag'] ?? null,
                    'author' => $item['author'] ?? 'Redaksi',
                    'summary' => $item['summary'] ?? null,
                    'content' => $item['content'] ?? null,
                    'published_at' => $item['published_at'] ?? ($item['date'] ?? null),
                    'hero_url' => $item['hero_url'] ?? null,
                ]
            );
            $count++;
        }
        $this->command?->info("Imported {$count} news items from JSON.");
    }
}
