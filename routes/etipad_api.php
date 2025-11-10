<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EtipadController;
use App\Models\EtipadNews;

Route::get('/etipad/docs', [EtipadController::class, 'index']);
Route::get('/etipad/docs/{slug}', [EtipadController::class, 'show']);

// News endpoints
Route::get('/etipad/news', function () {
    $items = EtipadNews::orderByDesc('published_at')->orderByDesc('id')->get()->map(function ($n) {
        return [
            'slug' => $n->slug,
            'title' => $n->title,
            'tag' => $n->tag,
            'author' => $n->author,
            'summary' => $n->summary,
            'published_at' => optional($n->published_at)->format('d M Y'),
        ];
    });
    return response()->json(['news' => $items]);
});

Route::get('/etipad/news/{slug}', function ($slug) {
    $n = EtipadNews::where('slug', $slug)->first();
    if (!$n) return response()->json(['error' => 'Not found'], 404);
    return response()->json(['news' => [
        'slug' => $n->slug,
        'title' => $n->title,
        'tag' => $n->tag,
        'author' => $n->author,
        'content' => $n->content,
        'published_at' => optional($n->published_at)->format('d M Y'),
        'hero_url' => $n->hero_url,
    ]]);
});
