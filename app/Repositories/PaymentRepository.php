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

    public function getCurrentCoursesForStudent(int $studentId)
    {
        $payments = $this->model::where('user_id', $studentId)
            ->with('paymentMethod', 'series')
            ->select('id', 'user_id', 'item_id', 'payments_method_id', 'time', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('item_id')
            ->map(function ($group) {
                return $group->first();
            })
            ->values();

        return $payments;
    }
}
