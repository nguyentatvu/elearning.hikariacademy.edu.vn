<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pronunciation extends Model
{
    protected $table = "pronunciations";

    protected $fillable = ['title'];

    /**
     * Get the pronunciationDetails that owns the Pronunciation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pronunciationDetails()
    {
        return $this->hasMany(PronunciationDetail::class, 'pronunciation_id', 'id');
    }
}