<?php

namespace App\Services\Api;

class ChefApiService extends BaseApiService
{
    public function dashboard(): array
    {
        return $this->get('chef.dashboard');
    }

    public function orders(array $query = []): array
    {
        return $this->get('chef.orders', [], $query);
    }

    public function showOrder(int $orderId): array
    {
        return $this->get('chef.show_order', ['order_id' => $orderId]);
    }

    public function startPreparing(int $orderId): array
    {
        return $this->patch('chef.start_preparing', ['order_id' => $orderId]);
    }

    public function markReady(int $orderId): array
    {
        return $this->patch('chef.mark_ready', ['order_id' => $orderId]);
    }

    public function drivers(bool $availableOnly = false): array
    {
        return $this->get('chef.drivers', [], $availableOnly ? ['available_only' => 'true'] : []);
    }

    public function assignDriver(int $orderId, int $driverId, ?string $scheduledAt = null): array
    {
        $data = ['driver_id' => $driverId];
        if ($scheduledAt) {
            $data['scheduled_at'] = $scheduledAt;
        }
        return $this->post('chef.assign_driver', ['order_id' => $orderId], $data);
    }
}
