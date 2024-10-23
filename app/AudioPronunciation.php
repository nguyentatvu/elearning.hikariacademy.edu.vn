<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AudioPronunciation extends Model
{
    protected $table = 'audio_pronunciations';

    protected $fillable = [
        'name',
        'sentence',
        'link',
    ];

    /**
     * Get the intonation that owns the Audio pronunciation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function intonations()
    {
        return $this->hasMany(Intonation::class, 'audio_pronunciation_id', 'id');
    }
}
