<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRoadmap extends Model
{
    protected $table = 'user_roadmaps';

    protected $fillable = [
        'user_id',
        'lmsseries_id',
        'duration_months'
    ];

    /**
     * Define relationship with LmsSeries
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lmsSeries()
    {
        return $this->belongsTo(LmsSeries::class, 'lmsseries_id');
    }
}
