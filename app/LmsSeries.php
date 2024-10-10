<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LmsSeries extends Model
{
    protected $table = 'lmsseries';

    public const COURSE_AND_EXAM = -1;
    public const COURSE = 0;
    public const EXAM = 1;

    public static function getRecordWithSlug($slug)
    {
        return LmsSeries::where('slug', '=', $slug)->first();
    }

    /**
     * This method lists all the items available in selected series
     * @return [type] [description]
     */
    public function getContents()
    {
        return DB::table('lmsseries_data')
            ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
            ->where('lmsseries_id', '=', $this->id)->get();
    }


    public static function getFreeSeries($limit = 0)
    {
        $records  = LmsSeries::where('show_in_front', 1)
            ->groupby('lms_category_id')
            ->inRandomOrder()
            ->pluck('lms_category_id')
            ->toArray();
        if ($limit > 0) {

            $lms_cats  = LmsCategory::whereIn('id', $records)->limit(6)->get();
        } else {

            $lms_cats  = LmsCategory::whereIn('id', $records)->get();
        }
        return $lms_cats;
    }


    public function viewContents($limit = '')
    {

        $contents_data   = LmsSeriesData::where('lmsseries_id', $this->id)
            ->pluck('lmscontent_id')
            ->toArray();

        if ($contents_data) {

            if ($limit != '') {

                $contents  = LmsContent::whereIn('id', $contents_data)->paginate($limit);
            } else {
                $contents  = LmsContent::whereIn('id', $contents_data)->get();
            }

            if ($contents)
                return $contents;

            return FALSE;
        }

        return FALSE;
    }

    /**
     * Define the relationship to LmsContent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lmscontents()
    {
        return $this->hasMany(LmsContent::class, 'lmsseries_id');
    }

    /**
     * Relationship with User model
     * A series can have many teachers.
     */
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'lmsseries_teacher', 'lmsseries_id', 'teacher_id');
    }
}
