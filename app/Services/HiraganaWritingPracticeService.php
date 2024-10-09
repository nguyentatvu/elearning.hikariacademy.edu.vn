<?php

namespace App\Services;

use App\Repositories\HiraganaWritingPracticeRepository;

class HiraganaWritingPracticeService extends BaseService
{
    public function __construct(HiraganaWritingPracticeRepository $repository)
    {
        parent::__construct($repository);
    }
}