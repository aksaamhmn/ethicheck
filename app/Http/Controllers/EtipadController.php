<?php

namespace App\Http\Controllers;

use App\Models\EtipadDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EtipadController extends Controller
{
    // GET /api/etipad/docs
    public function index()
    {
        $docs = EtipadDocument::orderBy('id')->get()->map(function ($d) {
            return [
                'slug' => $d->slug,
                'title' => $d->title,
                'file_url' => $d->file_path ? Storage::url($d->file_path) : null,
                'mime_type' => $d->mime_type,
            ];
        });
        return response()->json(['documents' => $docs]);
    }

    // GET /api/etipad/docs/{slug}
    public function show($slug)
    {
        $d = EtipadDocument::where('slug', $slug)->first();
        if (!$d) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json([
            'document' => [
                'slug' => $d->slug,
                'title' => $d->title,
                'content' => $d->content,
                'file_url' => $d->file_path ? Storage::url($d->file_path) : null,
                'mime_type' => $d->mime_type,
            ],
        ]);
    }
}
