<?php

namespace App\Services;

use App\Repositories\KanjiWritingPracticeRepository;

class KanjiWritingPracticeService extends BaseService
{
    public function __construct(KanjiWritingPracticeRepository $repository)
    {
        parent::__construct($repository);
    }
}