<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PronunciationDetail extends Model
{
    protected $table = "pronunciation_details";

    protected $fillable = ['pronunciation_id', 'audio_input_name', 'text', 'audio'];
}