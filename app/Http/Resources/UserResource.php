<?php

namespace App\Http\Resources;

use App\ImageSettings;
use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $imageSettings = new ImageSettings();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $imageSettings->getProfilePicsThumbnailpath() . $this->image,
            'address' => $this->address,
            'reward_point' => $this->reward_point,
        ];
    }
}
