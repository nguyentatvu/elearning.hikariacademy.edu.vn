<?php

namespace App\Services;

use App\LmsSeries;
use App\Repositories\PaymentMethodRepository;
use Illuminate\Support\Facades\Auth;

class PaymentMethodService extends BaseService
{
    public function __construct(PaymentMethodRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Checks validity of series combo
     *
     * @param int $seriesComboId
     * @param int $userId
     * @return bool
     */
    public function checkSerieValidity(int $userId, int $seriesComboId)
    {
        return $this->repository->checkSerieValidity($userId, $seriesComboId);
    }

    /**
     * Get all coin order payments
     *
     * @return mixed(\Illuminate\Database\Eloquent\Collection|null)
     */
    public function getAllCoinTransferOrders() {
        return $this->repository->getAllCoinTransferOrders();
    }

    /**
     * Check if user has pending series payment
     *
     * @return bool
     */
    public function checkPendingSeriesPayment() {
        return $this->repository->checkPendingSeriesPayment();
    }

    /**
     * Check if user has pending series transfering order
     *
     * @return bool
     */
    public function checkPendingSeriesTransferOrder()
    {
        return $this->repository->checkPendingSeriesTransferOrder();
    }

    /**
     * Check if user has pending coin payment
     *
     * @return bool
     */
    public function checkPendingCoinPayment() {
        return $this->repository->checkPendingCoinPayment();
    }

    /**
     * Check if user has pending coin transfering order
     *
     * @return bool
     */
    public function checkPendingCoinTransferOrder()
    {
        return $this->repository->checkPendingCoinTransferOrder();
    }

    /**
     * Get all overdue series payment
     *
     * @return mixed(\Illuminate\Database\Eloquent\Collection|null)
     */
    public function getAllOverdueSeriesPayment() {
        return $this->repository->getAllOverdueSeriesPayment();
    }

    /**
     * Get all overdue coin payment
     *
     * @return mixed(\Illuminate\Database\Eloquent\Collection|null)
     */
    public function getAllOverdueCoinPayment() {
        return $this->repository->getAllOverdueCoinPayment();
    }

        /**
     * Get my all payments
     *
     * @param string $userId
     * @return mixed
     */
    public function getAllMyPayments(string $userId) {
        return $this->repository->getAllMyPayments($userId);
    }

    /**
     * Get latest series purchased time
     *
     * @param string $userId
     * @param string $seriesComboId
     * @return PaymentMethod
     */
    public function getLatestPurchasedSeriesTime(string $userId, string $seriesComboId) {
        $latestPurchasedSeries = $this->repository->getLatestPurchasedSeries($userId, $seriesComboId);

        if ($latestPurchasedSeries) {
            return $latestPurchasedSeries->responseTime;
        }

        return null;
    }
}
