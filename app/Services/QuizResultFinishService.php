<?php

namespace App\Services;

use App\Repositories\QuizResultFinishRepository;

class QuizResultFinishService extends BaseService
{
    public function __construct(QuizResultFinishRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get student result exam
     *
     * @param string $userId
     * @return mixed
     */
    public function getStudentResultExam(string $userId)
    {
        return $this->repository->getStudentResultExam($userId);
    }
}
