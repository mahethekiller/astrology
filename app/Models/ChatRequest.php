<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'astrologer_id',
        'status',
        'twilio_sid',
        'chat_duration',
        'chat_cost',
        'commission_amount',
        'astrologer_earnings',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function astrologer()
    {
        return $this->belongsTo(AstrologerProfile::class, 'astrologer_id');
    }
}
