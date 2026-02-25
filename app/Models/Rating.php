<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'astrologer_profile_id',
        'rating',
        'comment',
        'ratable_type',
        'ratable_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function astrologerProfile()
    {
        return $this->belongsTo(AstrologerProfile::class);
    }

    public function ratable()
    {
        return $this->morphTo();
    }
}
