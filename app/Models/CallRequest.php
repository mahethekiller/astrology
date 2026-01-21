<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallRequest extends Model
{
    protected $fillable = [
        'user_id',
        'astrologer_id',
        'twilio_sid',
        'call_duration',
        'call_cost',
        'call_status',
        'start_time',
        'end_time',
        'commission_amount',
        'astrologer_earnings',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'call_cost' => 'decimal:2',
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
