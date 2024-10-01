<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class LmsStudentViewRepository extends BaseRepository
{
    /**
     * Get content views by series
     *
     * @return \Illuminate\Support\Collection
     */
    public function getViewsBySeries() {
        if (!Auth::check()) {
            return collect();
        }

        return $this->model->with('lmsContent.lmsseries')
            ->where('users_id', Auth::id())->get();
    }


    /**
     * Get the last viewed content of the student
     *
     * @param string $seriesId
     * @return mixed(LmsContent|null)
     */
    public function getLastViewedContentOfStudent(string $seriesId)
    {
        return $this->model
            ->join('lmscontents', 'lmscontents.id' , '=', 'lms_student_view.lmscontent_id')
            ->where('lmscontents.lmsseries_id', $seriesId)
            ->where('lms_student_view.users_id', Auth::id())
            ->latest('lms_student_view.created_date')
            ->first();
    }
}
