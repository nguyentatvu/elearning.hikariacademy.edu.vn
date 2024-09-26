<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsTest extends Model
{
    protected $table = 'lms_test';

    /**
     * Gets the answers
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
