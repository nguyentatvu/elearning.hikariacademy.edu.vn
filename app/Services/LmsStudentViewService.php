<?php

namespace App\Services;

use App\LmsSeries;
use App\Repositories\LmsStudentViewRepository;

class LmsStudentViewService
{
    private $lmsStudentViewRepository;

    public function __construct(LmsStudentViewRepository $lmsStudentViewRepository)
    {
        $this->lmsStudentViewRepository = $lmsStudentViewRepository;
    }
}
