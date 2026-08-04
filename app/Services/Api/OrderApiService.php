<?php

namespace App\Services\Api;

class OrderApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('orders.list', [], $query);
    }


    /**
     * Load generated orders for a delivery date from the management endpoint.
     */
    public function today(
        ?string $deliveryDate = null
    ): array {
        return $this->get(
            'orders.list',
            [],
            [
                'delivery_date' => $deliveryDate ?: date('Y-m-d'),
                'page' => 1,
                'limit' => 100,
            ]
        );
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
