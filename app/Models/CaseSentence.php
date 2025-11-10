<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseSentence extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'sentence_index',
        'text'
    ];

    public function case()
    {
        return $this->belongsTo(CaseStudy::class, 'case_id');
    }

    public function violations()
    {
        return $this->hasMany(SentenceViolation::class, 'case_sentence_id');
    }

    public function corrections()
    {
        return $this->hasMany(SentenceCorrection::class, 'case_sentence_id');
    }
}
