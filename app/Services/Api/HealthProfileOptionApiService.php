<?php

namespace App\Services\Api;

class HealthProfileOptionApiService extends BaseApiService
{
    public function adminList(array $query = []): array
    {
        return $this->get('health_profile_options.admin_list', [], $query);
    }

    public function publicList(): array
    {
        return $this->get('health_profile_options.public');
    }

    public function create(array $data): array
    {
        return $this->post('health_profile_options.admin_create', [], $data);
    }

    public function update(int $optionId, array $data): array
    {
        return $this->put(
            'health_profile_options.admin_update',
            ['option_id' => $optionId],
            $data
        );
    }

    public function updateStatus(int $optionId, bool $isActive): array
    {
        return $this->patch(
            'health_profile_options.admin_status',
            ['option_id' => $optionId],
            ['is_active' => $isActive]
        );
    }

    public function destroy(int $optionId): array
    {
        return $this->delete(
            'health_profile_options.admin_delete',
            ['option_id' => $optionId]
        );
    }
}
