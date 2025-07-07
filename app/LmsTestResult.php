<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsTestResult extends Model
{
    protected $table = 'lms_test_result';

    public function lmsContent()
    {
        return $this->belongsTo(LmsContent::class, 'lmscontent_id', 'id');
    }
}
