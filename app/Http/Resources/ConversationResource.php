<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ConversationResource extends Resource
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
            'id' => $this->dify_conversation_id,
            'title' => $this->title,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
