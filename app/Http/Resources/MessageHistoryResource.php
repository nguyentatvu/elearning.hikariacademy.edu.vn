<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class MessageHistoryResource extends Resource
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
            'user_request' => $this->user_message,
            'bot_response' => $this->bot_response,
        ];
    }
}
