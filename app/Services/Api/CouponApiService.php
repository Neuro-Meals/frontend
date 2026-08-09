<?php

namespace App\Services\Api;

class CouponApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('coupons.list', [], $query);
    }

    public function create(array $data): array
    {
        return $this->post('coupons.create', [], $data);
    }

    public function show(int $couponId): array
    {
        return $this->get('coupons.show', ['coupon_id' => $couponId]);
    }

    public function update(int $couponId, array $data): array
    {
        return $this->put('coupons.update', ['coupon_id' => $couponId], $data);
    }

    public function deleteCoupon(int $couponId): array
    {
        return $this->delete('coupons.delete', ['coupon_id' => $couponId]);
    }

    public function availability(int $subscriptionId): array
    {
        return $this->get('coupons.availability', [], [
            'subscription_id' => $subscriptionId,
        ]);
    }

    public function validateCode(string $code, float $amount, ?int $planId = null): array
    {
        $payload = [
            'code' => strtoupper(trim($code)),
            'amount' => $amount,
        ];

        if ($planId !== null) {
            $payload['plan_id'] = $planId;
        }

        return $this->post('coupons.validate', [], $payload);
    }

    public function redemptions(array $query = []): array
    {
        return $this->get('coupons.redemptions', [], $query);
    }
}
