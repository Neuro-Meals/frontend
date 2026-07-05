<?php

namespace App\Services\Api;

class MealApiService extends BaseApiService
{
    // ─── Meals ───

    public function list(array $query = []): array
    {
        return $this->get('meals.list', [], $query);
    }

    public function create(array $data): array
    {
        return $this->post('meals.create', [], $data);
    }

    public function show(int $mealId): array
    {
        return $this->get('meals.show', ['meal_id' => $mealId]);
    }

    public function update(int $mealId, array $data): array
    {
        return $this->put('meals.update', ['meal_id' => $mealId], $data);
    }

    public function delete(int $mealId): array
    {
        return $this->delete('meals.delete', ['meal_id' => $mealId]);
    }

    // ─── Meal Categories ───

    public function categoriesList(array $query = []): array
    {
        return $this->get('meal_categories.list', [], $query);
    }

    public function categoryCreate(array $data): array
    {
        return $this->post('meal_categories.create', [], $data);
    }

    public function categoryShow(int $categoryId): array
    {
        return $this->get('meal_categories.show', ['category_id' => $categoryId]);
    }

    public function categoryUpdate(int $categoryId, array $data): array
    {
        return $this->put('meal_categories.update', ['category_id' => $categoryId], $data);
    }

    public function categoryDelete(int $categoryId): array
    {
        return $this->delete('meal_categories.delete', ['category_id' => $categoryId]);
    }
}
