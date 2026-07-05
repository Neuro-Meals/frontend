<?php

namespace App\Services\Api;

class ProfileApiService extends BaseApiService
{
    public function get(): array
    {
        return $this->get('profile.get');
    }

    public function update(array $data): array
    {
        return $this->put('profile.update', [], $data);
    }
}
