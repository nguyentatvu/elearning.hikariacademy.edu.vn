<?php

namespace App\Services;

use App\Repositories\LmsSeriesRepository;

class LmsSeriesService
{
    private $lmsSeriesRepository;

    public function __construct(LmsSeriesRepository $lmsSeriesRepository)
    {
        $this->lmsSeriesRepository = $lmsSeriesRepository;
    }

    /**
     * Get Series with teachers
     *
     * @param array $seriesArray
     * @return Collection
     */
    public function getSeriesWithTeachers(array $seriesArray)
    {
        $seriesAndTeachers = $this->lmsSeriesRepository->getSeriesWithTeachers($seriesArray);

        return $seriesAndTeachers;
    }
}