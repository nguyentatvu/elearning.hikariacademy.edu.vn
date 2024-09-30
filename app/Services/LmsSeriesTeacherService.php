<?php

namespace App\Services;

use App\Repositories\LmsSeriesTeacherRepository;

class LmsSeriesTeacherService extends BaseService
{
    public function __construct(LmsSeriesTeacherRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get teacher ids by series id
     *
     * @param array $seriesId
     * @return mixed
     */
    public function getTeachersFromSeriesIds(array $seriesIds)
    {
        return $this->repository->getTeachersFromSeriesIds($seriesIds);
    }
}