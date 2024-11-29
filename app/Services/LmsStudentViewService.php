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


    /**
     * Get the last finished content of the student
     *
     * @param string $seriesId
     * @return mixed(LmsContent|null)
     */
    public function getLastFinishedContentOfStudent(string $seriesId)
    {
        return $this->repository->getLastFinishedContentOfStudent($seriesId);
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
        return $this->repository->getLastFinishedContentOfStudentAPI($seriesId, $userId);
    }

    /**
     * Get the view count of a series
     *
     * @param string $seriesId
     * @param string $userId
     * @return int
     */
    public function getViewCountOfSeries(string $seriesId, string $userId) {
        return $this->repository->getViewCountOfSeries($seriesId, $userId);
    }

    /**
     * Get the view count of a series for user
     *
     * @param string $userId
     * @return int
     */
    public function getCountOfSeriesForUser(string $userId) {
        return $this->repository->getCountOfSeriesForUser($userId);
    }

    /**
     * Get the view count of a series exam for user
     *
     * @param string $userId
     * @return int
     */
    public function getCountOfExamForUser(string $userId) {
        return $this->repository->getCountOfExamForUser($userId);
    }
}
