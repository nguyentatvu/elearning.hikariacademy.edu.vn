<?php

namespace App\Services;

use App\Repositories\LmsSeriesTeacherRepository;

class LmsSeriesTeacherService
{
    private $lmsSeriesTeacherRepository;

    public function __construct(LmsSeriesTeacherRepository $lmsSeriesTeacherRepository)
    {
        $this->lmsSeriesTeacherRepository = $lmsSeriesTeacherRepository;
    }

    /**
     * Get teacher ids by series id
     *
     * @param array $seriesId
     * @return mixed
     */
    public function getTeachersFromSeriesIds(array $seriesIds)
    {
        return $this->lmsSeriesTeacherRepository->getTeachersFromSeriesIds($seriesIds);
    }
}