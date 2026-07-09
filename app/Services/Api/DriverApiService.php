<?php

namespace App\Services\Api;

class DriverApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('drivers.list', [], $query);
    }

    public function show(int $id): array
    {
        return $this->get('drivers.show', ['driver_id' => $id]);
    }

    public function create(array $data): array
    {
        return $this->post('drivers.create', [], $data);
    }

    public function update(int $id, array $data): array
    {
        return $this->put('drivers.update', ['driver_id' => $id], $data);
    }

    public function destroy(int $id): array
    {
        return $this->delete('drivers.delete', ['driver_id' => $id]);
    }

    public function myDeliveries(): array
    {
        return $this->get('driver.my_deliveries');
    }

    public function showDelivery(int $deliveryId): array
    {
        return $this->get('driver.show_delivery', ['delivery_id' => $deliveryId]);
    }

    public function pickupDelivery(int $deliveryId): array
    {
        return $this->post('driver.pickup', ['delivery_id' => $deliveryId]);
    }

    public function outForDelivery(int $deliveryId): array
    {
        return $this->post('driver.out_for_delivery', ['delivery_id' => $deliveryId]);
    }

    public function completeDelivery(int $deliveryId): array
    {
        return $this->post('driver.complete', ['delivery_id' => $deliveryId]);
    }

    public function failDelivery(int $deliveryId, string $reason): array
    {
        return $this->post('driver.fail', ['delivery_id' => $deliveryId], ['reason' => $reason]);
    }

    public function updateLocation(int $deliveryId, float $latitude, float $longitude): array
    {
        return $this->patch('driver.update_location', ['delivery_id' => $deliveryId], [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
