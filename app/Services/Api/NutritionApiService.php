<?php

namespace App\Services\Api;

class NutritionApiService extends BaseApiService
{
    public function today(): array
    {
        return $this->get('nutrition.today');
    }

    public function weekly(): array
    {
        return $this->get('nutrition.weekly');
    }

    public function weightHistory(): array
    {
        return $this->get('nutrition.weight_history');
    }

    public function assignMealDay(
    int $subscriptionId,
    array $payload
): array {
    return $this->post(
        "/meal-assignments/subscriptions/{$subscriptionId}/assign-day",
        $payload
    );
}

    public function subscriptionMealAssignments(
    int $userId,
    ?int $subscriptionId = null
): array {

    $params = [];

    if ($subscriptionId) {
        $params['subscription_id'] = $subscriptionId;
    }

    return $this->get(
        "/meal-assignments/user/{$userId}",
        $params
    );
}

    public function activityToday(): array
    {
        return $this->get('nutrition.activity_today');
    }

    public function assignMeal(int $subscriptionId, array $data): array
    {
        return $this->post('nutrition.assign_meal', ['subscription_id' => $subscriptionId], $data);
    }

    public function subscriptionMealSelections(int $subscriptionId): array
    {
        return $this->get('nutrition.sub_meal_selections', ['subscription_id' => $subscriptionId]);
    }
}