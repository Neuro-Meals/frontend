<?php

namespace App\Services\Api;

class OrderApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('orders.list', [], $query);
    }

    public function my(): array
    {
        return $this->get('orders.my');
    }

    public function show(int $orderId): array
    {
        return $this->get('orders.show', ['order_id' => $orderId]);
    }

    public function fromSubscription(int $subscriptionId, ?string $deliveryAddress = null, ?string $deliveryNotes = null): array
    {
        return $this->post('orders.from_subscription', [], [
            'subscription_id' => $subscriptionId,
            'delivery_address' => $deliveryAddress,
            'delivery_notes' => $deliveryNotes,
        ]);
    }

    public function updateStatus(int $orderId, string $status): array
    {
        return $this->patch('orders.update_status', ['order_id' => $orderId], ['status' => $status]);
    }

    public function cancel(int $orderId): array
    {
        return $this->post('orders.cancel', ['order_id' => $orderId]);
    }
}
