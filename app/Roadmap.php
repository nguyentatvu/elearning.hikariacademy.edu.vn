<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roadmap extends Model
{
    protected $table = 'roadmaps';

    protected $fillable = [
        'duration_months',
        'lmsseries_id',
        'contents',
        'description'
    ];

    protected $casts = [
        'contents' => 'array'
    ];
}
