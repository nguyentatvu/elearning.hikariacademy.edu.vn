<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrafficRuleTestQuestion extends Model
{
    protected $table = 'traffic_rule_test_question';

    protected $fillable = [
        'lms_content_id',
        'parent_question_id',
        'question_order',
        'content',
        'point',
        'image_url',
        'option_1',
        'option_2',
        'answer'
    ];

    public function childQuestions() {
        return $this->hasMany(TrafficRuleTestQuestion::class, 'parent_question_id', 'id');
    }
}
