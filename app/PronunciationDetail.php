<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PronunciationDetail extends Model
{
    protected $table = "pronunciation_details";

    protected $fillable = ['pronunciation_id', 'katakana_text', 'text', 'audio', 'words', 'recognized_text'];

    protected $casts = [
        'words' => 'array',
    ];
}