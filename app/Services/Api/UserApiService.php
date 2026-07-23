<?php

namespace App\Services\Api;

class UserApiService extends BaseApiService
{
    // ─── Users ───

    public function me(): array
    {
        return $this->get('users.me');
    }

    public function getCompleteProfile(): array
    {
        return $this->get('users.me_profile');
    }

    public function updateCompleteProfile(array $data): array
    {
        return $this->put('users.me_profile', [], $data);
    }

    public function list(array $query = []): array
    {
        return $this->get('users.list', [], $query);
    }

    public function show(int $userId): array
    {
        return $this->get('users.show', ['user_id' => $userId]);
    }

    public function updateRole(int $userId, string $role): array
    {
        return $this->patch('users.update_role', ['user_id' => $userId], [
            'role' => $role,
        ]);
    }
}
