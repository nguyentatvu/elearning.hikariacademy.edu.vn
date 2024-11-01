<?php

namespace App\Repositories;

use App\Roadmap;

class RoadmapRepository extends BaseRepository
{
    /**
     * Constructor of class
     */
    public function __construct(Roadmap $model)
    {
        $this->model = $model;
    }

    /**
     * Get roadmap selection of list of series id
     *
     * @param array $seriesIdList
     * @return array
     */
    public function getRoadmapSelectionOfSeriesList(array $seriesIdList) {
        $roadmapList = $this->model
            ->select('duration_months', 'lmsseries_id', 'contents')
            ->whereIn('lmsseries_id', $seriesIdList)
            ->get();

        return $roadmapList->groupBy('lmsseries_id')->map(function ($roadmapGroup) {
            return $roadmapGroup->sortBy('duration_month')->values();
        })->toArray();
    }
}
