<?php

namespace App\Services\Api;

class PlanMenuApiService extends BaseApiService
{
    public function create(array $data): array
    {
        return $this->post('plan_menus.create', [], $data);
    }

    public function weekly(int $planId): array
    {
        return $this->get('plan_menus.weekly', ['plan_id' => $planId]);
    }

    public function list(int $planId, array $query = []): array
    {
        return $this->get('plan_menus.list', ['plan_id' => $planId], $query);
    }

    public function update(int $menuItemId, array $data): array
    {
        return $this->patch('plan_menus.update', ['menu_item_id' => $menuItemId], $data);
    }

    public function delete(int $menuItemId): array
    {
        return $this->delete('plan_menus.delete', ['menu_item_id' => $menuItemId]);
    }
}
