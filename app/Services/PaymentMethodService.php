<?php

namespace App\Services;

use App\LmsSeries;
use App\Repositories\PaymentMethodRepository;
use Illuminate\Support\Facades\Auth;

class PaymentMethodService
{
    private $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
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
        return $this->paymentMethodRepository->checkSerieValidity($userId, $seriesComboId);
    }

    /**
     * Create payment method by array attributes
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->paymentMethodRepository->create($attributes);
    }

    /**
     * Get payment method by condition
     *
     * @param string $field
     * @param mixed $value
     *
     * @return mixed(Model|null)
     */
    public function getByCondition($field, $value)
    {
        return $this->paymentMethodRepository->getByCondition($field, $value);
    }

    /**
     * Get payment method by condition
     *
     * @param array $conditions
     *
     * @return mixed(Model|null)
     */
    public function getByConditions(array $conditions)
    {
        return $this->paymentMethodRepository->getByConditions($conditions);
    }

    /**
     * Get all coin order payments
     *
     * @return mixed(\Illuminate\Database\Eloquent\Collection|null)
     */
    public function getAllCoinTransferOrders() {
        return $this->paymentMethodRepository->getAllCoinTransferOrders();
    }

    /**
     * Get payment method id
     *
     * @param string $id
     * @return mixed(Model|Null)
     */
    public function findById(string $id) {
        return $this->paymentMethodRepository->findById($id);
    }

    /**
     * Check if user has pending series payment
     *
     * @return bool
     */
    public function checkPendingSeriesPayment() {
        return $this->paymentMethodRepository->checkPendingSeriesPayment();
    }

    /**
     * Check if user has pending series transfering order
     *
     * @return bool
     */
    public function checkPendingSeriesTransferOrder()
    {
        return $this->paymentMethodRepository->checkPendingSeriesTransferOrder();
    }

    /**
     * Check if user has pending coin payment
     *
     * @return bool
     */
    public function checkPendingCoinPayment() {
        return $this->paymentMethodRepository->checkPendingCoinPayment();
    }

    /**
     * Check if user has pending coin transfering order
     *
     * @return bool
     */
    public function checkPendingCoinTransferOrder()
    {
        return $this->paymentMethodRepository->checkPendingCoinTransferOrder();
    }

    /**
     * Get all overdue series payment
     *
     * @return mixed(\Illuminate\Database\Eloquent\Collection|null)
     */
    public function getAllOverdueSeriesPayment() {
        return $this->paymentMethodRepository->getAllOverdueSeriesPayment();
    }

    /**
     * Get all overdue coin payment
     *
     * @return mixed(\Illuminate\Database\Eloquent\Collection|null)
     */
    public function getAllOverdueCoinPayment() {
        return $this->paymentMethodRepository->getAllOverdueCoinPayment();
    }
}
