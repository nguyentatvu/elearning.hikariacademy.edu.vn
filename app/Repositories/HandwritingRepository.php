<?php

namespace App\Repositories;

class HandwritingRepository extends BaseRepository
{
    /**
     * Get all Handwritings with sorting
     *
     * @param string $column
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithSorting(string $column = 'updated_at', string $order = 'desc')
    {
        $japaneseWritings = $this->model->orderBy($column, $order)->get();

        return $japaneseWritings;
    }
}