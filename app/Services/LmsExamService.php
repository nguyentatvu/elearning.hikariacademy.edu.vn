<?php

namespace App\Services;

use App\LmsSeries;
use App\Repositories\LmsExamRepository;

class LmsExamService
{
    private $lmsExamRepository;

    public function __construct(LmsExamRepository $lmsExamRepository)
    {
        $this->lmsExamRepository = $lmsExamRepository;
    }

    /**
     * Get Exercise content
     *
     * @param int $lessonId
     * @return LmsExam
     */
    public function getExerciseContent(int $lessonId)
    {
        return $this->lmsExamRepository->getExerciseContent($lessonId);
    }
}
