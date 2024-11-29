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
                    "DATE_ADD(
                        responseTime,
                        INTERVAL
                        (IF(time = 0, 90, IF(time = 1, 180, 365)) +
                        CASE
                            WHEN month_extend = 0 THEN 0
                            WHEN month_extend = 1 THEN 30
                            WHEN month_extend = 3 THEN 90
                            WHEN month_extend = 6 THEN 180
                            WHEN month_extend = 12 THEN 365
                            ELSE 0
                        END)
                        DAY
                    ) > NOW()"
                );
            })
            ->exists();

        return $isValid;
    }

    /**
     * Get latest series purchased
     *
     * @param string $userId
     * @param string $seriesComboId
     * @return PaymentMethod
     */
    public function getLatestPurchasedSeries(string $userId, string $seriesComboId) {
        return $this->model
            ->where('item_id', $seriesComboId)
            ->where('user_id', $userId)
            ->where('status', 1)
            ->orderBy('responseTime', 'desc')
            ->first();
    }

    /**
     * Get payment method by not null condition
     *
     * @return \Illuminate\Database\Eloquent\Collection | null
     */
    public function getAllCoinTransferOrders()
    {
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
                'user_id' => Auth::user()->id
            ])
            ->whereIn('status', [PaymentMethod::PAYMENT_PENDING, PaymentMethod::PAYMENT_PENDING_OS])
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
                'user_id' => Auth::user()->id
            ])
            ->whereIn('status', [PaymentMethod::PAYMENT_PENDING, PaymentMethod::PAYMENT_PENDING_OS])
            ->whereNull('recharge_coin_amount')
            ->where('orderType', 'transfer')
            ->exists();
    }

    /**
     * Check if user has a specific pending series payment
     *
     * @param string $seriesComboId
     * @return bool
     */
    public function checkPendingSeriesPaymentOf(string $seriesComboId) {
        return $this->model
            ->where([
                'user_id' => Auth::user()->id,
                'item_id' => $seriesComboId
            ])
            ->whereIn('status', [PaymentMethod::PAYMENT_PENDING, PaymentMethod::PAYMENT_PENDING_OS])
            ->whereNull('recharge_coin_amount')
            ->where('orderType', '<>', 'transfer')
            ->exists();
    }

    /**
     * Check if user has a specific pending series transfering order
     *
     * @param string $seriesComboId
     * @return bool
     */
    public function checkPendingSeriesTransferOrderOf(string $seriesComboId) {
        return $this->model
            ->where([
                'user_id' => Auth::user()->id,
                'item_id' => $seriesComboId
            ])
            ->whereIn('status', [PaymentMethod::PAYMENT_PENDING, PaymentMethod::PAYMENT_PENDING_OS])
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
                'user_id' => Auth::user()->id
            ])
            ->whereIn('status', [PaymentMethod::PAYMENT_PENDING, PaymentMethod::PAYMENT_PENDING_OS])
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
                'user_id' => Auth::user()->id
            ])
            ->whereIn('status', [PaymentMethod::PAYMENT_PENDING, PaymentMethod::PAYMENT_PENDING_OS])
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
                'user_id' => Auth::user()->id
            ])
            ->whereIn('status', [PaymentMethod::PAYMENT_PENDING, PaymentMethod::PAYMENT_PENDING_OS])
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
                'user_id' => Auth::user()->id
            ])
            ->whereIn('status', [PaymentMethod::PAYMENT_PENDING, PaymentMethod::PAYMENT_PENDING_OS])
            ->whereNotNull('recharge_coin_amount')
            ->where('orderType', '<>', 'transfer')
            ->where('created_at', '<', Carbon::now()->subMinutes($minValidMinutes))
            ->get();
    }

    /**
     * Get my all payments
     *
     * @param string $userId
     * @return mixed
     */
    public function getAllMyPayments(string $userId) {
        return $this->model
            ->join('lmsseries_combo', 'payment_method.item_id', '=', 'lmsseries_combo.id')
            ->select(
                'payment_method.id as payment_method_id',
                'lmsseries_combo.*',
                'payment_method.created_at',
                'payment_method.status',
                'payment_method.orderType',
                'payment_method.status',
                'payment_method.created_at',
                'lmsseries_combo.cost',
                'lmsseries_combo.title'
            )
            ->where([
                ['payment_method.user_id', $userId],
                ['lmsseries_combo.delete_status', 0],
            ])
            ->orderBy('payment_method.created_at', 'desc')
            ->paginate(15);
    }
}
