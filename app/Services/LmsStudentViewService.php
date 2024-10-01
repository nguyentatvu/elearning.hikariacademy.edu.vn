<?php

namespace App\Services;

use App\LmsSeries;
use App\Repositories\LmsStudentViewRepository;

class LmsStudentViewService extends BaseService
{
    public function __construct(LmsStudentViewRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get content views by series
     *
     * @return \Illuminate\Support\Collection
     */
    public function getViewsBySeries() {
        return $this->repository->getViewsBySeries();
    }

    /**
     * Get the last viewed content of the student
     *
     * @param string $seriesId
     * @return mixed(LmsContent|null)
     */
    public function getLastViewedContentOfStudent(string $seriesId) {
        return $this->repository->getLastViewedContentOfStudent($seriesId);
    }
}
