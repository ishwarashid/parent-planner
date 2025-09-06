<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'video_url',
        'featured_image',
        'published',
        'published_at',
        'user_id'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'published' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Generate a unique slug for the blog post
     *
     * @param string $title
     * @return string
     */
    public static function generateUniqueSlug($title)
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $originalSlug = $slug;
        $count = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
