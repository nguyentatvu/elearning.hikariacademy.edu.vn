<?php

namespace App\Services;

use App\Repositories\WeeklyLeaderboardRepository;

class WeeklyLeaderboardService extends BaseService
{
    public function __construct(WeeklyLeaderboardRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get top earned point user rankings
     *
     * @return Collection
     */
    public function getTopRankings() {
        return $this->repository->getTopRankings();
    }

    /**
     * Get user rank
     * @param int $userId
     * @return object
     */
    public function getUserRank(int $userId) {
        return $this->repository->getUserRank($userId);
    }
}
