<?php

namespace App\Services\Api;

class ReportsApiService extends BaseApiService
{
    public function summary(): array
    {
        return $this->get('reports.summary');
    }

    public function orders(): array
    {
        return $this->get('reports.orders');
    }

    public function subscriptions(): array
    {
        return $this->get('reports.subscriptions');
    }

    public function deliveries(): array
    {
        return $this->get('reports.deliveries');
    }

    public function revenue(): array
    {
        return $this->get('reports.revenue');
    }

    public function dashboard(): array
    {
        return $this->get('reports.dashboard');
    }
}
