<?php

namespace App\Services;

use App\Repositories\CoinRechargePackageRepository;

class CoinRechargePackageService
{
    private $coinRechargeRepo;

    public function __construct(CoinRechargePackageRepository $coinRechargeRepo)
    {
        $this->coinRechargeRepo = $coinRechargeRepo;
    }

    /**
     * Get all active packages
     *
     * @return Collection
     */
    public function getAllActivePackages()
    {
        $activePackages = $this->coinRechargeRepo->getAllActivePackages();
        $activePackages = $activePackages->map(function ($item) {
            $item->formattedPrice = formatCurrencyVND($item->price, 2);
            $item->totalCoin = $item->coin + (int) ($item->coin * ($item->bonus_percentage / 100));

            return $item;
        });

        return $activePackages;
    }

    /**
     * Get coin recharge package by id
     *
     * @param int $price
     * @return mixed(Model|null)
     */
    public function findByPrice(int $price) {
        return $this->coinRechargeRepo->getByCondition('price', $price);
    }

    /**
     * Get all the data sorted
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSorted() {
        return $this->coinRechargeRepo->getAllSorted();
    }

    /**
     * Insert or update a coin recharge package matching the attributes, and fill it with values
     *
     * @param array $attributes
     * @param array $values
     * @return bool
     */
    public function updateOrInsert(array $attributes, array $values)
    {
        return $this->coinRechargeRepo->updateOrInsert($attributes, $values);
    }

    /**
     * Get coin recharge package by Id
     *
     * @param string $id
     * @return mixed(Model|Null)
     */
    public function findById(int $id)
    {
        return $this->coinRechargeRepo->findById($id);
    }
}
