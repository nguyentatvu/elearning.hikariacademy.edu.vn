<?php

namespace App\Repositories;

class HandwritingRepository extends BaseRepository
{
    /**
     * Get all Handwritings with sorting
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithSorting()
    {
        $japaneseWritings = $this->model->orderBy('updated_at', 'desc')->get();

        return $japaneseWritings;
    }
}