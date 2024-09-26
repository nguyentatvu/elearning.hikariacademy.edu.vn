<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flashcard extends Model
{
    protected $table = 'lms_flashcard';

    public function flashcardDetails()
    {
        return $this->hasMany(FlashcardDetail::class, 'flashcard_id', 'id');
    }
}
