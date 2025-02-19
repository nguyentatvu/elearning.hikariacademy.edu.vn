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
        $image = $imageSettings->getProfilePicsThumbnailpath() . $this->image;
        $timestamp = time();

        if (!$this->image || $this->image == "") {
            $image = $imageSettings->getDefaultprofilePicsThumbnailpath();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'image' => $image . '?t=' . $timestamp,
            'address' => $this->address,
            'reward_point' => $this->reward_point,
        ];
    }
}
