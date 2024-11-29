<?php

namespace App\Repositories;

use App\LmsStudentView;
use Illuminate\Support\Facades\Auth;

class LmsStudentViewRepository extends BaseRepository
{
    /**
     * Get content views by series
     *
     * @return \Illuminate\Support\Collection
     */
    public function getViewsBySeries()
    {
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
            ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
            ->where('lmscontents.lmsseries_id', $seriesId)
            ->where('lms_student_view.users_id', Auth::id())
            ->latest('lms_student_view.created_date')
            ->first();
    }

    /**
     * Get the last finished content of the student
     *
     * @param string $seriesId
     * @return mixed(LmsContent|null)
     */
    public function getLastFinishedContentOfStudent(string $seriesId)
    {
        return $this->model
            ->join('lmscontents', 'lmscontents.id' , '=', 'lms_student_view.lmscontent_id')
            ->where('lmscontents.lmsseries_id', $seriesId)
            ->where('lms_student_view.users_id', Auth::id())
            ->latest('lms_student_view.created_date')
            ->first();
    }

    /**
     * Get the last finished content of the student
     *
     * @param int $seriesId
     * @param int $userId
     * @return mixed(LmsContent|null)
     */
    public function getLastFinishedContentOfStudentAPI(int $seriesId, int $userId)
    {
        return $this->model
            ->join('lmscontents', 'lmscontents.id' , '=', 'lms_student_view.lmscontent_id')
            ->where('lmscontents.lmsseries_id', $seriesId)
            ->where('lms_student_view.users_id', $userId)
            ->latest('lms_student_view.created_date')
            ->first();
    }

    /**
     * Get the view count of a series
     *
     * @param string $seriesId
     * @param string $userId
     * @return int
     */
    public function getViewCountOfSeries(string $seriesId, string $userId)
    {
        return $this->model
            ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
            ->where([
                'lms_student_view.users_id' => $userId,
                'lmscontents.lmsseries_id' => $seriesId,
                'lms_student_view.finish' => LmsStudentView::FINISH,
                'lmscontents.delete_status' => 0
            ])->count();
    }

    public function getCountOfSeriesForUser(string $userId)
    {
        $results = $this->model
            ->select('lmscontents.lmsseries_id', \DB::raw('COUNT(lmscontents.id) as lesson_count'))
            ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
            ->join('lmsseries', 'lmscontents.lmsseries_id', '=', 'lmsseries.id')
            ->where([
                'lms_student_view.users_id' => $userId,
                'lms_student_view.finish' => LmsStudentView::FINISH,
                'lmscontents.delete_status' => 0,
                'lmsseries.type_series' => 0
            ])
            ->groupBy('lmscontents.lmsseries_id')
            ->get();

        return $results->pluck('lesson_count', 'lmsseries_id')->toArray();
    }

    public function getCountOfExamForUser(string $userId)
    {
        $results = $this->model
            ->select('lmscontents.lmsseries_id', \DB::raw('COUNT(lmscontents.id) as lesson_count'))
            ->join('lmscontents', 'lmscontents.id', '=', 'lms_student_view.lmscontent_id')
            ->join('lmsseries', 'lmscontents.lmsseries_id', '=', 'lmsseries.id')
            ->where([
                'lms_student_view.users_id' => $userId,
                'lms_student_view.finish' => LmsStudentView::FINISH,
                'lmscontents.delete_status' => 0,
                'lmsseries.type_series' => 1
            ])
            ->groupBy('lmscontents.lmsseries_id')
            ->get();

        return $results->pluck('lesson_count', 'lmsseries_id')->toArray();
    }
}
