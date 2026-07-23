<?php

namespace App\Services\Api;

/**
 * Trait to be used by controllers to fetch data from API
 * with fallback to mock data during development.
 *
 * Usage in controller:
 *   use HasApiData;
 *   $api = app(AdminApiService::class);
 *
 *   public function dashboard()
 *   {
 *       $response = $this->apiData($api->dashboardStats(), function () {
 *           return $this->mockStats();
 *       });
 *       $stats = $response['data'] ?? $response;
 *       return view('admin.dashboard', compact('stats'));
 *   }
 */
trait HasApiData
{
    /**
     * Check if API mode is enabled.
     */
    protected function apiEnabled(): bool
    {
        return config('api.base_url') !== null
            && config('api.enabled', false) === true;
    }

    /**
     * Fetch data from API, fall back to mock if API fails or disabled.
     *
     * @param array $apiResponse  Response from API service
     * @param callable $fallback  Function returning mock data
     * @return array
     */
    protected function apiData(array $apiResponse, callable $fallback): array
    {
        if (isset($apiResponse['success']) && $apiResponse['success'] === false) {
            return $fallback();
        }

        if (!isset($apiResponse['data']) && empty($apiResponse)) {
            return $fallback();
        }

        return $apiResponse['data'] ?? $apiResponse;
    }

    /**
     * Extract pagination metadata from an API response.
     * Supports both the old {meta: {total, page, limit, pages}} format
     * and the new {pagination: {total_items, page, limit, total_pages}} format.
     *
     * @param array $apiResponse  Raw response from API service
     * @return array  Normalized: ['total' => int, 'page' => int, 'limit' => int, 'pages' => int]
     */
    protected function apiMeta(array $apiResponse): array
    {
        $meta = $apiResponse['meta'] ?? $apiResponse['pagination'] ?? [];

        return [
            'total' => $meta['total'] ?? $meta['total_items'] ?? 0,
            'page'  => $meta['page'] ?? 1,
            'limit' => $meta['limit'] ?? 10,
            'pages' => $meta['pages'] ?? $meta['total_pages'] ?? 1,
        ];
    }
}
