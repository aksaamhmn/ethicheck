<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudy extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = [
        'topic_id',
        'title',
        'summary',
        'article_body',
        'final_title',
        'final_article',
        'is_active'
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function sentences()
    {
        return $this->hasMany(CaseSentence::class, 'case_id')->orderBy('sentence_index');
    }
}
