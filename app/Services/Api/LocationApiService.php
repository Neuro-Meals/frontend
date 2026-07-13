<?php

namespace App\Services\Api;

class LocationApiService extends BaseApiService
{
    public function list(array $query = []): array
    {
        return $this->get('locations.list', [], $query);
    }

    public function regions(): array
    {
        return $this->get('locations.regions');
    }

    public function region(string $regionCode): array
    {
        return $this->get('locations.region', ['region_code' => $regionCode]);
    }

    public function regionCities(string $regionCode): array
    {
        return $this->get('locations.region_cities', ['region_code' => $regionCode]);
    }

    public function validate(string $regionCode, string $cityCode): array
    {
        return $this->get('locations.validate', [], [
            'region_code' => $regionCode,
            'city_code' => $cityCode,
        ]);
    }
}
