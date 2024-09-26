<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    protected $table = 'comments';

    /**
     * Relationship with child comments
     */
    public function childComments() {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }
}
