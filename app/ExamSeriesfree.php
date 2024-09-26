<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExamSeriesfree extends Model
{
    protected $table = 'exam_free';

    public static function getRecordWithSlug($slug)
    {
        return ExamSeriesfree::where('id', '=', $slug)->first();
    }
}
