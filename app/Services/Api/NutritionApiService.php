<?php

namespace App\Services\Api;

class NutritionApiService extends BaseApiService
{
    public function today(): array
    {
        return $this->get('nutrition.today');
    }

    public function weekly(): array
    {
        return $this->get('nutrition.weekly');
    }

    public function weightHistory(): array
    {
        return $this->get('nutrition.weight_history');
    }

    public function activityToday(): array
    {
        return $this->get('nutrition.activity_today');
    }
}
