<?php

namespace App\Repositories;

use App\LmsContent;
use App\LmsSeries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

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

    /**
     * Get list contents
     *
     * @param string $seriesId
     * @return Collection
     */
    public function getListContents(string $seriesId) {
        return $this->model->with('childContents.childContents.childContents')
            ->whereNull('parent_id')->where('lmsseries_id', $seriesId)
            ->where('delete_status', 0)->orderBy('stt', 'asc')
            ->get();
    }

    /**
     * Get content by id with its ancestor contents
     *
     * @param string $id
     * @return mixed(LmsContent|Null)
     */
    public function findByIdWithAncestors(string $id) {
        return $this->model
            ->with('parentContent.parentContent.parentContent')
            ->where('id', $id)
            ->get()->first();
    }

    /**
     * Get the first content of the series
     *
     * @param string $seriesId
     * @return mixed(LmsContent|null)
     */
    public function getFirstContentOfSeries(?string $seriesId)
    {
        if ($seriesId === null) {
            return null;
        }

        return $this->model
            ->where('lmsseries_id', $seriesId)
            ->orderBy('stt', 'asc')
            ->whereNotIn('type', [LmsContent::LESSON, LmsContent::LESSON_TOPIC])
            ->first();
    }
}
