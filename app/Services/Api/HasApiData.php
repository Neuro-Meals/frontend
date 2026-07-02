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
            && env('API_ENABLED', false) === true;
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
}
