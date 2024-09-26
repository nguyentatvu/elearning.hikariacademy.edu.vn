<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageHistory extends Model
{
    protected $table = 'message_histories';

    protected $fillable = ['user_id', 'conversation_id', 'user_message', 'bot_response'];

    /**
     * Get the user that owns the MessageHistory
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the conversation that owns the MessageHistory
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id', 'id');
    }
}
