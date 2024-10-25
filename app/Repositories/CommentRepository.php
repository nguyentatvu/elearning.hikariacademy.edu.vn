<?php

namespace App\Repositories;

use App\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{

    /**
     * Constructor of class
     */
    public function __construct(Comment $model)
    {
        $this->model = $model;
    }

    /**
     * Get student comments
     *
     * @param string $studentId
     * @return \Illuminate\Support\Collection
     */
    public function getStudentComments(string $studentId)
    {
        DB::statement(DB::raw('set @rownum=0'));

        return $this->model::query()
            ->with('series', 'comboSeries', 'lesson')
            ->join('lmsseries_combo', 'lmsseries_combo.id', 'comments.lmscombo_id')
            ->join('users', 'users.id', 'comments.user_id')
            ->select([
                DB::raw('@rownum := @rownum + 1 AS stt'),
                'comments.body',
                'users.name',
                'lmsseries_combo.title',
                'comments.updated_at',
                'comments.status',
                'comments.id',
                'comments.lmsseries_id',
                'comments.lmscombo_id',
                'comments.lmscontent_id',
                'comments.created_at',
            ])
            ->where('comments.user_id', $studentId)
            ->where('comments.parent_id', 0)
            ->orderByRaw('FIELD(comments.status, "0", "2", "1")')
            ->orderBy('comments.updated_at', 'desc')
            ->paginate(15);
    }
}
