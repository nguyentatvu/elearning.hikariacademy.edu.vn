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

    /**
     * Get Comments in course
     *
     * @param string $combo_slug
     * @param string $series_slug
     * @param string $lesson
     * @param int $userId
     * @return Comment
     */
    public function getCommentsInCourse(string $combo_slug, string $series_slug, string $lesson, int $userId)
    {
        $comments = $this->model::whereHas('series', function ($query) use ($series_slug) {
            $query->where('slug', $series_slug);
        })
            ->whereHas('comboSeries', function ($query) use ($combo_slug) {
                $query->where('slug', $combo_slug);
            })
            ->whereHas('lesson', function ($query) use ($lesson) {
                $query->where('id', $lesson);
            })
            ->where('user_id', $userId)
            ->where('parent_id', 0)
            ->with(['childComments', 'user', 'series', 'comboSeries', 'lesson'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return $comments;
    }
}
