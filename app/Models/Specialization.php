<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Specialization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relationship to AstrologerProfile
     */
    public function astrologerProfiles()
    {
        return $this->belongsToMany(AstrologerProfile::class, 'astrologer_profile_specialization');
    }

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($specialization) {
            if (empty($specialization->slug)) {
                $specialization->slug = Str::slug($specialization->name);

                // Ensure slug is unique
                $originalSlug = $specialization->slug;
                $count = 1;
                while (self::where('slug', $specialization->slug)->exists()) {
                    $specialization->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });

        static::updating(function ($specialization) {
            if ($specialization->isDirty('name')) {
                $specialization->slug = Str::slug($specialization->name);

                // Ensure slug is unique (excluding current record)
                $originalSlug = $specialization->slug;
                $count = 1;
                while (self::where('slug', $specialization->slug)->where('id', '!=', $specialization->id)->exists()) {
                    $specialization->slug = $originalSlug . '-' . $count;
                    $count++;
                }
            }
        });
    }

    /**
     * Scope for active specializations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
