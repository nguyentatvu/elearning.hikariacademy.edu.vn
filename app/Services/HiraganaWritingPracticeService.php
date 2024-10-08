<?php

namespace App\Services;

use App\Repositories\HiraganaWritingPracticeRepository;

class HiraganaWritingPracticeService extends BaseService
{
    private $hiraganaWritingPracticeRepository;

    public function __construct(HiraganaWritingPracticeRepository $hiraganaWritingPracticeRepository)
    {
        parent::__construct($hiraganaWritingPracticeRepository);
        $this->hiraganaWritingPracticeRepository = $hiraganaWritingPracticeRepository;
    }
}