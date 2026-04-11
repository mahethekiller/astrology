<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    protected $appends = ['image_url', 'mobile_image_url', 'app_image_url'];

    protected $fillable = [
        'title',
        'description',
        'image',
        'mobile_image',
        'app_image',
        'group',
        'order',
        'is_active',
        'button_text',
        'button_link'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    public function getMobileImageUrlAttribute()
    {
        return $this->mobile_image ? Storage::url($this->mobile_image) : null;
    }

    public function getAppImageUrlAttribute()
    {
        return $this->app_image ? Storage::url($this->app_image) : null;
    }
}
