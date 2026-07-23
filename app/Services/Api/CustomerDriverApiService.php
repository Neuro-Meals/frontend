<?php

namespace App\Services\Api;

class CustomerDriverApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('customer_drivers.list', [], $query);
    }

    public function assign(array $data): array
    {
        return $this->post('customer_drivers.assign', [], $data);
    }

    public function change(int $customerId, array $data): array
    {
        return $this->patch('customer_drivers.change', ['customer_id' => $customerId], $data);
    }

    public function remove(int $customerId): array
    {
        return $this->delete('customer_drivers.remove', ['customer_id' => $customerId]);
    }

    public function getForCustomer(int $customerId): array
    {
        return $this->get('customer_drivers.get', ['customer_id' => $customerId]);
    }

    public function history(int $customerId): array
    {
        return $this->get('customer_drivers.history', ['customer_id' => $customerId]);
    }

    public function driverCustomers(int $driverId): array
    {
        return $this->get('customer_drivers.driver_customers', ['driver_id' => $driverId]);
    }

    public function myCustomers(): array
    {
        return $this->get('customer_drivers.my_customers');
    }
}
