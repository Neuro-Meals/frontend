<?php

if (!function_exists('meal_image_url')) {
    /**
     * Build a full URL for a meal image returned by the backend API.
     *
     * Backend stores images as relative paths such as /static/uploads/xxx.jpg.
     * This helper prepends the configured backend base URL when needed and
     * falls back to a placeholder.
     */
    function meal_image_url(?string $url): string
    {
        if (empty($url)) {
            return asset('images/meal-placeholder.svg');
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // Keep relative paths as-is — served through Laravel's /static/{path} proxy.
        return $url;
    }
}

if (!function_exists('meal_image_srcset')) {
    /**
     * Build a srcset string for responsive meal images.
     * Returns empty string if the URL is a placeholder.
     */
    function meal_image_srcset(?string $url): string
    {
        if (empty($url)) {
            return '';
        }

        $full = meal_image_url($url);

        // If it's a placeholder, no srcset needed
        if (str_contains($full, 'meal-placeholder')) {
            return '';
        }

        // For backend images, we can't generate variants server-side,
        // so we return the same URL at different nominal widths for
        // browser hinting. The browser will still only download one.
        return $full . ' 400w, ' . $full . ' 800w';
    }
}
