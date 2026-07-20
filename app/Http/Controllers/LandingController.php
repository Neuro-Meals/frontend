<?php

namespace App\Http\Controllers;

use App\Services\Api\MealApiService;
use App\Services\Api\PlanApiService;
use Illuminate\Support\Facades\App;

class LandingController extends Controller
{
    use \App\Services\Api\HasApiData;

    public function index(MealApiService $mealApi, PlanApiService $planApi)
    {
        $isAr = App::getLocale() === 'ar';

        $mealsData = $this->apiData($mealApi->list(['limit' => 6, 'is_available' => true]), fn () => []);
        $plansData = $this->apiData($planApi->list(['limit' => 100, 'is_active' => true]), fn () => []);

        $featuredMeals = [];
        foreach ($mealsData as $meal) {
            $featuredMeals[] = [
                'id' => $meal['id'] ?? 0,
                'name' => $isAr ? ($meal['name_ar'] ?? $meal['name_en'] ?? $meal['name'] ?? 'Meal') : ($meal['name_en'] ?? $meal['name'] ?? 'Meal'),
                'description' => $isAr ? ($meal['description_ar'] ?? $meal['description_en'] ?? $meal['description'] ?? '') : ($meal['description_en'] ?? $meal['description'] ?? ''),
                'calories' => $meal['calories'] ?? 0,
                'protein' => $meal['protein_g'] ?? 0,
                'carbs' => $meal['carbs_g'] ?? 0,
                'fat' => $meal['fat_g'] ?? 0,
                'image' => $meal['image_url'] ?? null,
                'category' => $isAr
                    ? ($meal['category']['name_ar'] ?? $meal['category']['name_en'] ?? $meal['category_name'] ?? 'Meal')
                    : ($meal['category']['name_en'] ?? $meal['category_name'] ?? 'Meal'),
                'tags' => $meal['diet_tags'] ?? [],
            ];
        }

        $plans = [];
        foreach ($plansData as $plan) {
            $plans[] = [
                'id' => $plan['id'] ?? 0,
                'name' => $isAr ? ($plan['name_ar'] ?? $plan['name_en'] ?? $plan['name'] ?? 'Plan') : ($plan['name_en'] ?? $plan['name'] ?? 'Plan'),
                'description' => $isAr ? ($plan['description_ar'] ?? $plan['description_en'] ?? $plan['description'] ?? '') : ($plan['description_en'] ?? $plan['description'] ?? ''),
                'price' => $plan['price'] ?? 0,
                'duration' => ($plan['duration_days'] ?? 28) . ' ' . __('days'),
                'calories' => $plan['calories'] ?? '',
                'meals' => $plan['total_meals'] ?? 0,
                'subscribers' => $plan['subscribers_count'] ?? 0,
                'features' => $plan['features'] ?? [],
                'color' => $plan['color'] ?? '#259B00',
            ];
        }

        $stats = [
            'mealsCount' => count($featuredMeals),
            'plansCount' => count($plans),
        ];

        return view('landing', compact('featuredMeals', 'plans', 'stats'));
    }
}
