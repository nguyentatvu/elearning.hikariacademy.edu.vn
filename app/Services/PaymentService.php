<?php

namespace App\Services;

use App\Repositories\PaymentRepository;
use Carbon\Carbon;

class PaymentService extends BaseService
{
    public function __construct(PaymentRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getCurrentCoursesForStudent(int $studentId)
    {
        $payments = $this->repository->getCurrentCoursesForStudent($studentId);

        $series = $payments->filter(function ($payment) {
            $expiryDate = Carbon::parse(calculateExpiryDate($payment->created_at, $payment->time, $payment->paymentMethod->month_extend));
            $payment->title = $payment->series->title;

            return $expiryDate->gte(now());
        });

        return $series;
    }
}
