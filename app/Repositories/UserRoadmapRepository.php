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
}
