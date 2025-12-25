<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AstrologerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'slug',
        'gender',
        'date_of_birth',
        'experience_years',
        'about',
        'profile_image',
        'cover_image',
        // 'languages',
        // 'specializations',
        'verification_status',
        'is_featured',
        'is_online',
        'status',
        'rating',
        'total_reviews',
        'total_consultations',
        'chat_price',
        'call_price',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_featured' => 'boolean',
        'is_online' => 'boolean',
        'rating' => 'decimal:1',
        'total_reviews' => 'integer',
        'total_consultations' => 'integer',
        'experience_years' => 'integer',
        'chat_price' => 'decimal:2',
        'call_price' => 'decimal:2',
    ];

    /**
     * Relationship to User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Specializations
     */
    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'astrologer_profile_specialization');
    }

    /**
     * Relationship to Languages
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'astrologer_profile_language');
    }

    /**
     * Get the profile image URL
     */
    public function getProfileImageUrlAttribute()
    {
        return asset('uploads/astrologers/' . $this->profile_image);
    }

    /**
     * Get the cover image URL
     */
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('uploads/astrologers/' . $this->cover_image);
        }
        return null;
    }

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($profile) {
            if (empty($profile->slug)) {
                $profile->slug = Str::slug($profile->display_name);

                // Ensure slug is unique
                $originalSlug = $profile->slug;
                $count = 1;
                while (self::where('slug', $profile->slug)->exists()) {
                    $profile->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        static::updating(function ($profile) {
            if ($profile->isDirty('display_name')) {
                $profile->slug = Str::slug($profile->display_name);

                // Ensure slug is unique (excluding current record)
                $originalSlug = $profile->slug;
                $count = 1;
                while (self::where('slug', $profile->slug)->where('id', '!=', $profile->id)->exists()) {
                    $profile->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });
    }

    /**
     * Scope for featured astrologers
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for online astrologers
     */
    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    /**
     * Scope for active astrologers
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for approved astrologers
     */
    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'approved');
    }
}
