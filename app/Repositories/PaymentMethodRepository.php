<?php

namespace App\Repositories;

use App\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentMethodRepository extends BaseRepository
{
    /**
     * Checks validity of series combo
     *
     * @param int $seriesComboId
     * @param int $userId
     * @return bool
     */
    public function checkSerieValidity(int $userId, int $seriesComboId)
    {
        $isValid = $this->model->where('item_id', $seriesComboId)
            ->where('user_id', $userId)
            ->where('status', 1)
            ->whereHas('lmsSeriesCombo', function ($query) {
                $query->whereRaw(
                    'DATE_ADD(responseTime, INTERVAL IF(time = 0, 90, IF(time = 1, 180, 365)) DAY) > NOW()'
                );
            })
            ->exists();

        return $isValid;
    }

    /**
     * Get payment method by not null condition
     *
     * @return \Illuminate\Database\Eloquent\Collection | null
     */
    public function getAllCoinTransferOrders()
    {.
        return $this->model->with('user')
            ->whereNotNull('recharge_coin_amount')
            ->where('orderType', 'transfer')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if user has pending series payment
     *
     * @return bool
     */
    public function checkPendingSeriesPayment() {
        return $this->model
            ->where([
                'status' => PaymentMethod::PAYMENT_PENDING,
                'user_id' => Auth::user()->id
            ])
            ->whereNull('recharge_coin_amount')
            ->where('orderType', '<>', 'transfer')
            ->exists();
    }

    /**
     * Check if user has pending series transfering order
     *
     * @return bool
     */
    public function checkPendingSeriesTransferOrder() {
        return $this->model
            ->where([
                'status' => PaymentMethod::PAYMENT_PENDING,
                'user_id' => Auth::user()->id
            ])
            ->whereNull('recharge_coin_amount')
            ->where('orderType', 'transfer')
            ->exists();
    }

    /**
     * Check if user has pending coin payment
     *
     * @return bool
     */
    public function checkPendingCoinPayment()
    {
        return $this->model
            ->where([
                'status' => PaymentMethod::PAYMENT_PENDING,
                'user_id' => Auth::user()->id
            ])
            ->whereNotNull('recharge_coin_amount')
            ->where('orderType', '<>', 'transfer')
            ->exists();
    }

    /**
     * Check if user has pending coin transfering order
     *
     * @return bool
     */
    public function checkPendingCoinTransferOrder()
    {
        return $this->model
            ->where([
                'status' => PaymentMethod::PAYMENT_PENDING,
                'user_id' => Auth::user()->id
            ])
            ->whereNotNull('recharge_coin_amount')
            ->where('orderType', 'transfer')
            ->exists();
    }

    /**
     * Get all overdue series payment
     *
     * @return mixed(\Illuminate\Database\Eloquent\Collection|null)
     */
    public function getAllOverdueSeriesPayment() {
        $minValidMinutes = config('constant.payment.min_valid_time');
        return $this->model
            ->where([
                'status' => PaymentMethod::PAYMENT_PENDING,
                'user_id' => Auth::user()->id
            ])
            ->whereNull('recharge_coin_amount')
            ->where('orderType', '<>', 'transfer')
            ->where('created_at', '<', Carbon::now()->subMinutes($minValidMinutes))
            ->get();
    }
    /**
     * Get all overdue coin payment
     *
     * @return mixed(\Illuminate\Database\Eloquent\Collection|null)
     */
    public function getAllOverdueCoinPayment() {
        $minValidMinutes = config('constant.payment.min_valid_time');
        return $this->model
            ->where([
                'status' => PaymentMethod::PAYMENT_PENDING,
                'user_id' => Auth::user()->id
            ])
            ->whereNotNull('recharge_coin_amount')
            ->where('orderType', '<>', 'transfer')
            ->where('created_at', '<', Carbon::now()->subMinutes($minValidMinutes))
            ->get();
    }
}
