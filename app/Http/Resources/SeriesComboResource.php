<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class SeriesComboResource extends Resource
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

        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'slug_lms_series_combo' => $this->slug,
            'slug_lms_series' => $this->slug_lmscontents,
            'cost' => $this->cost,
            'selloff' => $this->selloff,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'image' => $this->image,
            'type' => $this->type,
            'time' => $timeValues[$this->time],
            'total_items' => $this->total_items,
            'series' => $this->series,
            'timefrom' => $this->timefrom,
            'timeto' => $this->timeto,
            'total_lessons' => $this->total_lessons,
            'trial_lessons' => $this->trial_lessons,
            'payment' => $this->payment
        ];
    }
}
