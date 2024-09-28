<?php

namespace App\Repositories;

use App\LmsContent;
use App\LmsSeries;
use Illuminate\Support\Facades\DB;

class LmsContentRepository extends BaseRepository
{
    /**
     * Get contents by series id
     *
     * @param int $seriesId
     * @return \Illuminate\Support\Collection
     */
    public function getContentsBySerieId(int $seriesId)
    {
        $series = LmsSeries::where('delete_status', 0)->find($seriesId);

        if (!$series) {
            return null;
        }

        return $this->getContents(null, $seriesId);
    }

    /**
     * Get contents recursively with children
     *
     * @param int|null $parentId
     * @param int $seriesId
     * @return \Illuminate\Support\Collection
     */
    private function getContents(?int $parentId, int $seriesId)
    {
        $contents = $this->model::query()
            ->select('id', 'bai', 'title', 'file_path', 'image', 'description', 'type', 'el_try as is_trial', 'parent_id')
            ->where('lmsseries_id', $seriesId)
            ->where('parent_id', $parentId)
            ->where('delete_status', 0)
            ->orderBy('stt', 'asc')
            ->get();

        foreach ($contents as $content) {
            $content->children = $this->getContents($content->id, $seriesId);
        }

        return $contents;
    }

    /**
     * Get content by id
     *
     * @param int $id
     * @return \Illuminate\Support\Collection
     */
    public function getContentById(int $id)
    {
        $content = $this->model::where('id', $id)
            ->where('delete_status', 0)
            ->first();

        return $content;
    }

    /**
     * Get in progress content
     *
     * @param int $userId
     * @param int $seriesId
     * @return \Illuminate\Support\Collection
     */
    public function getInProgressContent(int $userId, int $seriesId)
    {
        $content = $this->model::with(['lmsseries' => function ($query) {
            $query->where('delete_status', 0);
        }])
            ->whereHas('lmsStudentView', function ($query) use ($userId) {
                $query->where('users_id', $userId)
                    ->where('finish', 0);
            })
            ->where('lmsseries_id', $seriesId)
            ->where('delete_status', 0)
            ->orderBy('stt', 'desc')
            ->first();

        return $content;
    }
}
