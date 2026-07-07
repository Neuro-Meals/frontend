<?php

namespace App\Services\Api;

class ProfileApiService extends BaseApiService
{
    public function fetch(): array
    {
        return $this->get('profile.get');
    }

    public function update(array $data): array
    {
        return $this->put('profile.update', [], $data);
    }
}
