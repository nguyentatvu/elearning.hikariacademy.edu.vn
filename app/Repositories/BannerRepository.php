<?php

namespace App\Repositories;

class BannerRepository extends BaseRepository
{
    /**
     * Get Banners By Position
     *
     * @param string $position
     * @return mixed
     */
    public function getBannersByPosition(string $position)
    {
        $banners = $this->model::where('position', 'like', "%{$position}%")
            ->get();

        return $banners;
    }
}
