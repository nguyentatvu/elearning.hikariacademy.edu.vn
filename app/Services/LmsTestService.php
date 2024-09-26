<?php

namespace App\Services;

use App\Repositories\LmsTestRepository;

class LmsTestService
{
    private $lmsTestRepository;

    public function __construct(LmsTestRepository $lmsTestRepository)
    {
        $this->lmsTestRepository = $lmsTestRepository;
    }

    /**
     * Evaluates the exam
     *
     * @param int $lessonId
     * @param array $submittedAnswers
     * @return mixed
     */
    public function evaluateTest(int $lessonId, array $submittedAnswers)
    {
        return $this->calculatePoint($lessonId, $submittedAnswers);
    }

    /**
     * Calculates the total point
     *
     * @param int $lessonId
     * @param array $submittedAnswers
     * @return int
     */
    protected function calculatePoint(int $lessonId, array $submittedAnswers)
    {
        $correctAnswers = $this->lmsTestRepository->getCorrectAnswers($lessonId);

        if (empty($correctAnswers)) {
            return null;
        }

        $totalQuestion = $correctAnswers->count();
        $totalPoint = 0;
        $totalCorrectAnswers = 0;
        $maxPoint = 0;

        foreach ($correctAnswers as $correctAnswer) {
            $answer = $correctAnswer->cau;
            $maxPoint += $correctAnswer->diem;

            if (isset($submittedAnswers[$answer]) && $submittedAnswers[$answer] == $correctAnswer->dapan) {
                $totalPoint += $correctAnswer->diem;
                $totalCorrectAnswers += 1;
            }
        }

        $isPassed = $this->evaluatePassFail($totalPoint, $maxPoint);

        return [
            'total_point' => $totalPoint,
            'total_correct_answers' => $totalCorrectAnswers,
            'total_question' => $totalQuestion,
            'is_passed' => $isPassed,
        ];
    }

    /**
     * Evaluates the pass fail
     *
     * @param int $totalPoint
     * @param int $maxPoint
     * @return bool
     */
    protected function evaluatePassFail(int $totalPoint, int $maxPoint)
    {
        $isPassed = true;
        $percentagePoint = ((float) ($totalPoint / $maxPoint)) * 100;

        if ($percentagePoint < 65) {
            $isPassed = false;
        }

        return $isPassed;
    }
}
