<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsStudentView extends Model
{
    protected $table = 'lms_student_view';
    public $timestamps = false;

    public const NOT_FINISHED = 0;
    public const FINISH = 1;
}
