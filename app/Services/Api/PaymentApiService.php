<?php

namespace App\Services\Api;

class PaymentApiService extends BaseApiService
{
    public function createCheckout(int $subscriptionId): array
    {
        return $this->post('payments.create_checkout', [], [
            'subscription_id' => $subscriptionId,
        ]);
    }

    public function my(): array
    {
        return $this->get('payments.my');
    }

    public function list(array $query = []): array
    {
        return $this->get('payments.list', [], $query);
    }

    public function verifySession(string $sessionId): array
    {
        return $this->get('payments.verify_session', ['session_id' => $sessionId]);
    }
}
