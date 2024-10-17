<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointRule extends Model
{
    protected $table = 'point_rules';

    protected $fillable = [
        'rules'
    ];

    protected $casts = [
        'rules' => 'array'
    ];
}
