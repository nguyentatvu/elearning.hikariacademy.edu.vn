<?php

namespace App\Repositories;

use App\LmsContent;

class LmsSeriesRepository extends BaseRepository
{
    /**
     * Get Series with teachers
     *
     * @param array $seriesArray
     * @return Collection
     */
    public function getSeriesWithTeachers(array $seriesArray)
    {
        $seriesAndTeachers = $this->model::whereIn('id', $seriesArray)
            ->with('teachers')
            ->select('lmsseries.*')
            ->selectSub(function ($query) {
                $query->from('lmscontents')
                    ->whereColumn('lmscontents.lmsseries_id', 'lmsseries.id')
                    ->where('lmscontents.delete_status', 0)
                    ->whereNotIn('lmscontents.type', [LmsContent::LESSON_TOPIC, LmsContent::LESSON])
                    ->selectRaw('COUNT(*)');
            }, 'total_lessons')
            ->get();

        return $seriesAndTeachers;
    }

    /**
     * Get series detail
     *
     * @param int $seriesId
     * @return LmsSeries
     */
    public function getSeriesDetail(int $seriesId)
    {
        $seriesDetail = $this->model::where('id', $seriesId)
            ->where('delete_status', 0)
            ->first();

        return $seriesDetail;
    }

    /**
     * Get all series with roadmap
     *
     * @return Collection
     */
    public function getAllWithRoadmapsAndLessons(){
        return $this->model
            ->with([
                'roadmaps', 'lmscontents' => function ($query) {
                    $query->orderBy('stt', 'asc');
                }
            ])
            ->where('delete_status', 0)
            ->get();
    }
}
