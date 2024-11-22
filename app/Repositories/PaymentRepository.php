<?php

namespace App\Repositories;

use App\Payment;
use App\PaymentMethod;

class PaymentRepository extends BaseRepository
{

    /**
     * Constructor of class
     */
    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    public function getCurrentCoursesForStudent(int $studentId)
    {
        $payments = $this->model::where('user_id', $studentId)
            ->with('paymentMethod', 'series')
            ->whereHas('paymentMethod', function ($query) {
                $query->where('status', PaymentMethod::PAYMENT_SUCCESS);
            })
            ->select('id', 'user_id', 'item_id', 'payments_method_id', 'time', 'created_at')
            ->orderBy('created_at', 'desc')
            ->groupBy('payments.item_id')
            ->get()
            ->values();

        return $payments;
    }
}
