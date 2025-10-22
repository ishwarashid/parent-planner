<?php

if (!function_exists('do_spaces_url')) {
    /**
     * Generate a URL for a file stored in DigitalOcean Spaces
     *
     * @param string $path
     * @return string
     */
    function do_spaces_url($path)
    {
        // If no path is provided, return empty string
        if (empty($path)) {
            return '';
        }

        // If the path is already a full URL, return it as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Check if this is a help-video or help-video-thumbnail path
        $isHelpVideoPath = strpos($path, 'help-videos/') === 0;
        $isHelpThumbnailPath = strpos($path, 'help-video-thumbnails/') === 0;
        
        // Get CDN URL configuration
        $cdnUrl = config('filesystems.disks.do.cdn_url');
        
        // Log for debugging
        \Log::info("do_spaces_url called", [
            'path' => $path,
            'isHelpVideoPath' => $isHelpVideoPath,
            'isHelpThumbnailPath' => $isHelpThumbnailPath,
            'cdnUrl' => $cdnUrl
        ]);
        
        // For help videos and thumbnails, use CDN if available regardless of visibility
        if ((!empty($cdnUrl)) && ($isHelpVideoPath || $isHelpThumbnailPath)) {
            $cdnUrl = rtrim($cdnUrl, '/');
            $path = ltrim($path, '/');
            $url = "{$cdnUrl}/{$path}";
            \Log::info("Using CDN URL for help file: " . $url);
            return $url;
        }
        
        // For private files, generate a temporary URL
        if (config('filesystems.disks.do.visibility') === 'private') {
            try {
                // Use Laravel's Storage facade to generate a temporary URL
                return Storage::disk('do')->temporaryUrl($path, now()->addMinutes(5));
            } catch (Exception $e) {
                // If there's an error generating the temporary URL, fall back to default behavior
                \Log::warning('Could not generate temporary URL: ' . $e->getMessage());
                
                // Fallback: construct URL manually if CDN is available
                if (!empty($cdnUrl)) {
                    $cdnUrl = rtrim($cdnUrl, '/');
                    $path = ltrim($path, '/');
                    $url = "{$cdnUrl}/{$path}";
                    \Log::info("Using CDN URL as fallback: " . $url);
                    return $url;
                }
                
                // If no CDN, construct direct DO Spaces URL
                $endpoint = config('filesystems.disks.do.endpoint');
                $bucket = config('filesystems.disks.do.bucket');
                
                if (empty($endpoint) || empty($bucket)) {
                    return asset('storage/' . $path);
                }
                
                $endpoint = rtrim($endpoint, '/');
                $bucket = trim($bucket, '/');
                $path = ltrim($path, '/');
                
                return "{$endpoint}/{$bucket}/{$path}";
            }
        }
        
        // For public files, return CDN URL if configured, otherwise direct DO Spaces URL
        if (!empty($cdnUrl)) {
            $cdnUrl = rtrim($cdnUrl, '/');
            $path = ltrim($path, '/');
            $url = "{$cdnUrl}/{$path}";
            \Log::info("Using CDN URL for public file: " . $url);
            return $url;
        }

        // Get the DigitalOcean Spaces endpoint from config
        $endpoint = config('filesystems.disks.do.endpoint');
        $bucket = config('filesystems.disks.do.bucket');
        
        // If endpoint or bucket is not configured, fallback to local storage
        if (empty($endpoint) || empty($bucket)) {
            return asset('storage/' . $path);
        }
        
        // Construct the URL for DigitalOcean Spaces
        $endpoint = rtrim($endpoint, '/');
        $bucket = trim($bucket, '/');
        
        // Remove any leading slash from the path
        $path = ltrim($path, '/');
        
        return "{$endpoint}/{$bucket}/{$path}";
    }
}