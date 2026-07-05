<?php

namespace App\Services\Api;

class UserApiService extends BaseApiService
{
    // ─── Users ───

    public function me(): array
    {
        return $this->get('users.me');
    }

    public function list(array $query = []): array
    {
        return $this->get('users.list', [], $query);
    }

    public function updateRole(int $userId, string $role): array
    {
        return $this->patch('users.update_role', ['user_id' => $userId], [
            'role' => $role,
        ]);
    }
}
