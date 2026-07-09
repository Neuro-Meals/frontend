<?php

namespace App\Services\Api;

class DriverApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('drivers.list', [], $query);
    }

    public function show(int $id): array
    {
        return $this->get('drivers.show', ['driver_id' => $id]);
    }

    public function create(array $data): array
    {
        return $this->post('drivers.create', [], $data);
    }

    public function update(int $id, array $data): array
    {
        return $this->put('drivers.update', ['driver_id' => $id], $data);
    }

    public function destroy(int $id): array
    {
        return $this->delete('drivers.delete', ['driver_id' => $id]);
    }
}
