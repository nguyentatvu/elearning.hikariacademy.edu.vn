<?php

namespace App\Services;

use App\Repositories\RoadmapRepository;

class RoadmapService extends BaseService
{
    public function __construct(RoadmapRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get roadmap selection of list of series id
     *
     * @param array $seriesIdList
     * @return array
     */
    public function getRoadmapSelectionOfSeriesList(array $seriesIdList) {
        return $this->repository->getRoadmapSelectionOfSeriesList($seriesIdList);
    }
}
