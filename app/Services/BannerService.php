<?php

namespace App\Services;

use App\Repositories\BannerRepository;

class BannerService
{
    private $bannerRepository;

    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }
}
