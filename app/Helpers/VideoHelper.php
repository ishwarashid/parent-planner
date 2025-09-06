<?php

if (!function_exists('extractVideoId')) {
    /**
     * Extract video ID from various video platforms
     *
     * @param string $url
     * @return string|null
     */
    function extractVideoId($url) {
        if (empty($url)) {
            return null;
        }

        // YouTube patterns
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            // youtu.be short URLs
            if (preg_match('/youtu\.be\/([^\?]+)/', $url, $matches)) {
                return $matches[1];
            }
            
            // youtube.com URLs
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
                return $matches[1];
            }
        }

        // Vimeo patterns
        if (strpos($url, 'vimeo.com') !== false) {
            if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
                return $matches[1];
            }
            
            if (preg_match('/vimeo\.com\/.*?clip_id=(\d+)/', $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}

if (!function_exists('getVideoEmbedUrl')) {
    /**
     * Get embed URL for video based on platform
     *
     * @param string $url
     * @return string|null
     */
    function getVideoEmbedUrl($url) {
        if (empty($url)) {
            return null;
        }

        $videoId = extractVideoId($url);

        if (!$videoId) {
            return null;
        }

        // YouTube
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            return "https://www.youtube.com/embed/{$videoId}";
        }

        // Vimeo
        if (strpos($url, 'vimeo.com') !== false) {
            return "https://player.vimeo.com/video/{$videoId}";
        }

        return null;
    }
}