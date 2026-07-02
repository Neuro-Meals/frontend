<?php

namespace App\Services\Api;

class UserApiService extends BaseApiService
{
    // ─── Dashboard ───

    public function dashboardStats(): array
    {
        return $this->get('user.dashboard.stats');
    }

    public function dashboardWeeklyProgress(): array
    {
        return $this->get('user.dashboard.weekly_progress');
    }

    public function dashboardNextDelivery(): array
    {
        return $this->get('user.dashboard.next_delivery');
    }

    public function dashboardWeightTracking(): array
    {
        return $this->get('user.dashboard.weight_tracking');
    }

    // ─── Subscriptions ───

    public function subscriptionsList(array $query = []): array
    {
        return $this->get('user.subscriptions.list', [], $query);
    }

    public function subscriptionActive(): array
    {
        return $this->get('user.subscriptions.active');
    }

    public function subscriptionHistory(): array
    {
        return $this->get('user.subscriptions.history');
    }

    // ─── Meals ───

    public function mealsList(array $query = []): array
    {
        return $this->get('user.meals.list', [], $query);
    }

    public function mealsToday(): array
    {
        return $this->get('user.meals.today');
    }

    public function mealFavorites(): array
    {
        return $this->get('user.meals.favorite');
    }

    // ─── Nutrition ───

    public function nutritionSummary(): array
    {
        return $this->get('user.nutrition.summary');
    }

    public function nutritionLog(array $query = []): array
    {
        return $this->get('user.nutrition.log', [], $query);
    }

    public function nutritionTargets(): array
    {
        return $this->get('user.nutrition.targets');
    }

    // ─── Orders ───

    public function ordersList(array $query = []): array
    {
        return $this->get('user.orders.list', [], $query);
    }

    public function orderShow(int $id): array
    {
        return $this->get('user.orders.show', ['id' => $id]);
    }

    // ─── Delivery ───

    public function deliveryCurrent(): array
    {
        return $this->get('user.delivery.current');
    }

    public function deliveryHistory(): array
    {
        return $this->get('user.delivery.history');
    }

    public function deliveryTrack(int $id): array
    {
        return $this->get('user.delivery.track', ['id' => $id]);
    }

    // ─── Notifications ───

    public function notificationsList(array $query = []): array
    {
        return $this->get('user.notifications.list', [], $query);
    }

    public function notificationRead(int $id): array
    {
        return $this->post('user.notifications.read', ['id' => $id]);
    }

    // ─── Settings ───

    public function settingsGet(): array
    {
        return $this->get('user.settings.get');
    }

    public function settingsUpdate(array $data): array
    {
        return $this->put('user.settings.update', [], $data);
    }
}
