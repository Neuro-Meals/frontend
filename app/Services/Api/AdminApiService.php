<?php

namespace App\Services\Api;

class AdminApiService extends BaseApiService
{
    // ─── Users (Admin manages users via /users endpoints) ───

    public function usersList(array $query = []): array
    {
        return $this->get('users.list', [], $query);
    }

    public function userShow(int $userId): array
    {
        return $this->get('users.show', ['user_id' => $userId]);
    }

    public function updateUserRole(int $userId, string $role): array
    {
        return $this->patch('users.update_role', ['user_id' => $userId], [
            'role' => $role,
        ]);
    }

    public function userUpdate(int $userId, array $data): array
    {
        return $this->put('users.update', ['user_id' => $userId], $data);
    }

    public function userDelete(int $userId): array
    {
        return $this->delete('users.delete', ['user_id' => $userId]);
    }

    // ─── Meals ───

    public function mealsList(array $query = []): array
    {
        return $this->get('meals.list', [], $query);
    }

    public function mealShow(int $mealId): array
    {
        return $this->get('meals.show', ['meal_id' => $mealId]);
    }

    public function mealCreate(array $data): array
    {
        return $this->post('meals.create', [], $data);
    }

    public function mealUpdate(int $mealId, array $data): array
    {
        return $this->put('meals.update', ['meal_id' => $mealId], $data);
    }

    public function mealDelete(int $mealId): array
    {
        return $this->delete('meals.delete', ['meal_id' => $mealId]);
    }

    // ─── Meal Categories ───

    public function mealCategories(array $query = []): array
    {
        return $this->get('meal_categories.list', [], $query);
    }

    public function mealCategoryCreate(array $data): array
    {
        return $this->post('meal_categories.create', [], $data);
    }

    public function mealCategoryUpdate(int $categoryId, array $data): array
    {
        return $this->put('meal_categories.update', ['category_id' => $categoryId], $data);
    }

    public function mealCategoryDelete(int $categoryId): array
    {
        return $this->delete('meal_categories.delete', ['category_id' => $categoryId]);
    }

    // ─── Meal Plans ───

    public function plansList(array $query = []): array
    {
        return $this->get('plans.list', [], $query);
    }

    public function planShow(int $planId): array
    {
        return $this->get('plans.show', ['plan_id' => $planId]);
    }

    public function planCreate(array $data): array
    {
        return $this->post('plans.create', [], $data);
    }

    public function planUpdate(int $planId, array $data): array
    {
        return $this->put('plans.update', ['plan_id' => $planId], $data);
    }

    public function planDelete(int $planId): array
    {
        return $this->delete('plans.delete', ['plan_id' => $planId]);
    }

    // ─── Subscriptions ───

    public function subscriptionsList(array $query = []): array
    {
        return $this->get('subscriptions.list', [], $query);
    }

    public function subscriptionShow(int $subscriptionId): array
    {
        return $this->get('subscriptions.show', ['subscription_id' => $subscriptionId]);
    }

    public function subscriptionCreate(array $data): array
    {
        return $this->post('subscriptions.create', [], $data);
    }

    public function subscriptionUpdate(int $subscriptionId, array $data): array
    {
        return $this->patch('subscriptions.update', ['subscription_id' => $subscriptionId], $data);
    }

    public function subscriptionCancel(int $subscriptionId): array
    {
        return $this->post('subscriptions.cancel', ['subscription_id' => $subscriptionId]);
    }

    // ─── RBAC ───

    public function rolesList(): array
    {
        return $this->get('rbac.roles');
    }

    public function roleCreate(array $data): array
    {
        return $this->post('rbac.roles', [], $data);
    }

    public function permissionsList(): array
    {
        return $this->get('rbac.permissions');
    }

    public function permissionCreate(array $data): array
    {
        return $this->post('rbac.permissions', [], $data);
    }

    public function assignRole(array $data): array
    {
        return $this->post('rbac.assign_role', [], $data);
    }

    public function assignPermission(array $data): array
    {
        return $this->post('rbac.assign_permission', [], $data);
    }

    // ─── Chef Management ───

    public function chefsList(array $query = []): array
    {
        return $this->get('admin_chefs.list', [], $query);
    }

    public function chefShow(int $chefId): array
    {
        return $this->get('admin_chefs.show', ['chef_id' => $chefId]);
    }

    public function chefCreate(array $data): array
    {
        return $this->post('admin_chefs.create', [], $data);
    }

    public function chefUpdate(int $chefId, array $data): array
    {
        return $this->patch('admin_chefs.update', ['chef_id' => $chefId], $data);
    }

    public function chefActivate(int $chefId): array
    {
        return $this->patch('admin_chefs.activate', ['chef_id' => $chefId]);
    }

    public function chefDeactivate(int $chefId): array
    {
        return $this->patch('admin_chefs.deactivate', ['chef_id' => $chefId]);
    }

    public function chefAssignExistingUser(int $userId): array
    {
        return $this->post('admin_chefs.assign_existing', [], ['user_id' => $userId]);
    }

    public function chefRemoveRole(int $chefId): array
    {
        return $this->patch('admin_chefs.remove_role', ['chef_id' => $chefId]);
    }
}
