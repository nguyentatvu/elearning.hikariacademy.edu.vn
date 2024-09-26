<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LmsContent extends Model
{
    protected $table = 'lmscontents';

    public const LESSON = 0;
    public const VOCABULARY = 1;
    public const STRUCTURE = 2;
    public const PARTIAL_EXERCISE = 3;
    public const SUMMARY_EXERCISE = 4;
    public const TEST = 5;
    public const KANJI = 6;
    public const REVIEW_EXERCISE = 7;
    public const LESSON_TOPIC = 8;
    public const SUMMARY_AND_INTRODUCTION = 9;
    public const FLASHCARD = 10;

    public static function getRecordWithSlug($slug)
    {
        return LmsContent::where('slug', '=', $slug)->first();
    }

    public static function getRecordWithId($slug)
    {
        return LmsContent::where('id', '=', $slug)->first();
    }

    public function category()
    {
        return $this->belongsTo('App\Lmscategory', 'category_id');
    }

    /**
     * Relationship with Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'lmscontent_id', 'id');
    }

    /**
     * Relationship with LmsContent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childContents()
    {
        return $this->hasMany(LmsContent::class, 'parent_id', 'id');
    }

    /**
     * Define the inverse relationship to LmsSeries
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lmsseries()
    {
        return $this->belongsTo(LmsSeries::class, 'lmsseries_id');
    }

    /**
     * Relationship with LmsStudentView
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lmsStudentView()
    {
        return $this->hasMany(LmsStudentView::class, 'lmscontent_id', 'id');
    }
}
