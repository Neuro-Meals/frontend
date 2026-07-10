<?php

namespace App\Services\Api;

class MealSelectionApiService extends BaseApiService
{
    public function my(?int $subscriptionId = null): array
    {
        $query = [];
        if ($subscriptionId !== null) {
            $query['subscription_id'] = $subscriptionId;
        }
        return $this->get('meal_selections.my', [], $query);
    }

    public function create(array $data): array
    {
        return $this->post('meal_selections.create', [], $data);
    }

    public function update(int $selectionId, array $data): array
    {
        return $this->put('meal_selections.update', ['selection_id' => $selectionId], $data);
    }

    public function destroy(int $selectionId): array
    {
        return $this->delete('meal_selections.delete', ['selection_id' => $selectionId]);
    }
}
