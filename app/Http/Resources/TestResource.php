<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TestResource extends Resource
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
            'question_type' => $this->dang,
            'question' => $this->cau,
            'description' => $this->mota,
            'answers' => $this->answers,
        ];
    }
}
