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
}