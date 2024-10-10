<?php

namespace App\Services;

use App\Repositories\HandwritingRepository;

class HandwritingService extends BaseService
{
    public function __construct(HandwritingRepository $repository)
    {
        parent::__construct($repository);
    }
}