<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    protected $table = 'comments';

    public const UNSEEN_STATUS = 0;
    public const ANSWERED_STATUS = 1;
    public const SEEN_STATUS = 2;

    /**
     * Relationship with child comments
     */
    public function childComments() {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relationship with user
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    /**
     * Relationship with LmsSeries
     */
    public function series() {
        return $this->belongsTo(LmsSeries::class, 'lmsseries_id', 'id');
    }

    /**
     * Relationship with LmsSeries
     */
    public function comboSeries() {
        return $this->belongsTo(LmsSeriesCombo::class, 'lmscombo_id', 'id');
    }

    /**
     * Relationship with LmsContent
     */
    public function lesson() {
        return $this->belongsTo(LmsContent::class, 'lmscontent_id', 'id');
    }
}
