<?php

namespace App\Repositories;

use App\WeeklyLeaderboard;
use Carbon\Carbon;

class WeeklyLeaderboardRepository extends BaseRepository
{

    /**
     * Constructor of class
     */
    public function __construct(WeeklyLeaderboard $model)
    {
        $this->model = $model;
    }

    /**
     * Get top earned point user rankings
     *
     * @return Collection
     */
    public function getTopRankings()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        return $this->model->with('user')
            ->where('week_start', $startOfWeek)
            ->orderBy('rank')
            ->limit(NUMBER_OF_STUDENTS_ON_THE_LEADERBOARD)
            ->get();
    }

    /**
     * Get user rank
     * @param int $userId
     * @return object
     */
    public function getUserRank(int $userId)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        return $this->model->with('user')
            ->where('week_start', $startOfWeek)
            ->where('user_id', $userId)
            ->first();
    }
}
