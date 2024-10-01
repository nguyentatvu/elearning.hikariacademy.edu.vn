<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SeriesAndTeacherResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'teachers' => $this->teachers->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                ];
            }),
        ];
    }
}
