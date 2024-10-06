<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JapaneseWritingPractice extends Model
{
    protected $table = "japanese_writing_practices";

    protected $fillable = ['title', 'type', 'slug'];

    public const HIRAGANA = 1;
    public const KANJI = 2;

    /**
     * Get the hiraganaWritingPractices that owns the JapaneseWritingPractice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hiraganaWritingPractices()
    {
        return $this->hasMany(HiraganaWritingPractice::class, 'practice_id', 'id');
    }

    /**
     * Get the kanjiWritingPractices that owns the JapaneseWritingPractice
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function kanjiWritingPractices()
    {
        return $this->hasMany(KanjiWritingPractice::class, 'practice_id', 'id');
    }
}
