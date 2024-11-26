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
        $imageUrl = config('constant.series.image_url');
        $timestamp = time();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'image' => $imageUrl . $this->image . '?t=' . $timestamp,
            'total_lessons' => $this->total_lessons,
            'teachers' => $this->teachers->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                ];
            }),
        ];
    }
}
