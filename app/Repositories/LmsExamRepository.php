<?php

namespace App\Repositories;

use App\LmsExam;
use Illuminate\Support\Facades\DB;

class LmsExamRepository extends BaseRepository
{
    /**
     * Get Exercise content
     *
     * @param int $lessonId
     * @return LmsExam
     */
    public function getExerciseContent(int $lessonId)
    {
        $exercises = $this->model::where('content_id', $lessonId)
            ->where('delete_status', 0)
            ->whereNotNull('dang')
            ->select(
                'id',
                'dang',
                'cau',
                'mota',
                'dapan',
                'label',
                'luachon1',
                'luachon2',
                'luachon3',
                'luachon4'
            )
            ->get();

        return $exercises;
    }
}
