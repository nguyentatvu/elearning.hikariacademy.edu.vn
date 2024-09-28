<?php

namespace App\Repositories;

class LmsSeriesTeacherRepository extends BaseRepository
{
    /**
     * Get teacher ids by series id
     *
     * @param array $seriesIds
     * @return mixed
     */
    public function getTeachersFromSeriesIds(array $seriesIds)
    {
        $teachers = $this->model::whereIn('lmsseries_id', $seriesIds)
            ->with('teacher:id,name')
            ->get();

        return $teachers;
    }
}