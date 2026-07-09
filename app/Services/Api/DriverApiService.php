<?php

namespace App\Services\Api;

/**
 * Driver management API client.
 *
 * NOTE: This service currently points to placeholder endpoints.
 *       Connect these to the real backend driver/user endpoints when ready.
 */
class DriverApiService extends BaseApiService
{
    /**
     * List all drivers.
     *
     * Backend suggestion: GET /users?role=driver
     *
     * @param array<string, mixed> $query
     */
    public function list(array $query = []): array
    {
        // TODO: Replace 'drivers.list' endpoint key once backend endpoint exists.
        // For now, fall back to listing users with driver role via AdminApiService.
        return [];
    }

    /**
     * Create a new driver.
     *
     * Backend suggestion: POST /users (with role = driver)
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): array
    {
        // TODO: Replace 'drivers.store' endpoint key once backend endpoint exists.
        return [
            'success' => false,
            'message' => 'Driver creation API is not connected yet.',
        ];
    }

    /**
     * Update an existing driver.
     *
     * Backend suggestion: PUT /users/{id}
     *
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): array
    {
        // TODO: Replace 'drivers.update' endpoint key once backend endpoint exists.
        return [
            'success' => false,
            'message' => 'Driver update API is not connected yet.',
        ];
    }

    /**
     * Delete a driver.
     *
     * Backend suggestion: DELETE /users/{id}
     */
    public function destroy(int $id): array
    {
        // TODO: Replace 'drivers.destroy' endpoint key once backend endpoint exists.
        return [
            'success' => false,
            'message' => 'Driver deletion API is not connected yet.',
        ];
    }
}
