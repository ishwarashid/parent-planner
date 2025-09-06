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

        // For private files, generate a temporary URL
        if (config('filesystems.disks.do.visibility') === 'private') {
            try {
                // Use Laravel's Storage facade to generate a temporary URL
                return Storage::disk('do')->temporaryUrl($path, now()->addMinutes(5));
            } catch (Exception $e) {
                // If there's an error generating the temporary URL, fall back to local storage
                return asset('storage/' . $path);
            }
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