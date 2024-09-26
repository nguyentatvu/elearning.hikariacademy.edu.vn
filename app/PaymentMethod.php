<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentMethod extends Model
{
    protected $table = 'payment_method';

    protected $fillable = [
        'user_id',
        'item_name',
        'orderInfo',
        'amount',
        'requestId',
        'orderId',
        'transId',
        'orderType',
        'payType',
        'type',
        'extraData',
        'responseTime',
        'status',
        'recharge_coin_amount'
    ];

    public const PAYMENT_PENDING = 0;
    public const PAYMENT_SUCCESS = 1;
    public const PAYMENT_FAILED = 2;

    public const PAYMENT_SERIES_TYPE = 0;
    public const PAYMENT_EXAM_SERIES_TYPE = 1;
    public const PAYMENT_RECHARGE_COIN_TYPE = 2;

    public const PAYMENT_MOMO_TYPE = 'momo_wallet';
    public const PAYMENT_VNPAY_TYPE = 'vnpay';
    public const PAYMENT_TRANSFER_TYPE = 'transfer';

    public function getPointByAmount($amout)
    {
        $point = DB::table('payments_amout')->where('amout', $amout)->first()->point;
        return ($point);
    }
    public function getAmountByPoint($point)
    {
        $amout = DB::table('payments_amout')->where('point', $point)->first();
        if (empty($amout)) {
            return 0;
        }
        return $amout->amout;
    }

    /**
     * Get the LmsSeriesCombo that owns the PaymentMethod
     */
    public function lmsSeriesCombo()
    {
        return $this->belongsTo(LmsSeriesCombo::class, 'item_id', 'id');
    }

    /**
     * Get the user that owns the payment method
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
