<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = "banners";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'size',
        'title',
        'description',
        'display_type',
        'image',
        'to_url',
        'position',
        'is_active'
    ];
}
