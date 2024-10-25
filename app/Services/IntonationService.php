<?php

namespace App\Services;

use App\Repositories\IntonationRepository;

class IntonationService extends BaseService
{
    public function __construct(IntonationRepository $repository)
    {
        parent::__construct($repository);
    }
}