<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\AuthApiService;
use App\Services\Api\DeliveryApiService;
use App\Services\Api\HasApiData;
use App\Services\Api\MealApiService;
use App\Services\Api\MealScheduleApiService;
use App\Services\Api\NotificationApiService;
use App\Services\Api\NutritionApiService;
use App\Services\Api\OrderApiService;
use App\Services\Api\PlanApiService;
use App\Services\Api\ProfileApiService;
use App\Services\Api\SubscriptionApiService;

class UserController extends Controller
{
    use HasApiData;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authApi = app(AuthApiService::class);
            if (!$authApi->check()) {
                return redirect()->route('login');
            }
            return $next($request);
        });
    }

    public function dashboard(Request $request, AuthApiService $authApi, MealApiService $mealApi, SubscriptionApiService $subscriptionApi)
    {
        $user = $this->apiData($authApi->me(), function () use ($authApi) {
            return $authApi->user() ?? [];
        });

        // Refresh session user data when API returns fresh data
        if (!empty($user['id'])) {
            session(['api_user' => $user]);
        }

        $subscription = $this->apiData($subscriptionApi->my(), function () {
            return [];
        });

        $meals = $this->apiData($mealApi->list(['limit' => 20]), function () {
            return [];
        });

        $plan = $user['subscription'] ?? $subscription;

        $planName = $plan['plan_name'] ?? $plan['name'] ?? 'Active Plan';
        $calorieTarget = $plan['calories'] ?? $plan['calorie_target'] ?? 1800;
        $proteinTarget = $plan['protein_target'] ?? 140;
        $carbsTarget = $plan['carbs_target'] ?? 200;
        $fatTarget = $plan['fat_target'] ?? 55;

        $upcomingMeals = [];
        foreach (array_slice($meals, 0, 3) as $meal) {
            $upcomingMeals[] = [
                'name' => $meal['name'] ?? 'Meal',
                'time' => $meal['meal_time'] ?? ($meal['time'] ?? 'Upcoming'),
                'calories' => $meal['calories'] ?? 0,
                'image' => $meal['image'] ?? 'whitelogo.png',
            ];
        }

        // Fallback to mock meals if API returns nothing
        if (empty($upcomingMeals)) {
            $upcomingMeals = [
                ['name' => 'Grilled Chicken Bowl', 'time' => 'Today · Lunch', 'calories' => 520, 'image' => 'grilled-chicken-breast-rice-berry-vegetables-white-background_1428-2141.jpg'],
                ['name' => 'Quinoa Buddha Bowl', 'time' => 'Today · Dinner', 'calories' => 480, 'image' => 'healthy-buddha-bowl-with-sliced-meat-fresh-vegetables_9975-132258.jpg'],
                ['name' => 'Protein Breakfast Plate', 'time' => 'Tomorrow · Breakfast', 'calories' => 410, 'image' => 'healthy-protein-bowl-with-quinoa-avocado-kale-sweet-potato-poached-egg_9975-132760.jpg'],
            ];
        }

        $weeklyProgress = $this->buildWeeklyProgress($meals, (int) $calorieTarget);
        $recentOrders = $this->buildRecentOrders($subscription);

        $currentWeight = $user['weight_kg'] ?? 78.2;
        $weightGoal = $user['fitness_goal'] === 'weight_loss' ? $currentWeight - 5 : ($user['fitness_goal'] === 'muscle_gain' ? $currentWeight + 3 : $currentWeight);
        $weightStart = $currentWeight + (($user['fitness_goal'] === 'weight_loss' ? 4.3 : -2) * -1);

        $stats = [
            'activePlan' => $planName,
            'planPrice' => $plan['amount'] ?? $plan['price'] ?? 420,
            'planRenewal' => !empty($plan['end_date']) ? date('M d, Y', strtotime($plan['end_date'])) : 'N/A',
            'mealsThisWeek' => min(count($meals), 21),
            'mealsTotal' => $plan['meals_total'] ?? 84,
            'totalOrders' => $subscription['orders_count'] ?? 0,
            'dailyCalories' => $this->calculateTodayCalories($meals),
            'calorieTarget' => (int) $calorieTarget,
            'proteinTarget' => (int) $proteinTarget,
            'proteinToday' => $this->calculateTodayMacro($meals, 'protein'),
            'carbsTarget' => (int) $carbsTarget,
            'carbsToday' => $this->calculateTodayMacro($meals, 'carbs'),
            'fatTarget' => (int) $fatTarget,
            'fatToday' => $this->calculateTodayMacro($meals, 'fat'),
            'streakDays' => $user['streak_days'] ?? 0,
            'nextDelivery' => $plan['next_delivery'] ?? 'Tomorrow, 09:00 - 10:00',
            'nextMeal' => $upcomingMeals[0]['name'] ?? 'Grilled Chicken Bowl',
            'weightStart' => (float) $weightStart,
            'weightCurrent' => (float) $currentWeight,
            'weightGoal' => (float) $weightGoal,
        ];

        return view('user.dashboard', compact('user', 'stats', 'weeklyProgress', 'upcomingMeals', 'recentOrders'));
    }

    /**
     * Build 7-day calorie progress from meals.
     */
    private function buildWeeklyProgress(array $meals, int $target): array
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $progress = [];

        foreach ($days as $index => $day) {
            $calories = 0;
            foreach ($meals as $meal) {
                $mealDay = (int) ($meal['day_index'] ?? ($meal['day'] ?? $index));
                if ($mealDay === $index) {
                    $calories += (int) ($meal['calories'] ?? 0);
                }
            }
            $progress[] = [
                'day' => $day,
                'calories' => $calories ?: $target - rand(100, 300),
                'target' => $target,
            ];
        }

        return $progress;
    }

    /**
     * Build recent orders from subscription data.
     */
    private function buildRecentOrders(array $subscription): array
    {
        $orders = $subscription['orders'] ?? [];
        if (!empty($orders)) {
            $recent = [];
            foreach (array_slice($orders, 0, 4) as $order) {
                $recent[] = [
                    'id' => $order['id'] ?? ('ORD-' . rand(1000, 9999)),
                    'plan' => $order['plan_name'] ?? 'Active Plan',
                    'amount' => $order['amount'] ?? 0,
                    'status' => $order['status'] ?? 'delivered',
                    'date' => $order['date'] ?? date('Y-m-d'),
                ];
            }
            return $recent;
        }

        return [
            ['id' => 'ORD-2401', 'plan' => 'Active Plan', 'amount' => 420, 'status' => 'delivered', 'date' => date('Y-m-d', strtotime('-3 days'))],
            ['id' => 'ORD-2387', 'plan' => 'Active Plan', 'amount' => 420, 'status' => 'delivered', 'date' => date('Y-m-d', strtotime('-4 days'))],
            ['id' => 'ORD-2372', 'plan' => 'Active Plan', 'amount' => 420, 'status' => 'delivered', 'date' => date('Y-m-d', strtotime('-5 days'))],
            ['id' => 'ORD-2358', 'plan' => 'Active Plan', 'amount' => 420, 'status' => 'delivered', 'date' => date('Y-m-d', strtotime('-6 days'))],
        ];
    }

    /**
     * Calculate total calories for today's meals.
     */
    private function calculateTodayCalories(array $meals): int
    {
        $total = 0;
        foreach ($meals as $meal) {
            $isToday = ($meal['is_today'] ?? false) === true || ($meal['day'] ?? '') === 'Today';
            if ($isToday || empty($meal['date']) || $meal['date'] === date('Y-m-d')) {
                $total += (int) ($meal['calories'] ?? 0);
            }
        }
        return $total ?: 1650;
    }

    /**
     * Calculate today's macro total (protein, carbs, fat).
     */
    private function calculateTodayMacro(array $meals, string $macro): int
    {
        $total = 0;
        foreach ($meals as $meal) {
            $isToday = ($meal['is_today'] ?? false) === true || ($meal['day'] ?? '') === 'Today';
            if ($isToday || empty($meal['date']) || $meal['date'] === date('Y-m-d')) {
                $total += (int) ($meal[$macro] ?? 0);
            }
        }

        $defaults = ['protein' => 95, 'carbs' => 165, 'fat' => 38];
        return $total ?: $defaults[$macro] ?? 0;
    }

    public function subscriptions(PlanApiService $planApi, SubscriptionApiService $subscriptionApi)
    {
        $mySubscriptions = $this->apiData($subscriptionApi->my(), function () {
            return [];
        });

        $plans = $this->apiData($planApi->list(['limit' => 100]), function () {
            return [];
        });

        // Index plans by ID for quick lookup
        $plansById = [];
        foreach ($plans as $plan) {
            $plansById[$plan['id'] ?? 0] = $plan;
        }

        // Build active plan from the first active subscription
        $activeSubscription = null;
        foreach ($mySubscriptions as $sub) {
            if (($sub['status'] ?? '') === 'active') {
                $activeSubscription = $sub;
                break;
            }
        }
        if (!$activeSubscription && !empty($mySubscriptions)) {
            $activeSubscription = $mySubscriptions[0];
        }

        $activePlanDetails = $activeSubscription ? ($plansById[$activeSubscription['plan_id'] ?? 0] ?? []) : [];

        $activePlan = [
            'name' => $activePlanDetails['name_en'] ?? 'Active Plan',
            'price' => $activePlanDetails['price'] ?? ($activeSubscription['amount'] ?? 0),
            'duration' => ($activePlanDetails['duration_days'] ?? 28) . ' days',
            'status' => $activeSubscription['status'] ?? 'active',
            'started' => !empty($activeSubscription['start_date']) ? date('Y-m-d', strtotime($activeSubscription['start_date'])) : 'N/A',
            'renewal' => !empty($activeSubscription['end_date']) ? date('M d, Y', strtotime($activeSubscription['end_date'])) : 'N/A',
            'mealsRemaining' => max(0, ($activePlanDetails['total_meals'] ?? 0) - ($activeSubscription['meals_consumed'] ?? 0)),
            'mealsTotal' => max(1, $activePlanDetails['total_meals'] ?? 0),
            'calories' => $activePlanDetails['calories'] ?? '1500-1800',
            'color' => '#259B00',
        ];

        // Fallback if no active subscription
        if (empty($activeSubscription)) {
            $activePlan = [
                'name' => 'No Active Plan',
                'price' => 0,
                'duration' => '-',
                'status' => 'none',
                'started' => 'N/A',
                'renewal' => 'N/A',
                'mealsRemaining' => 0,
                'mealsTotal' => 1,
                'calories' => '-',
                'color' => '#6b7280',
            ];
        }

        // Build available plans list
        $availablePlans = [];
        $activePlanId = $activeSubscription['plan_id'] ?? null;
        $colors = ['#259B00', '#033133', '#f9ac00', '#3b82f6', '#6E7A25', '#025C5F'];
        $colorIndex = 0;

        foreach ($plans as $plan) {
            $planId = $plan['id'] ?? 0;
            $isCurrent = $planId && $planId === $activePlanId;
            $availablePlans[] = [
                'id' => $planId,
                'name' => $plan['name_en'] ?? 'Plan',
                'price' => $plan['price'] ?? 0,
                'duration' => ($plan['duration_days'] ?? 28) . ' days',
                'calories' => $plan['calories'] ?? '1500-1800',
                'subscribers' => $plan['subscribers_count'] ?? rand(50, 150),
                'color' => $colors[$colorIndex % count($colors)],
                'current' => $isCurrent,
            ];
            $colorIndex++;
        }

        // Fallback if API returns no plans
        if (empty($availablePlans)) {
            $availablePlans = [
                ['id' => 1, 'name' => 'Weight Loss Pro', 'price' => 420, 'duration' => '4 weeks', 'calories' => '1500-1800', 'subscribers' => 128, 'color' => '#259B00', 'current' => true],
                ['id' => 2, 'name' => 'Muscle Gain', 'price' => 380, 'duration' => '4 weeks', 'calories' => '2500-3000', 'subscribers' => 94, 'color' => '#033133', 'current' => false],
                ['id' => 3, 'name' => 'Maintenance', 'price' => 295, 'duration' => '4 weeks', 'calories' => '2000-2200', 'subscribers' => 76, 'color' => '#f9ac00', 'current' => false],
                ['id' => 4, 'name' => 'Keto Premium', 'price' => 510, 'duration' => '4 weeks', 'calories' => '1800-2000', 'subscribers' => 44, 'color' => '#3b82f6', 'current' => false],
            ];
        }

        // Build subscription history
        $history = [];
        foreach ($mySubscriptions as $sub) {
            $plan = $plansById[$sub['plan_id'] ?? 0] ?? [];
            $history[] = [
                'plan' => $plan['name_en'] ?? 'Unknown Plan',
                'period' => !empty($sub['start_date']) ? date('M Y', strtotime($sub['start_date'])) : 'N/A',
                'status' => $sub['status'] ?? 'unknown',
                'amount' => $sub['amount'] ?? 0,
            ];
        }

        if (empty($history)) {
            $history = [
                ['plan' => 'Weight Loss Pro', 'period' => date('M Y'), 'status' => 'active', 'amount' => 420],
            ];
        }

        return view('user.subscriptions', compact('activePlan', 'availablePlans', 'history'));
    }

    public function subscribe(Request $request, SubscriptionApiService $subscriptionApi)
    {
        $planId = (int) $request->input('plan_id');

        if ($planId <= 0) {
            return redirect()->route('user.subscriptions')->with('error', 'Invalid plan selected.');
        }

        $result = $this->apiData($subscriptionApi->create(['plan_id' => $planId]), function () {
            return [];
        });

        if (empty($result) || !empty($result['error'])) {
            return redirect()->route('user.subscriptions')->with('error', 'Failed to subscribe. Please try again.');
        }

        return redirect()->route('user.subscriptions')->with('success', 'Subscription created successfully!');
    }

    public function meals(MealApiService $mealApi, SubscriptionApiService $subscriptionApi, PlanApiService $planApi)
    {
        $meals = $this->apiData($mealApi->list(['limit' => 100, 'is_available' => true]), function () {
            return [];
        });

        $mySubscriptions = $this->apiData($subscriptionApi->my(), function () {
            return [];
        });

        $activeSubscription = null;
        foreach ($mySubscriptions as $sub) {
            if (($sub['status'] ?? '') === 'active') {
                $activeSubscription = $sub;
                break;
            }
        }

        // Fetch plan details to get meals_per_day and total_meals
        $planDetails = [];
        if ($activeSubscription && !empty($activeSubscription['plan_id'])) {
            $planDetails = $this->apiData($planApi->show($activeSubscription['plan_id']), function () {
                return [];
            });
        }

        $totalPlanMeals = $planDetails['total_meals'] ?? 84;
        $mealsPerDay = $planDetails['meals_per_day'] ?? 3;
        $mealsConsumed = $activeSubscription['meals_consumed'] ?? 0;

        // Map API meals to view format
        $todayMeals = [];
        $mealTimes = ['Breakfast · 07:30', 'Lunch · 12:30', 'Dinner · 19:00'];
        $timeIndex = 0;
        foreach (array_slice($meals, 0, $mealsPerDay) as $index => $meal) {
            $todayMeals[] = [
                'name' => $meal['name_en'] ?? 'Meal',
                'time' => $meal['meal_time'] ?? ($mealTimes[$timeIndex % 3]),
                'calories' => (int) ($meal['calories'] ?? 0),
                'protein' => (int) ($meal['protein_g'] ?? 0),
                'carbs' => (int) ($meal['carbs_g'] ?? 0),
                'fat' => (int) ($meal['fat_g'] ?? 0),
                'status' => ($index === 0 && $timeIndex === 0) ? 'upcoming' : 'delivered',
                'image' => $meal['image_url'] ?? 'whitelogo.png',
            ];
            $timeIndex++;
        }

        if (empty($todayMeals)) {
            $todayMeals = [
                ['name' => 'Protein Breakfast Plate', 'time' => 'Breakfast · 07:30', 'calories' => 410, 'protein' => 35, 'carbs' => 28, 'fat' => 14, 'status' => 'delivered', 'image' => 'healthy-protein-bowl-with-quinoa-avocado-kale-sweet-potato-poached-egg_9975-132760.jpg'],
                ['name' => 'Grilled Chicken Bowl', 'time' => 'Lunch · 12:30', 'calories' => 520, 'protein' => 45, 'carbs' => 38, 'fat' => 18, 'status' => 'delivered', 'image' => 'grilled-chicken-breast-rice-berry-vegetables-white-background_1428-2141.jpg'],
                ['name' => 'Quinoa Buddha Bowl', 'time' => 'Dinner · 19:00', 'calories' => 480, 'protein' => 22, 'carbs' => 62, 'fat' => 16, 'status' => 'upcoming', 'image' => 'healthy-buddha-bowl-with-sliced-meat-fresh-vegetables_9975-132258.jpg'],
            ];
        }

        // Build weekly schedule from meals
        $weekMeals = $this->buildWeeklyMeals($meals, $mealsPerDay, $totalPlanMeals);

        // Calculate stats
        $totalThisWeek = min(count($meals), $mealsPerDay * 7);
        $avgCalories = $this->calculateAvgCalories($meals);
        $favoriteMeal = $this->findFavoriteMeal($meals);

        $stats = [
            'totalThisWeek' => $totalThisWeek,
            'totalPlan' => $totalPlanMeals,
            'remaining' => max(0, $totalPlanMeals - $mealsConsumed),
            'avgCalories' => $avgCalories,
            'favoriteMeal' => $favoriteMeal['name'] ?? 'Grilled Chicken Bowl',
            'favoriteCount' => $favoriteMeal['count'] ?? 0,
        ];

        return view('user.meals', compact('todayMeals', 'weekMeals', 'stats'));
    }

    /**
     * Build weekly meal schedule from API meals.
     */
    private function buildWeeklyMeals(array $meals, int $mealsPerDay, int $totalPlanMeals): array
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weekly = [];

        foreach ($days as $index => $day) {
            $dayMeals = array_slice($meals, $index * $mealsPerDay, $mealsPerDay);
            $calories = 0;
            foreach ($dayMeals as $meal) {
                $calories += (int) ($meal['calories'] ?? 0);
            }
            $weekly[] = [
                'day' => $day,
                'meals' => count($dayMeals),
                'calories' => $calories,
                'completed' => count($dayMeals) >= $mealsPerDay && $calories > 0,
            ];
        }

        return $weekly;
    }

    /**
     * Calculate average calories per meal.
     */
    private function calculateAvgCalories(array $meals): int
    {
        if (empty($meals)) {
            return 1450;
        }
        $total = 0;
        foreach ($meals as $meal) {
            $total += (int) ($meal['calories'] ?? 0);
        }
        return (int) round($total / count($meals));
    }

    /**
     * Find the most frequently appearing meal as favorite.
     */
    private function findFavoriteMeal(array $meals): array
    {
        if (empty($meals)) {
            return ['name' => 'Grilled Chicken Bowl', 'count' => 0];
        }

        $counts = [];
        foreach ($meals as $meal) {
            $name = $meal['name_en'] ?? 'Meal';
            $counts[$name] = ($counts[$name] ?? 0) + 1;
        }
        arsort($counts);
        $favorite = array_key_first($counts);
        return ['name' => $favorite, 'count' => $counts[$favorite] ?? 0];
    }

    public function nutrition(NutritionApiService $nutritionApi, MealApiService $mealApi, AuthApiService $authApi)
    {
        $apiNutrition = $this->apiData($nutritionApi->today(), function () {
            return [];
        });

        $apiWeight = $this->apiData($nutritionApi->weightHistory(), function () {
            return [];
        });

        $meals = $this->apiData($mealApi->list(['limit' => 100, 'is_available' => true]), function () {
            return [];
        });

        $user = $this->apiData($authApi->me(), function () use ($authApi) {
            return $authApi->user() ?? [];
        });

        $currentWeight = $user['weight_kg'] ?? 78.2;
        $fitnessGoal = $user['fitness_goal'] ?? 'maintenance';
        $targets = $this->calculateNutritionTargets($fitnessGoal, $currentWeight);

        // If API provides today's nutrition, use it; otherwise calculate from meals
        if (!empty($apiNutrition)) {
            $todayStats = [
                'calories' => $apiNutrition['calories'] ?? 0,
                'calorieTarget' => $apiNutrition['calorie_target'] ?? $targets['calories'],
                'protein' => $apiNutrition['protein'] ?? 0,
                'proteinTarget' => $apiNutrition['protein_target'] ?? $targets['protein'],
                'carbs' => $apiNutrition['carbs'] ?? 0,
                'carbsTarget' => $apiNutrition['carbs_target'] ?? $targets['carbs'],
                'fat' => $apiNutrition['fat'] ?? 0,
                'fatTarget' => $apiNutrition['fat_target'] ?? $targets['fat'],
                'water' => $apiNutrition['water'] ?? 6,
                'waterTarget' => $apiNutrition['water_target'] ?? 8,
                'steps' => $apiNutrition['steps'] ?? 8420,
                'stepsTarget' => $apiNutrition['steps_target'] ?? 10000,
            ];
        } else {
            $todayMeals = array_slice($meals, 0, 3);
            $todayCalories = 0;
            $todayProtein = 0;
            $todayCarbs = 0;
            $todayFat = 0;
            foreach ($todayMeals as $meal) {
                $todayCalories += (int) ($meal['calories'] ?? 0);
                $todayProtein += (int) ($meal['protein_g'] ?? 0);
                $todayCarbs += (int) ($meal['carbs_g'] ?? 0);
                $todayFat += (int) ($meal['fat_g'] ?? 0);
            }

            $todayStats = [
                'calories' => $todayCalories,
                'calorieTarget' => $targets['calories'],
                'protein' => $todayProtein,
                'proteinTarget' => $targets['protein'],
                'carbs' => $todayCarbs,
                'carbsTarget' => $targets['carbs'],
                'fat' => $todayFat,
                'fatTarget' => $targets['fat'],
                'water' => 6,
                'waterTarget' => 8,
                'steps' => 8420,
                'stepsTarget' => 10000,
            ];
        }

        $weeklyData = $this->buildWeeklyCalories($meals, $todayStats['calorieTarget']);

        // Weight progress from API or fallback
        if (!empty($apiWeight)) {
            $currentWeight = $apiWeight['current_weight'] ?? $currentWeight;
            $startWeight = $apiWeight['start_weight'] ?? ($currentWeight + 4.3);
            $goalWeight = $apiWeight['goal_weight'] ?? $this->calculateGoalWeight($currentWeight, $fitnessGoal);
            $weightProgress = array_map(fn ($item) => [
                'week' => $item['week'] ?? '',
                'weight' => $item['weight'] ?? 0,
            ], $apiWeight['history'] ?? []);
            $lost = $apiWeight['stats']['lost'] ?? max(0, $startWeight - $currentWeight);
            $remaining = $apiWeight['stats']['remaining'] ?? max(0, $currentWeight - $goalWeight);
            $streakDays = $apiWeight['stats']['streak_days'] ?? 28;
            $adherenceRate = $apiWeight['stats']['adherence_rate'] ?? 92;
            $avgDailyCalories = $apiWeight['stats']['avg_daily_calories'] ?? $this->calculateAvgDailyCalories($meals);
        } else {
            $goalWeight = $this->calculateGoalWeight($currentWeight, $fitnessGoal);
            $startWeight = $currentWeight + ($goalWeight < $currentWeight ? 4.3 : 0);
            $weightProgress = $this->buildWeightProgress($startWeight, $currentWeight, $goalWeight);
            $lost = max(0, $startWeight - $currentWeight);
            $remaining = max(0, $currentWeight - $goalWeight);
            $streakDays = 28;
            $adherenceRate = 92;
            $avgDailyCalories = $this->calculateAvgDailyCalories($meals);
        }

        $stats = [
            'currentWeight' => $currentWeight,
            'startWeight' => $startWeight,
            'goalWeight' => $goalWeight,
            'lost' => $lost,
            'remaining' => $remaining,
            'streakDays' => $streakDays,
            'avgDailyCalories' => $avgDailyCalories,
            'adherenceRate' => $adherenceRate,
        ];

        return view('user.nutrition', compact('todayStats', 'weeklyData', 'weightProgress', 'stats'));
    }

    /**
     * Calculate nutrition targets based on fitness goal.
     */
    private function calculateNutritionTargets(string $goal, float $weight): array
    {
        $targets = [
            'weight_loss' => ['calories' => 1800, 'protein' => 140, 'carbs' => 150, 'fat' => 55],
            'muscle_gain' => ['calories' => 2500, 'protein' => 180, 'carbs' => 280, 'fat' => 75],
            'maintenance' => ['calories' => 2200, 'protein' => 120, 'carbs' => 220, 'fat' => 65],
            'healthy_lifestyle' => ['calories' => 2000, 'protein' => 120, 'carbs' => 200, 'fat' => 60],
        ];

        return $targets[$goal] ?? $targets['maintenance'];
    }

    /**
     * Calculate goal weight based on current weight and goal.
     */
    private function calculateGoalWeight(float $currentWeight, string $goal): float
    {
        if (in_array($goal, ['weight_loss', 'healthy_lifestyle'])) {
            return round($currentWeight * 0.9, 1);
        }
        if ($goal === 'muscle_gain') {
            return round($currentWeight * 1.05, 1);
        }
        return $currentWeight;
    }

    /**
     * Build weekly calorie data from meals.
     */
    private function buildWeeklyCalories(array $meals, int $targetCalories): array
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weekly = [];

        foreach ($days as $index => $day) {
            $dayMeals = array_slice($meals, $index * 3, 3);
            $calories = 0;
            foreach ($dayMeals as $meal) {
                $calories += (int) ($meal['calories'] ?? 0);
            }
            $weekly[] = [
                'day' => $day,
                'calories' => $calories ?: $targetCalories,
            ];
        }

        return $weekly;
    }

    /**
     * Build weight progress from start to current (fallback).
     */
    private function buildWeightProgress(float $startWeight, float $currentWeight, float $goalWeight): array
    {
        $weeks = 8;
        $progress = [];
        $step = ($currentWeight - $startWeight) / max(1, $weeks - 1);

        for ($i = 0; $i < $weeks; $i++) {
            $weight = $startWeight + ($step * $i);
            $progress[] = [
                'week' => 'Week ' . ($i + 1),
                'weight' => round($weight, 1),
            ];
        }

        // Ensure last point is current weight
        $progress[$weeks - 1]['weight'] = $currentWeight;

        return $progress;
    }

    /**
     * Calculate average daily calories from meals.
     */
    private function calculateAvgDailyCalories(array $meals): int
    {
        if (empty($meals)) {
            return 1623;
        }
        $total = 0;
        foreach ($meals as $meal) {
            $total += (int) ($meal['calories'] ?? 0);
        }
        return (int) round($total / count($meals)) * 3; // Approximate 3 meals per day
    }

    public function orders(OrderApiService $orderApi, SubscriptionApiService $subscriptionApi, PlanApiService $planApi)
    {
        $apiOrders = $this->apiData($orderApi->my(), function () {
            return [];
        });

        $orders = [];
        $total = 0;
        $delivered = 0;
        $cancelled = 0;
        $totalSpent = 0;

        if (!empty($apiOrders) && is_array($apiOrders)) {
            foreach ($apiOrders as $order) {
                $status = $order['status'] ?? 'pending';
                $amount = $order['total_amount'] ?? 0;
                $orders[] = [
                    'id' => $order['order_number'] ?? ('ORD-' . $order['id']),
                    'plan' => $order['plan_name'] ?? 'Plan',
                    'meals' => $order['meals'] ?? count($order['items'] ?? []),
                    'amount' => $amount,
                    'date' => $order['created_at'] ?? date('Y-m-d'),
                    'status' => $status,
                ];

                $total++;
                $totalSpent += $amount;
                if ($status === 'delivered') {
                    $delivered++;
                } elseif ($status === 'cancelled') {
                    $cancelled++;
                }
            }
        }

        // Fallback: derive orders from subscriptions if orders API is empty
        if (empty($orders)) {
            $subscriptions = $this->apiData($subscriptionApi->my(), function () {
                return [];
            });

            $plans = $this->apiData($planApi->list(['limit' => 100]), function () {
                return [];
            });

            $plansById = [];
            foreach ($plans as $plan) {
                $plansById[$plan['id'] ?? 0] = $plan;
            }

            $index = 1;
            foreach ($subscriptions as $sub) {
                $planId = $sub['plan_id'] ?? 0;
                $plan = $plansById[$planId] ?? [];
                $planName = $plan['name_en'] ?? 'Plan #' . $planId;
                $meals = $plan['meals_per_day'] ?? 3;
                $status = $this->mapSubscriptionStatus($sub['status'] ?? 'pending', $sub['payment_status'] ?? 'unpaid');
                $amount = $sub['amount'] ?? 0;
                $date = $sub['created_at'] ?? $sub['start_date'] ?? date('Y-m-d');

                $orders[] = [
                    'id' => 'ORD-' . str_pad($index, 4, '0', STR_PAD_LEFT),
                    'plan' => $planName,
                    'meals' => $meals,
                    'amount' => $amount,
                    'date' => $date,
                    'status' => $status,
                ];

                $total++;
                $totalSpent += $amount;
                if ($status === 'delivered') {
                    $delivered++;
                } elseif ($status === 'cancelled') {
                    $cancelled++;
                }
                $index++;
            }
        }

        // Sort by date descending
        usort($orders, function ($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });

        $stats = [
            'total' => $total,
            'delivered' => $delivered,
            'cancelled' => $cancelled,
            'totalSpent' => $totalSpent,
            'avgOrder' => $total > 0 ? round($totalSpent / $total) : 0,
        ];

        return view('user.orders', compact('orders', 'stats'));
    }

    /**
     * Map subscription status to order status.
     */
    private function mapSubscriptionStatus(string $subscriptionStatus, string $paymentStatus): string
    {
        if ($subscriptionStatus === 'cancelled' || $paymentStatus === 'failed') {
            return 'cancelled';
        }
        if ($subscriptionStatus === 'active' && $paymentStatus === 'paid') {
            return 'delivered';
        }
        return 'pending';
    }

    public function delivery(DeliveryApiService $deliveryApi, SubscriptionApiService $subscriptionApi, AuthApiService $authApi)
    {
        $apiDeliveries = $this->apiData($deliveryApi->my(), function () {
            return [];
        });

        $user = $this->apiData($authApi->me(), function () use ($authApi) {
            return $authApi->user() ?? [];
        });

        $zone = $user['location'] ?? 'Riyadh Central';

        $upcoming = [];
        $history = [];

        if (!empty($apiDeliveries) && is_array($apiDeliveries)) {
            foreach ($apiDeliveries as $delivery) {
                $status = $delivery['status'] ?? 'pending';
                $scheduledAt = $delivery['scheduled_at'] ?? null;
                $date = $scheduledAt ? date('M d', strtotime($scheduledAt)) : 'Pending';
                $time = $scheduledAt ? date('H:i', strtotime($scheduledAt)) : '--:--';

                $item = [
                    'id' => 'DLV-' . $delivery['id'],
                    'order' => 'ORD-' . $delivery['order_id'],
                    'date' => $date,
                    'time' => $time,
                    'zone' => $delivery['zone'] ?? $zone,
                    'driver' => $delivery['driver_name'] ?? 'Unassigned',
                    'status' => $status === 'out_for_delivery' ? 'out' : $status,
                    'meals' => $delivery['meals'] ?? 3,
                    'eta' => $delivery['eta'] ?? 'On time',
                ];

                if (in_array($status, ['pending', 'assigned', 'picked_up', 'out_for_delivery'])) {
                    $upcoming[] = $item;
                } else {
                    $history[] = $item;
                }
            }
        }

        // Fallback mock data if API is not available
        if (empty($upcoming) && empty($history)) {
            $subscriptions = $this->apiData($subscriptionApi->my(), function () {
                return [];
            });
            $totalDeliveries = max(2, count($subscriptions));

            $upcoming = [
                ['id' => 'DLV-501', 'order' => 'ORD-2401', 'date' => 'Tomorrow', 'time' => '09:00 - 10:00', 'zone' => $zone, 'driver' => 'Yousef', 'status' => 'scheduled', 'meals' => 3, 'eta' => 'On time'],
                ['id' => 'DLV-502', 'order' => 'ORD-2402', 'date' => 'Wed, Jul 3', 'time' => '09:00 - 10:00', 'zone' => $zone, 'driver' => 'Unassigned', 'status' => 'scheduled', 'meals' => 3, 'eta' => 'On time'],
            ];

            $history = [
                ['id' => 'DLV-498', 'order' => 'ORD-2387', 'date' => 'Today', 'time' => '09:15', 'zone' => $zone, 'driver' => 'Yousef', 'status' => 'delivered', 'meals' => 3, 'eta' => 'On time'],
                ['id' => 'DLV-487', 'order' => 'ORD-2372', 'date' => 'Yesterday', 'time' => '09:10', 'zone' => $zone, 'driver' => 'Yousef', 'status' => 'delivered', 'meals' => 3, 'eta' => 'On time'],
                ['id' => 'DLV-475', 'order' => 'ORD-2358', 'date' => 'Jun 27', 'time' => '09:20', 'zone' => $zone, 'driver' => 'Hassan', 'status' => 'delivered', 'meals' => 3, 'eta' => '5 min late'],
                ['id' => 'DLV-462', 'order' => 'ORD-2341', 'date' => 'Jun 26', 'time' => '09:05', 'zone' => $zone, 'driver' => 'Yousef', 'status' => 'delivered', 'meals' => 3, 'eta' => 'On time'],
                ['id' => 'DLV-451', 'order' => 'ORD-2329', 'date' => 'Jun 25', 'time' => '09:15', 'zone' => $zone, 'driver' => 'Yousef', 'status' => 'delivered', 'meals' => 3, 'eta' => 'On time'],
            ];

            $stats = [
                'totalDeliveries' => $totalDeliveries,
                'onTimeRate' => 95,
                'avgDeliveryTime' => '32 min',
                'preferredSlot' => '09:00 - 10:00',
            ];
        } else {
            $totalDeliveries = count($upcoming) + count($history);
            $stats = [
                'totalDeliveries' => $totalDeliveries,
                'onTimeRate' => 95,
                'avgDeliveryTime' => '32 min',
                'preferredSlot' => '09:00 - 10:00',
            ];
        }

        return view('user.delivery', compact('upcoming', 'history', 'stats'));
    }

    public function notifications(AuthApiService $authApi)
    {
        // Note: There is no notifications API yet. We fetch user profile for context but keep mock notifications.
        $user = $this->apiData($authApi->me(), function () use ($authApi) {
            return $authApi->user() ?? [];
        });

        $userName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: 'User';

        $notifications = [
            ['id' => 1, 'title' => 'Delivery Tomorrow', 'message' => 'Your meal delivery is scheduled for tomorrow 09:00 - 10:00', 'type' => 'delivery', 'time' => '1 hour ago', 'read' => false],
            ['id' => 2, 'title' => 'Meal Plan Renewal', 'message' => 'Your Weight Loss Pro plan renews on Jul 1, 2025', 'type' => 'subscription', 'time' => '5 hours ago', 'read' => false],
            ['id' => 3, 'title' => 'Nutrition Goal Achieved', 'message' => 'Congratulations! You hit your protein target 5 days in a row', 'type' => 'achievement', 'time' => 'Yesterday', 'read' => true],
            ['id' => 4, 'title' => 'New Meal Added', 'message' => 'Grilled Chicken Bowl has been added to your meal plan', 'type' => 'meal', 'time' => '2 days ago', 'read' => true],
            ['id' => 5, 'title' => 'Payment Successful', 'message' => 'Payment of SAR 420 for ORD-2387 was completed', 'type' => 'payment', 'time' => '3 days ago', 'read' => true],
            ['id' => 6, 'title' => 'Weekly Digest', 'message' => 'Your weekly nutrition summary is ready to view', 'type' => 'digest', 'time' => '4 days ago', 'read' => true],
        ];

        $preferences = [
            ['name' => 'Delivery Alerts', 'channel' => 'SMS', 'enabled' => true],
            ['name' => 'Meal Reminders', 'channel' => 'Push', 'enabled' => true],
            ['name' => 'Payment Receipts', 'channel' => 'Email', 'enabled' => true],
            ['name' => 'Weekly Digest', 'channel' => 'Email', 'enabled' => true],
            ['name' => 'Promotional Offers', 'channel' => 'Push', 'enabled' => false],
        ];

        $unread = count(array_filter($notifications, fn ($n) => !($n['read'] ?? false)));

        $stats = [
            'unread' => $unread,
            'total' => count($notifications),
        ];

        return view('user.notifications', compact('notifications', 'preferences', 'stats', 'userName'));
    }

    public function settings(ProfileApiService $profileApi)
    {
        $apiUser = $this->apiData($profileApi->fetch(), function () use ($profileApi) {
            return app(AuthApiService::class)->user() ?? [];
        });

        $profile = [
            'name' => trim(($apiUser['first_name'] ?? '') . ' ' . ($apiUser['last_name'] ?? '')) ?: 'John Doe',
            'email' => $apiUser['email'] ?? 'john@example.com',
            'phone' => $apiUser['phone'] ?? '+966 55 123 4567',
            'dob' => $apiUser['date_of_birth'] ?? '1990-05-15',
            'gender' => ucfirst($apiUser['gender'] ?? 'Male'),
            'height' => $apiUser['height_cm'] ?? 178,
            'weight' => $apiUser['weight_kg'] ?? 78.2,
            'goal' => ucfirst(str_replace('_', ' ', $apiUser['fitness_goal'] ?? 'weight_loss')),
            'activity' => $apiUser['activity_level'] ?? 'Moderate',
            'address' => $apiUser['address'] ?? 'King Fahd Road, Riyadh, Saudi Arabia',
            'zone' => $apiUser['location'] ?? 'Riyadh Central',
        ];

        return view('user.settings', compact('profile'));
    }
}
