<?php

if (!function_exists('meal_image_url')) {
    /**
     * Build a full URL for a meal image returned by the backend API.
     *
     * Backend stores images as relative paths such as /static/uploads/xxx.jpg.
     * This helper prepends the configured backend base URL when needed and
     * falls back to the local placeholder logo.
     */
    function meal_image_url(?string $url): string
    {
        if (empty($url)) {
            return asset('whitelogo.png');
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        $baseUrl = rtrim(config('api.base_url'), '/');
        $path = ltrim($url, '/');

        return $baseUrl . '/' . $path;
    }
}
