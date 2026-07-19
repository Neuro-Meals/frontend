<?php

namespace App\Services\Api;

class SubscriptionApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('subscriptions.list', [], $query);
    }

    public function create(array $data): array
    {
        return $this->post('subscriptions.create', [], $data);
    }

    public function adminCreate(array $data): array
    {
        return $this->post('subscriptions.admin_create', [], $data);
    }

    public function my(): array
    {
        return $this->get('subscriptions.my');
    }

    public function currentDetails(): array
    {
        return $this->get('subscriptions.current_details');
    }

    public function show(int $subscriptionId): array
    {
        return $this->get('subscriptions.show', ['subscription_id' => $subscriptionId]);
    }

    public function update(int $subscriptionId, array $data): array
    {
        return $this->patch('subscriptions.update', ['subscription_id' => $subscriptionId], $data);
    }

    public function cancel(int $subscriptionId): array
    {
        return $this->post('subscriptions.cancel', ['subscription_id' => $subscriptionId]);
    }

    public function pause(int $subscriptionId, ?string $reason = null): array
    {
        return $this->post('subscriptions.pause', ['subscription_id' => $subscriptionId], [
            'reason' => $reason,
        ]);
    }

    public function resume(int $subscriptionId): array
    {
        return $this->post('subscriptions.resume', ['subscription_id' => $subscriptionId]);
    }

    public function changePlan(int $subscriptionId, int $newPlanId): array
    {
        return $this->post('subscriptions.change_plan', ['subscription_id' => $subscriptionId], [
            'new_plan_id' => $newPlanId,
        ]);
    }
}
