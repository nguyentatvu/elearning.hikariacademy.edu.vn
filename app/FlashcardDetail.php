<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FlashcardDetail extends Model
{
    protected $table = 'lms_flashcard_detail';

    public function flashcard()
    {
        return $this->belongsTo(Flashcard::class, 'flashcard_id', 'id');
    }
}
