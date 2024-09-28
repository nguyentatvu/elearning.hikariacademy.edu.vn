<?php

namespace App\Repositories;

use App\Payment;

class PaymentRepository extends BaseRepository
{

    /**
     * Constructor of class
     */
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }
}
