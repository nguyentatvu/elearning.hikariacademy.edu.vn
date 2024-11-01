<?php

namespace App\Services;

use App\Repositories\BannerRepository;

class BannerService extends BaseService
{
    public function __construct(BannerRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get Banners By Position
     *
     * @param string $position
     * @return mixed
     */
    public function getBannersByPosition(string $position)
    {
        return $this->repository->getBannersByPosition($position);
    }
}
