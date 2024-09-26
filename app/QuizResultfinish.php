<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizResultfinish extends Model
{
    protected $table = 'quizresultfinish';

    public function resultReview()
    {
        return $this->hasOne(QuizResultReview::class, 'quiz_result_id');
    }
}
