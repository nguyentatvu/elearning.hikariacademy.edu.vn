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
            'title' => $this->bai,
            'file_path' => $this->file_path,
            'description' => $this->description,
            'type' => $this->type,
            'is_trial' => $this->el_try,
            'parent_id' => $this->parent_id,
            'document' => $this->download_doc,
            'content' => $this->content,
        ];
    }
}
