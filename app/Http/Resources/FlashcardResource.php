<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class FlashcardResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $audioUrl = config('constant.flash_card.audio_url') . $this->mp3;

        return [
            'word' => $this->m1tuvung ?? '',
            'front_example' => $this->m1vidu ?? '',
            'pronunciation' => $this->m2cachdoc ?? '',
            'sino_vietnamese' => $this->m2amhanviet ?? '',
            'meaning' => $this->m2ynghia ?? '',
            'back_example' => $this->m2vidu ?? '',
            'stt' => $this->stt,
            'audio' => $audioUrl,
        ];
    }
}
