<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HiraganaWritingPractice extends Model
{
    protected $table = "hiragana_writing_practices";

    protected $fillable = ['practice_id', 'character', 'number'];
}