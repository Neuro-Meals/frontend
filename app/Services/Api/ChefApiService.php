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

    public function ordersToday(bool $includeCompleted = false): array
    {
        return $this->get('chef.orders_today', [], $includeCompleted ? ['include_completed' => 'true'] : []);
    }

    public function ordersTodayGrouped(bool $includeCompleted = false): array
    {
        return $this->get('chef.orders_today_grouped', [], $includeCompleted ? ['include_completed' => 'true'] : []);
    }

    public function ordersTomorrow(bool $includeCompleted = false): array
    {
        return $this->get('chef.orders_tomorrow', [], $includeCompleted ? ['include_completed' => 'true'] : []);
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

    public function bulkAssignDriver(int $driverId, array $orderIds, ?string $scheduledAt = null): array
    {
        $data = [
            'driver_id' => $driverId,
            'order_ids' => $orderIds,
        ];
        if ($scheduledAt) {
            $data['scheduled_at'] = $scheduledAt;
        }
        return $this->post('chef.bulk_assign_driver', [], $data);
    }

    public function mealsSummary(?string $date = null): array
    {
        $query = [];
        if ($date) {
            $query['date'] = $date;
        }
        return $this->get('chef.meals_summary', [], $query);
    }

    public function allergiesSummary(?string $date = null): array
    {
        $query = [];
        if ($date) {
            $query['date'] = $date;
        }
        return $this->get('chef.allergies_summary', [], $query);
    }

    public function readyForDelivery(bool $unassignedOnly = false, ?string $date = null): array
    {
        $query = [];
        if ($unassignedOnly) {
            $query['unassigned_only'] = 'true';
        }
        if ($date) {
            $query['date'] = $date;
        }
        return $this->get('chef.ready_for_delivery', [], $query);
    }

    public function generateTodayOrders(): array
    {
        $url = $this->buildUrl('orders_automation.generate') . '?date=' . date('Y-m-d');
        return $this->request('post', $url);
    }

    public function confirmTodayOrders(): array
    {
        return $this->post('orders_automation.confirm_today');
    }

}
