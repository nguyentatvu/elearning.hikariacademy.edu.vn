<?php

namespace App\Services;

use App\Repositories\HandwritingRepository;

class HandwritingService extends BaseService
{
    private $handwritingRepository;

    public function __construct(HandwritingRepository $handwritingRepository)
    {
        parent::__construct($handwritingRepository);
        $this->handwritingRepository = $handwritingRepository;
    }

    /**
     * Get all Handwritings with sorting
     *
     * @param string $column
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithSorting(string $column = 'updated_at', string $order = 'desc')
    {
        return $this->handwritingRepository->getAllWithSorting($column, $order);
    }
}