<?php

namespace App\Repositories;

use App\CoinRechargePackage;
use App\User;

class CoinRechargePackageRepository extends BaseRepository
{

    /**
     * Constructor of class
     */
    public function __construct(CoinRechargePackage $model)
    {
        $this->model = $model;
    }

    /**
     * Get all the data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllActivePackages()
    {
        return $this->model->where('is_active', 1)->orderBy('price')->get();
    }

    /**
     * Get all the data sorted
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSorted() {
        return $this->model->orderBy('price')->get();
    }

    /**
     * Insert or update a coin recharge package matching the attributes, and fill it with values
     *
     * @param array $attributes
     * @param array $values
     * @return bool
     */
    public function updateOrInsert(array $attributes, array $values) {
        return $this->model->updateOrInsert($attributes, $values);
    }
}
