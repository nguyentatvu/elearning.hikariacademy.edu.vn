<?php

namespace App\Services;

use App\Repositories\CommentRepository;

class CommentService extends BaseService
{
    public function __construct(CommentRepository $repository)
    {
        parent::__construct($repository);
    }

    /**
     * Get student comments
     *
     * @param string $studentId
     * @return \Illuminate\Support\Collection
     */
    public function getStudentComments(string $studentId)
    {
        $comments = $this->repository->getStudentComments($studentId);

        $lessonTypeMap = config('constant.series.type_map');
        $lessonRouteMap = config('constant.series.routes');
        $comments->map(function ($comment) use ($lessonTypeMap, $lessonRouteMap) {
            foreach ($lessonRouteMap as $textType => $route) {
                if (in_array(optional($comment->lesson)->type, $lessonTypeMap[$textType])) {
                    $params =[
                        'combo_slug' => $comment->comboSeries->slug,
                        'slug' => $comment->series->slug,
                        'stt' => $comment->lesson->id
                    ];
                    $comment->lesson->url = route($route, $params);
                    break;
                }
            }
        });

        return $comments;
    }
}
