<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class QnAResource extends Resource
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
            'conversation_id' => $this->dify_conversation_id,
            'user_message' => $this->user_message,
            'bot_response' => $this->bot_response,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
