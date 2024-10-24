<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LmsSeriesCombo extends Model
{
    protected $table = 'lmsseries_combo';

    protected $casts = [
        'description' => 'array'
    ];

    public const LEARNING_TYPE = 0;
    public const EXAM_TYPE = 1;

    public static function getRecordWithSlug($slug)
    {
        return LmsSeriesCombo::where('slug', '=', $slug)->first();
    }

    public function getCheckMultipleComboAttribute() {
        $seriesCount = 0;
        for($i = 1; $i <= 5; $i++) {
            if (!is_null($this->{'n'.$i})) {
                $seriesCount++;
            }
        }

        return $seriesCount > 1;
    }
}
