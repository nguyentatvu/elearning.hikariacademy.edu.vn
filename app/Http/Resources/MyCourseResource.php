<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MyCourseResource extends Resource
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
            'series_combo_id' => $this->series_combo_id,
            'series_id' => $this->series_id,
            'title' => $this->title,
            'purchase_date' => $this->created_at->format('Y-m-d H:i:s'),
            'expiry_date' => $this->expiry_date,
            'is_active' => $this->is_active,
            'total_lessons' => $this->total_lessons,
            'completed_lessons' => $this->completed_lessons
        ];
    }
}
