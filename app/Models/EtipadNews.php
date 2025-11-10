<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtipadNews extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'tag',
        'summary',
        'content',
        'author',
        'published_at',
        'hero_url',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
