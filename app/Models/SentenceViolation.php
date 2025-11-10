<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentenceViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_sentence_id',
        'violation_code',
        'violation_title',
        'snippet',
        'description',
        'legal_basis',
        'severity'
    ];

    public function sentence()
    {
        return $this->belongsTo(CaseSentence::class, 'case_sentence_id');
    }
}
