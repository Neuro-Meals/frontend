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

    public function activityToday(): array
    {
        return $this->get('nutrition.activity_today');
    }

    /**
     * Create or update meal assignments for one delivery date.
     */
    public function createMealAssignments(
        array $payload
    ): array {
        return $this->post(
            'meal_assignments.create',
            [],
            $payload
        );
    }

    /**
     * Get meal assignments for one customer.
     */
    public function subscriptionMealAssignments(
        int $userId,
        ?int $subscriptionId = null
    ): array {
        $params = [
            'user_id' => $userId,
            'active_only' => true,
            'page' => 1,
            'page_size' => 100,
        ];

        if (
            $subscriptionId !== null
            && $subscriptionId > 0
        ) {
            $params['subscription_id'] =
                $subscriptionId;
        }

        return $this->get(
            'meal_assignments.customer',
            $params
        );
    }

    /**
     * Old MealSelection endpoint.
     *
     * Keep only while old pages still use it.
     */
    public function assignMeal(
        int $subscriptionId,
        array $data
    ): array {
        return $this->post(
            'nutrition.assign_meal',
            [
                'subscription_id' =>
                    $subscriptionId,
            ],
            $data
        );
    }

    /**
     * Old MealSelection endpoint.
     */
    public function subscriptionMealSelections(
        int $subscriptionId
    ): array {
        return $this->get(
            'nutrition.sub_meal_selections',
            [
                'subscription_id' =>
                    $subscriptionId,
            ]
        );
    }
}
