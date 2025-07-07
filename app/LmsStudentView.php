<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsStudentView extends Model
{
    protected $table = 'lms_student_view';
    public $timestamps = false;

    public const NOT_FINISHED = 0;
    public const FINISH = 1;

    protected $fillable = [
        'lmscontent_id',
        'users_id',
        'view_time',
        'finish',
        'type',
        'created_date'
    ];

    /**
     * Relationship with LmsContent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lmsContent() {
        return $this->belongsTo(LmsContent::class, 'lmscontent_id', 'id');
    }

    public function scopeHasLmsContentByTypeAndSeries($query, $types, $seriesIds, $exclude = false)
    {
        return $query->whereHas('lmsContent', function ($q) use ($types, $seriesIds, $exclude) {
            if ($exclude) {
                $q->whereNotIn('type', $types);
            } else {
                $q->whereIn('type', $types);
            }

            $q->whereIn('lmsseries_id', $seriesIds);
        });
    }
}
