<?php

namespace App\Services;

use App\Repositories\PaymentRepository;

class PaymentService
{
    private $paymentRepo;

    public function __construct(PaymentRepository $paymentRepo)
    {
        $this->paymentRepo = $paymentRepo;
    }

    /**
     * Create data by array attributes
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes) {
        return $this->paymentRepo->create($attributes);
    }
}
