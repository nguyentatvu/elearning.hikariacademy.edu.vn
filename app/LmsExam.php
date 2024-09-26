<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsExam extends Model
{
    protected $table = 'lms_exams';

    /**
     * Gets the answers
     *
     * @return array
     */
    public function getAnswersAttribute()
    {
        return [
            $this->luachon1,
            $this->luachon2,
            $this->luachon3,
            $this->luachon4
        ];
    }
}