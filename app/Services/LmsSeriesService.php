<?php

namespace App\Services;

use App\Repositories\LmsSeriesRepository;

class LmsSeriesService extends BaseService
{
    public function __construct(LmsSeriesRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get Series with teachers
     *
     * @param array $seriesArray
     * @return Collection
     */
    public function getSeriesWithTeachers(array $seriesArray)
    {
        $seriesAndTeachers = $this->repository->getSeriesWithTeachers($seriesArray);

        return $seriesAndTeachers;
    }
}