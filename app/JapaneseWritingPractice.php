<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JapaneseWritingPractice extends Model
{
    protected $table = "japanese_writing_practices";

    protected $fillable = ['title', 'type', 'slug'];

    public const HIRAGANA = 1;
    public const KANJI = 2;
}
