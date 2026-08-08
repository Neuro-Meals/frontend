<?php

namespace App\Services\Api;

class ReferralApiService extends BaseApiService
{
    public function my(): array
    {
        return $this->get('referrals.my');
    }

    public function adminList(array $query = []): array
    {
        return $this->get('referrals.admin_list', [], $query);
    }

    public function settings(): array
    {
        return $this->get('referrals.admin_settings');
    }

    public function updateSettings(array $data): array
    {
        return $this->patch('referrals.update_settings', [], $data);
    }
}
