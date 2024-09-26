<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizResultReview extends Model
{
    protected $table = 'quiz_result_reviews';

    protected $fillable = [
        'quiz_result_id',
        'student_id',
        'teacher_id',
        'review',
    ];

    /**
     * Get the teacher that made the review
     */
    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
