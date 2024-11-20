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
            $monthExtend = optional($payment->paymentMethod)->month_extend ?? 0;
            $title = optional($payment->series)->title ?? '';
            $expiryDate = Carbon::parse(calculateExpiryDate($payment->created_at, $payment->time, $monthExtend));
            $payment->title = $title;

            return $expiryDate->gte(now());
        });

        return $series;
    }
}
