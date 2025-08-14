<?php

if (!function_exists('getUserTimezone')) {
    /**
     * Get the user's timezone from session or fallback to app timezone
     *
     * @return string
     */
    function getUserTimezone()
    {
        // Try to get timezone from session (if previously detected)
        if (session()->has('user_timezone')) {
            return session('user_timezone');
        }
        
        // Fallback to app timezone
        return config('app.timezone', 'UTC');
    }
}

if (!function_exists('convertToUserTimezone')) {
    /**
     * Convert a datetime to the user's timezone
     *
     * @param mixed $datetime
     * @return \Carbon\Carbon
     */
    function convertToUserTimezone($datetime)
    {
        $userTimezone = getUserTimezone();
        $appTimezone = config('app.timezone', 'UTC');
        
        // Parse the datetime and set its timezone to the app timezone (assuming it's stored in app timezone)
        $carbon = \Carbon\Carbon::parse($datetime)->setTimezone($appTimezone);
        
        // Convert to user's timezone
        return $carbon->setTimezone($userTimezone);
    }
}

if (!function_exists('formatUserTimezone')) {
    /**
     * Format a datetime in the user's timezone
     *
     * @param mixed $datetime
     * @param string $format
     * @return string
     */
    function formatUserTimezone($datetime, $format = 'M d, Y H:i A')
    {
        return convertToUserTimezone($datetime)->format($format);
    }
}