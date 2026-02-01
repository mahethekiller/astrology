<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // Add this

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles; // Add HasRoles trait

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Check if user is admin (convenience method)
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is manager (convenience method)
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    /**
     * Check if user is regular user (convenience method)
     */
    public function isUser(): bool
    {
        return $this->hasRole('user');
    }

    public function isAstrologer(): bool
    {
        return $this->hasRole('astrologer');
    }

    /**
     * Relationship to AstrologerProfile
     */
    public function astrologerProfile()
    {
        return $this->hasOne(\App\Models\AstrologerProfile::class);
    }

    public function wallet()
    {
        return $this->hasOne(\App\Models\Wallet::class);
    }
}
