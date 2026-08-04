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
     /**
 * Create or update meal assignments for one delivery date.
 */
public function createMealAssignments(array $payload): array
{
    \Log::info('FastAPI meal assignment request', [
        'endpoint' => config('api.endpoints.meal_assignments.create'),
        'payload' => $payload,
    ]);

    $response = $this->post(
        'meal_assignments.create',
        [],
        $payload
    );

    \Log::info('FastAPI meal assignment response', [
        'response' => $response,
    ]);

    return $response;
}

    /**
     * Get every active meal assignment for one customer/subscription.
     *
     * The FastAPI endpoint is paginated. This method follows all pages so
     * a long subscription is not silently truncated after the first 100
     * assignment records.
     */
    public function subscriptionMealAssignments(
        int $userId,
        ?int $subscriptionId = null,
        ?string $dateFrom = null,
        ?string $dateTo = null
    ): array {
        $pageSize = 100;
        $page = 1;
        $maxPages = 50;
        $items = [];
        $lastResponse = [];

        do {
            $query = [
                'active_only' => true,
                'page' => $page,
                'page_size' => $pageSize,
            ];

            if ($subscriptionId !== null && $subscriptionId > 0) {
                $query['subscription_id'] = $subscriptionId;
            }

            if ($dateFrom) {
                $query['date_from'] = $dateFrom;
            }

            if ($dateTo) {
                $query['date_to'] = $dateTo;
            }

            $response = $this->get(
                'meal_assignments.customer',
                ['user_id' => $userId],
                $query
            );

            $lastResponse = is_array($response) ? $response : [];

            if (($lastResponse['success'] ?? true) === false) {
                return $lastResponse;
            }

            $pageItems = $lastResponse['items']
                ?? $lastResponse['data']
                ?? [];

            if (!is_array($pageItems)) {
                $pageItems = [];
            }

            $items = array_merge($items, array_values($pageItems));

            $reportedTotal = (int) (
                $lastResponse['total']
                ?? $lastResponse['total_count']
                ?? 0
            );

            $hasMoreFromTotal = $reportedTotal > count($items);
            $hasMoreFromPageSize = count($pageItems) === $pageSize;

            $page++;
        } while (
            $page <= $maxPages
            && ($hasMoreFromTotal || $hasMoreFromPageSize)
            && count($pageItems) > 0
        );

        return [
            'items' => $items,
            'total' => count($items),
            'page' => 1,
            'page_size' => count($items),
            'source' => $lastResponse,
        ];
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
