<?php

namespace App\Repositories;

use App\LmsTest;
use Illuminate\Support\Facades\DB;

class LmsTestRepository extends BaseRepository
{
    /**
     * Gets the test content
     *
     * @param int $lessonId
     * @return any
     */
    public function getTestContent(int $lessonId)
    {
        $test = $this->model::where('content_id', $lessonId)
            ->where('delete_status', 0)
            ->whereNotNull('dang')
            ->select(
                'id',
                'dang',
                'cau',
                'mota',
                'dapan',
                'display',
                'luachon1',
                'luachon2',
                'luachon3',
                'luachon4'
            )
            ->get();

        return $test;
    }

    /**
     * Gets the correct answers
     *
     * @param int $lessonId
     * @return mixed
     */
    public function getCorrectAnswers(int $lessonId)
    {
        $correctAnswers = $this->model::where('content_id', $lessonId)
            ->where('delete_status', 0)
            ->get(['id', 'cau', 'diem', 'dapan']);

        return $correctAnswers;
    }
}
