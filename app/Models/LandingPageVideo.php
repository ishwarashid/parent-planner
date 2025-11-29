<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class LandingPageVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration',
        'date',
        'video_url',
        'thumbnail_url',
        'is_active',
        'video_type'
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the full URL for the video file.
     *
     * @return string
     */
    public function getVideoUrlAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        // If it's already a full URL (external), return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Check if this is a landing page video path - if so, it should be public and use CDN
        $isLandingVideoPath = strpos($value, 'landing-page-videos/') === 0;

        if ($isLandingVideoPath) {
            // For landing page videos, directly use the do_spaces_url helper which handles CDN
            return do_spaces_url($value);
        }

        // For other files, check if the file is in DO Spaces by checking if it exists there
        // Add error handling for potential connection issues
        try {
            if (Storage::disk('do')->exists($value)) {
                // If the file is stored in DigitalOcean Spaces with private visibility, generate a temporary URL
                if (config('filesystems.disks.do.visibility') === 'private' && !empty($value)) {
                    try {
                        return Storage::disk('do')->temporaryUrl($value, now()->addMinutes(5));
                    } catch (\Exception $e) {
                        // Fall back to the helper function if there's an error
                        return do_spaces_url($value);
                    }
                }

                return do_spaces_url($value);
            }
        } catch (\Exception $storageException) {
            \Log::warning('Storage check failed for video: ' . $storageException->getMessage());
            // If storage check fails, try the helper function directly
            return do_spaces_url($value);
        }

        // If not in DO Spaces, check if it's in the public disk
        try {
            if (Storage::disk('public')->exists($value)) {
                return asset('storage/' . $value);
            }
        } catch (\Exception $storageException) {
            \Log::warning('Public storage check failed for video: ' . $storageException->getMessage());
        }

        // If it's neither in DO nor public disk, return as is (might be a relative path)
        return $value;
    }

    /**
     * Get the full URL for the thumbnail image.
     *
     * @return string
     */
    public function getThumbnailUrlAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        // If it's already a full URL (external), return as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Check if this is a landing page video thumbnail path - if so, it should be public and use CDN
        $isLandingThumbnailPath = strpos($value, 'landing-page-video-thumbnails/') === 0;

        if ($isLandingThumbnailPath) {
            // For landing page video thumbnails, directly use the do_spaces_url helper which handles CDN
            return do_spaces_url($value);
        }

        // For other files, check if the file is in DO Spaces by checking if it exists there
        // Add error handling for potential connection issues
        try {
            if (Storage::disk('do')->exists($value)) {
                // If the file is stored in DigitalOcean Spaces with private visibility, generate a temporary URL
                if (config('filesystems.disks.do.visibility') === 'private' && !empty($value)) {
                    try {
                        return Storage::disk('do')->temporaryUrl($value, now()->addMinutes(5));
                    } catch (\Exception $e) {
                        // Fall back to the helper function if there's an error
                        return do_spaces_url($value);
                    }
                }

                return do_spaces_url($value);
            }
        } catch (\Exception $storageException) {
            \Log::warning('Storage check failed for thumbnail: ' . $storageException->getMessage());
            // If storage check fails, try the helper function directly
            return do_spaces_url($value);
        }

        // If not in DO Spaces, check if it's in the public disk
        try {
            if (Storage::disk('public')->exists($value)) {
                return asset('storage/' . $value);
            }
        } catch (\Exception $storageException) {
            \Log::warning('Public storage check failed for thumbnail: ' . $storageException->getMessage());
        }

        // If it's neither in DO nor public disk, return as is (might be a relative path)
        return $value;
    }

    /**
     * Check if the video has a valid URL that can be played
     *
     * @return bool
     */
    public function hasValidVideo()
    {
        $videoUrl = $this->video_url;

        // Check if video URL is not empty and is different from empty URL result
        try {
            $emptySpaceUrl = do_spaces_url('');
            return !empty($videoUrl) && $videoUrl !== $emptySpaceUrl;
        } catch (\Exception $e) {
            // If there's an error generating the empty space URL, just check if the video URL is not empty
            return !empty($videoUrl);
        }
    }

    /**
     * Scope to get only active landing page videos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
