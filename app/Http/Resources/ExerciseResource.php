<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ExerciseResource extends Resource
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
            'label' => $this->label,
            'question_type' => $this->dang,
            'question' => $this->cau,
            'description' => $this->mota,
            'correct_answer' => $this->dapan,
            'answers' => $this->answers,
        ];
    }
}
