<?php

namespace App\Services;

use App\LmsSeries;
use App\Repositories\LmsExamRepository;

class LmsExamService extends BaseService
{
    public function __construct(LmsExamRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get Exercise content
     *
     * @param int $lessonId
     * @return LmsExam
     */
    public function getExerciseContent(int $lessonId)
    {
        return $this->repository->getExerciseContent($lessonId);
    }
}
