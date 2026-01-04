<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['chat_request_id', 'sender_identity', 'body'];

    public function chatRequest()
    {
        return $this->belongsTo(ChatRequest::class);
    }
}
