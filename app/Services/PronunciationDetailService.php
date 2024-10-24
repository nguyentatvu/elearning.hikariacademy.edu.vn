<?php

namespace App\Services;

use App\Repositories\PronunciationDetailRepository;

class PronunciationDetailService extends BaseService {
    public function __construct(PronunciationDetailRepository $repository)
    {
        parent::__construct($repository);
    }
}