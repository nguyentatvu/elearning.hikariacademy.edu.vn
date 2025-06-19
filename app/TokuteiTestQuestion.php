<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TokuteiTestQuestion extends Model
{
    protected $table = 'tokutei_test_question';

    protected $fillable = [
        'lms_content_id',
        'question_order',
        'content',
        'point',
        'options',
        'answer',
        'section',
        'category',
        'tokutei_test_type',
        'image_url',
        'is_deleted'
    ];

    protected $casts = [
        'options' => 'array',
    ];
}
