<?php

namespace App\Services\Api;

class PlanApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('plans.list', [], $query);
    }

    public function create(array $data): array
    {
        return $this->post('plans.create', [], $data);
    }

    public function show(int $planId): array
    {
        return $this->get('plans.show', ['plan_id' => $planId]);
    }

    public function update(int $planId, array $data): array
    {
        return $this->put('plans.update', ['plan_id' => $planId], $data);
    }

    public function destroy(int $planId): array
    {
        return $this->delete('plans.delete', ['plan_id' => $planId]);
    }
}
