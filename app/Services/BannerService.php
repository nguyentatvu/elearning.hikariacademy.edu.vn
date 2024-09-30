<?php

namespace App\Services;

use App\Repositories\BannerRepository;

class BannerService extends BaseService
{
    public function __construct(BannerRepository $bannerRepository)
    {
        parent::__construct($bannerRepository);
    }
}
