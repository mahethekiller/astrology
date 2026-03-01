<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class Blog extends Model
{
    protected $appends = ['image_url'];
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'image',
        'image_alt',
        'author',
        'published_at',
        'meta_title',
        'meta_description'
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }
}
