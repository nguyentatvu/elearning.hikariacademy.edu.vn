<?php

namespace App\Repositories;

use App\QuizResultfinish;

class QuizResultFinishRepository extends BaseRepository
{
    public function __construct(QuizResultfinish $model)
    {
        $this->model = $model;
    }

    /**
     * Get student result exam
     *
     * @param string $userId
     * param boolean $isPaginated
     * @return mixed
     */
    public function getStudentResultExam(string $userId, $isPaginated) {
        $results = $this->model
            ->join('examseries', 'examseries.id', '=', 'quizresultfinish.examseri_id')
            ->where('quizresultfinish.user_id', '=', $userId)
            ->orderBy('quizresultfinish.created_at', 'desc')
            ->select(
                'examseries.title',
                'examseries.category_id',
                'quizresultfinish.id',
                'quizresultfinish.created_at',
                'quizresultfinish.finish',
                'quizresultfinish.quiz_1_total',
                'quizresultfinish.quiz_2_total',
                'quizresultfinish.quiz_3_total',
                'quizresultfinish.total_marks',
                'quizresultfinish.status'
            );

        if ($isPaginated) {
            return $results->paginate(10);
        }

        return $results->get();
    }
}
