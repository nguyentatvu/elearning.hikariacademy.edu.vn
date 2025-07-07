<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsClassSeries extends Model
{
    protected $table = 'lms_class_series';

    protected $fillable = [
        'class_id',
        'series_id',
        'created_by',
        'delete_status',
    ];

    public function series()
    {
        return $this->belongsTo(LmsSeries::class, 'id', 'series_id');
    }
}
