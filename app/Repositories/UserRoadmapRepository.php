<?php

namespace App\Repositories;

use App\UserRoadmap;
use Illuminate\Support\Facades\DB;

class UserRoadmapRepository extends BaseRepository
{
    /**
     * Constructor of class
     */
    public function __construct(UserRoadmap $model)
    {
        $this->model = $model;
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
        return $this->model
            ->where([
                'user_id' => $userId,
                'lmsseries_id' => $seriesId
            ])
            ->whereNotNull('roadmap_id')
            ->exists();
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
        return $this->model
            ->select('user_roadmaps.user_id', 'user_roadmaps.duration_months', 'lmsseries.id')
            ->rightJoin('lmsseries', function ($join) use ($userId) {
                $join->on('lmsseries.id', '=', 'user_roadmaps.lmsseries_id')
                    ->where('user_roadmaps.user_id', $userId);
            })
            ->get();
    }

    /**
     * Get user chosen roadmap list and information
     *
     * @param string $userId
     * @param string $seriesId
     *
     * @return boolean
     */
    public function getUserChosenRoadmap(string $userId) {
        return $this->model
            ->where('user_roadmaps.user_id', $userId)
            ->leftJoin('roadmaps', function ($join) {
                $join->on('user_roadmaps.lmsseries_id', '=', 'roadmaps.lmsseries_id')
                     ->on('user_roadmaps.duration_months', '=', 'roadmaps.duration_months');
            })
            ->select('user_roadmaps.*', 'roadmaps.contents', 'roadmaps.description')
            ->get();
    }
}
