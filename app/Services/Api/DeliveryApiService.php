<?php

namespace App\Services\Api;

class DeliveryApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('deliveries.list', [], $query);
    }

    public function create(array $data): array
    {
        return $this->post('deliveries.create', [], $data);
    }

    public function my(): array
    {
        return $this->get('deliveries.my');
    }

    public function driverMy(): array
    {
        return $this->get('deliveries.driver_my');
    }

    public function show(int $deliveryId): array
    {
        return $this->get('deliveries.show', ['delivery_id' => $deliveryId]);
    }

    public function assignDriver(int $deliveryId, int $driverId): array
    {
        return $this->patch('deliveries.assign_driver', ['delivery_id' => $deliveryId], ['driver_id' => $driverId]);
    }

    public function updateStatus(int $deliveryId, string $status, ?string $failureReason = null): array
    {
        $data = ['status' => $status];
        if ($failureReason !== null) {
            $data['failure_reason'] = $failureReason;
        }
        return $this->patch('deliveries.update_status', ['delivery_id' => $deliveryId], $data);
    }

    public function updateLocation(int $deliveryId, float $latitude, float $longitude): array
    {
        return $this->patch('deliveries.update_location', ['delivery_id' => $deliveryId], [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
