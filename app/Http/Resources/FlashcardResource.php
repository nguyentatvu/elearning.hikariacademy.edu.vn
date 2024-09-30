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
        $details = $this->flashcardDetails->map(function ($detail) {
            return [
                'id' => $detail->id,
                'word' => $detail->m1tuvung,
                'front_example' => $detail->m1vidu,
                'pronunciation' => $detail->m2cachdoc,
                'sino_vietnamese' => $detail->m2amhanviet,
                'meaning' => $detail->m2ynghia,
                'back_example' => $detail->m2vidu,
                'stt' => $detail->stt,
                'audio' => $detail->mp3,
            ];
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'detail' => $details,
        ];
    }
}
