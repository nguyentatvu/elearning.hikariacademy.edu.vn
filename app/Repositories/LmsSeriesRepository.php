<?php

namespace App\Repositories;

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
        $seriesAndTeachers = $this->model::whereIn('id', $seriesArray)->with('teachers')->get();

        return $seriesAndTeachers;
    }
}