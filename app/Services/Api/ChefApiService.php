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

    // ─── Kitchen Schedule (item-level workflow) ───

    public function scheduleCategories(?string $date = null): array
    {
        $query = $date ? ['date' => $date] : [];
        return $this->get('schedule.categories', [], $query);
    }

    public function productionRequirements(?string $date = null, ?int $categoryId = null): array
    {
        $query = [];
        if ($date) {
            $query['date'] = $date;
        }
        if ($categoryId) {
            $query['category_id'] = $categoryId;
        }
        return $this->get('schedule.production_requirements', [], $query);
    }

    public function kitchenQueue(?string $date = null, ?int $categoryId = null): array
    {
        $query = [];
        if ($date) {
            $query['date'] = $date;
        }
        if ($categoryId) {
            $query['category_id'] = $categoryId;
        }
        return $this->get('schedule.kitchen_queue', [], $query);
    }

    public function transferSchedule(string $deliveryDate, int $categoryId): array
    {
        return $this->post('schedule.transfer', [], [
            'delivery_date' => $deliveryDate,
            'category_id' => $categoryId,
        ]);
    }

    public function advanceSchedule(string $deliveryDate, int $categoryId, string $action, ?int $mealId = null): array
    {
        $data = [
            'delivery_date' => $deliveryDate,
            'category_id' => $categoryId,
            'action' => $action,
        ];
        if ($mealId) {
            $data['meal_id'] = $mealId;
        }
        return $this->patch('schedule.advance', [], $data);
    }
}
