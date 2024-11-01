<?php

namespace App\Services;

use App\Repositories\UserRoadmapRepository;

class UserRoadmapService extends BaseService
{
    public function __construct(UserRoadmapRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Check user roadmap selection
     *
     * @param string $userId
     * @param string $seriesId
     *
     * @return boolean
     */
    public function isRoadmapChosenForSeries(string $userId, string $seriesId) {
        return $this->repository->isRoadmapChosenForSeries($userId, $seriesId);
    }

    /**
     * Get user chosen roadmap list
     *
     * @param string $userId
     * @param string $seriesId
     *
     * @return boolean
     */
    public function userChosenRoadmapList(string $userId) {
        $array = $this->repository->userChosenRoadmapList($userId)->toArray();

        $result = array_reduce($array, function ($carry, $item) {
            $carry[$item['id']] = $item['duration_months'] !== null;
            return $carry;
        }, []);

        return $result;
    }

    /**
     * Get user chosen roadmap list
     *
     * @param string $userId
     * @param string $seriesId
     * @param string $durationMonths
     *
     * @return void
     */
    public function saveUserRoadmap(string $userId, string $seriesId, string $durationMonths) {
        $userRoadmap = $this->repository->getByConditions([
            'user_id' => $userId,
            'lmsseries_id' => $seriesId
        ]);

        if (empty($userRoadmap)) {
            return;
        }

        $userRoadmap->update(['duration_months' => $durationMonths]);
    }
}
