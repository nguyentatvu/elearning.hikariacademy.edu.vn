<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intonation extends Model
{
    protected $table = 'intonations';

    protected $fillable = [
        'pronunciation_detail_id',
        'word',
        'start',
        'end',
        'average'
    ];
}