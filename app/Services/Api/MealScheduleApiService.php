<?php

namespace App\Services\Api;

class MealScheduleApiService extends BaseApiService
{
    public function my(): array
    {
        return $this->get('meal_schedule.my');
    }

    public function myToday(): array
    {
        return $this->get('meal_schedule.my_today');
    }
}
