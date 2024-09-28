<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinRechargePackage extends Model
{

    protected $table = 'coin_recharge_packages';

    protected $fillable = [
        'price',
        'coin',
        'bonus_percentage',
        'is_active'
    ];
}
