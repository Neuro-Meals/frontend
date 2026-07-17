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

    public function createPlanChangeCheckout(int $planChangeId): array
    {
        return $this->post('payments.create_plan_change_checkout', [], [
            'plan_change_id' => $planChangeId,
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

    public function verifyCharge(string $chargeId): array
    {
        return $this->get('payments.verify_charge', ['charge_id' => $chargeId]);
    }

    public function attachMoyasarPayment(int $localPaymentId, string $moyasarPaymentId): array
    {
        return $this->post('payments.attach_moyasar_payment', [], [
            'local_payment_id' => $localPaymentId,
            'moyasar_payment_id' => $moyasarPaymentId,
        ]);
    }

    public function verifyPayment(int $paymentId): array
    {
        return $this->get('payments.verify_payment', ['payment_id' => $paymentId]);
    }
}
