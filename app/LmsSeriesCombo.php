<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LmsSeriesCombo extends Model
{
    protected $table = 'lmsseries_combo';

    public const LEARNING_TYPE = 0;
    public const EXAM_TYPE = 1;

    public static function getRecordWithSlug($slug)
    {
        return LmsSeriesCombo::where('slug', '=', $slug)->first();
    }
}
