<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ContentResource extends Resource
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
            'bai' => $this->bai,
            'title' => $this->title,
            'file_path' => $this->file_path,
            'image' => $this->image,
            'description' => $this->description,
            'type' => $this->type,
            'is_trial' => $this->el_try,
            'parent_id' => $this->parent_id,
            'document' => $this->download_doc,
            'test' => $this->test,
            'exercise' => $this->exercise,
            'flashcard' => $this->flashcard,
        ];
    }
}
