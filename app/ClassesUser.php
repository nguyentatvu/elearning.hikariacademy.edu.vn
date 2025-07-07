<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassesUser extends Model
{
    protected $table = 'classes_user';

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

}


