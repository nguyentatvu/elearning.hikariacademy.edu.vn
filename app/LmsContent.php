<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    public const HANDWRITING = 11;
    public const PRONUNCIATION_ASSESSMENT = 12;

    public const TRIAL_TYPE = 1;
    public const PURCHASE_TYPE = 0;

    public const ACTIVE = 0;
    public const DELETED = 1;

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
     * Relationship with child contents
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childContents()
    {
        return $this->hasMany(LmsContent::class, 'parent_id', 'id')
            ->where('delete_status', 0);
    }

    /**
     * Relationship with parent contents
     */
    public function parentContent() {
        return $this->belongsTo(LmsContent::class, 'parent_id', 'id');
    }

    /**
     * Define the inverse relationship to LmsSeries
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lmsseries()
    {
        $slug = request()->route('slug');

        return $this->belongsTo(LmsSeries::class, 'lmsseries_id')
            ->where('slug', $slug)
            ->where('delete_status', 0);
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

    /**
     * Relationship with LmsStudentView of current user (return null if guest)
     */
    public function currentLmsStudentView() {
        if (!Auth::check()) {
            return null;
        }

        return $this->hasOne(LmsStudentView::class, 'lmscontent_id', 'id')
            ->where('users_id', Auth::id());
    }

    /**
     * Scope a query to only include total lessons
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $seriesId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTotalLessons($query, $seriesId)
    {
        return $query->where('lmsseries_id', $seriesId)
            ->where('delete_status', 0)
            ->whereNotIn('type', [self::LESSON_TOPIC, self::LESSON]);
    }

    /**
     * Check blocked content
     *
     * @param array $testContentResult
     * @return array
     */
    public function checkBlockedContent(array $testContentResult) {
        $isContentBlocked = false;
        $incompleteTestTitle = '';
        $checkTestContentExists = count($testContentResult) > 0;
        $firstTestContentOrder = array_keys($testContentResult)[0];

        if (!$this->stt <= $testContentResult[$firstTestContentOrder]) {
            foreach ($testContentResult as $testContentOrder => $testResult) {
                if ($this->stt > $testContentOrder) {
                    if (!$testResult['is_passed']) {
                        $isContentBlocked = true;
                        $incompleteTestTitle = $this->getTitleOfNearestTestContent($this->stt, $testContentResult);
                        break;
                    }
                } else {
                    break;
                }
            }
        }

        return [
            'isContentBlocked' => $isContentBlocked && $checkTestContentExists,
            'incompleteTestTitle' => $incompleteTestTitle
        ];
    }

    /**
     * Get title of test content
     *
     * @param int $currentContentOrder
     * @param array $testContentResult
     * @return string
     */
    private function getTitleOfNearestTestContent($currentContentOrder, $testContentResult) {
        $title = '';

        foreach ($testContentResult as $testContentOrder => $testResult) {
            if ($currentContentOrder > $testContentOrder) {
                $title = $testResult['title'];
            }
        }

        return $title;
    }
}
