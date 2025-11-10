<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentenceCorrection extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_sentence_id',
        'text',
        'is_correct',
        'rationale'
    ];

    public function sentence()
    {
        return $this->belongsTo(CaseSentence::class, 'case_sentence_id');
    }
}
