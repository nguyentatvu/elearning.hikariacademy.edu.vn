<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class IntonationResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'word' => $this->word,
            'start' => $this->start,
            'end' => $this->end,
            'average' => $this->average,
        ];
    }
}
