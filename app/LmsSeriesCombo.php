<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LmsSeriesCombo extends Model
{
    protected $table = 'lmsseries_combo';



    public static function getRecordWithSlug($slug)
    {
        return LmsSeriesCombo::where('slug', '=', $slug)->first();
    }
}
