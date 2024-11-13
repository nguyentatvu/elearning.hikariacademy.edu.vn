<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
        $imageUrl = config('constant.series_combo.image_url');

        $currentDate = Carbon::now();
        $timeFrom = Carbon::parse($this->timefrom);
        $timeTo = Carbon::parse($this->timeto);
        $selloffValue = $currentDate->between($timeFrom, $timeTo) ? (int) $this->selloff : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'slug_lms_series_combo' => $this->slug,
            'slug_lms_series' => $this->slug_lmscontents,
            'cost' => (int) $this->cost,
            'selloff' => $selloffValue,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'image' => $imageUrl . $this->image,
            'type' => $this->type,
            'time' => $timeValues[$this->time],
            'total_items' => $this->total_items,
            'series' => $this->series,
            'timefrom' => $this->timefrom,
            'timeto' => $this->timeto,
            'total_lessons' => $this->total_lessons,
            'trial_lessons' => $this->trial_lessons,
            'payment' => $this->payment,
        ];
    }
}
