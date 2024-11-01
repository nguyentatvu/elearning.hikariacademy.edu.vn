<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class BannerResource extends Resource
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
            'description' => $this->description,
            'display_type' => $this->display_type,
            'to_url' => $this->to_url,
            'image' => $this->image,
            'position' => $this->position,
        ];
    }
}
