<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KanjiWritingPractice extends Model
{
    protected $table = "kanji_writing_practices";

    protected $fillable = ['practice_id', 'number', 'full_word', 'underlined_word', 'kanji'];
}