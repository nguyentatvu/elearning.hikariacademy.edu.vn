<?php

namespace App\Services;

use App\Repositories\KanjiWritingPracticeRepository;

class KanjiWritingPracticeService extends BaseService
{
    private $kanjiWritingPracticeRepository;

    public function __construct(KanjiWritingPracticeRepository $kanjiWritingPracticeRepository)
    {
        parent::__construct($kanjiWritingPracticeRepository);
        $this->kanjiWritingPracticeRepository = $kanjiWritingPracticeRepository;
    }
}