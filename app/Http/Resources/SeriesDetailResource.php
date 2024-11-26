<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SeriesDetailResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $timeValues = config('constant.series.time');
        $imageUrl = config('constant.series_combo.image_url');
        $timestamp = time();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'cost' => (int) $this->cost,
            'description' => $this->description,
            'image' => $imageUrl . $this->image . '?t=' . $timestamp,
            'time' => $timeValues[$this->time],
            'total_lessons' => $this->total_lessons,
            'payment' => $this->payment
        ];
    }
}
