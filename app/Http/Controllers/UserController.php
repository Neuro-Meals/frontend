<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\AuthApiService;
use App\Services\Api\HasApiData;
use App\Services\Api\MealApiService;
use App\Services\Api\PlanApiService;
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
                'mealsTotal' => 0,
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

    public function meals()
    {
        $todayMeals = [
            ['name' => 'Protein Breakfast Plate', 'time' => 'Breakfast · 07:30', 'calories' => 410, 'protein' => 35, 'carbs' => 28, 'fat' => 14, 'status' => 'delivered', 'image' => 'healthy-protein-bowl-with-quinoa-avocado-kale-sweet-potato-poached-egg_9975-132760.jpg'],
            ['name' => 'Grilled Chicken Bowl', 'time' => 'Lunch · 12:30', 'calories' => 520, 'protein' => 45, 'carbs' => 38, 'fat' => 18, 'status' => 'delivered', 'image' => 'grilled-chicken-breast-rice-berry-vegetables-white-background_1428-2141.jpg'],
            ['name' => 'Quinoa Buddha Bowl', 'time' => 'Dinner · 19:00', 'calories' => 480, 'protein' => 22, 'carbs' => 62, 'fat' => 16, 'status' => 'upcoming', 'image' => 'healthy-buddha-bowl-with-sliced-meat-fresh-vegetables_9975-132258.jpg'],
        ];

        $weekMeals = [
            ['day' => 'Mon', 'meals' => 3, 'calories' => 1410, 'completed' => true],
            ['day' => 'Tue', 'meals' => 3, 'calories' => 1480, 'completed' => true],
            ['day' => 'Wed', 'meals' => 3, 'calories' => 1390, 'completed' => true],
            ['day' => 'Thu', 'meals' => 3, 'calories' => 1520, 'completed' => true],
            ['day' => 'Fri', 'meals' => 3, 'calories' => 1450, 'completed' => true],
            ['day' => 'Sat', 'meals' => 3, 'calories' => 1410, 'completed' => true],
            ['day' => 'Sun', 'meals' => 2, 'calories' => 930, 'completed' => false],
        ];

        $stats = [
            'totalThisWeek' => 18,
            'totalPlan' => 84,
            'remaining' => 66,
            'avgCalories' => 1450,
            'favoriteMeal' => 'Grilled Chicken Bowl',
            'favoriteCount' => 12,
        ];

        return view('user.meals', compact('todayMeals', 'weekMeals', 'stats'));
    }

    public function nutrition()
    {
        $todayStats = [
            'calories' => 1240,
            'calorieTarget' => 1800,
            'protein' => 80,
            'proteinTarget' => 140,
            'carbs' => 130,
            'carbsTarget' => 200,
            'fat' => 32,
            'fatTarget' => 55,
            'water' => 6,
            'waterTarget' => 8,
            'steps' => 8420,
            'stepsTarget' => 10000,
        ];

        $weeklyData = [
            ['day' => 'Mon', 'calories' => 1620, 'protein' => 135, 'carbs' => 190, 'fat' => 52],
            ['day' => 'Tue', 'calories' => 1750, 'protein' => 142, 'carbs' => 205, 'fat' => 48],
            ['day' => 'Wed', 'calories' => 1580, 'protein' => 128, 'carbs' => 175, 'fat' => 44],
            ['day' => 'Thu', 'calories' => 1820, 'protein' => 145, 'carbs' => 210, 'fat' => 58],
            ['day' => 'Fri', 'calories' => 1700, 'protein' => 138, 'carbs' => 195, 'fat' => 50],
            ['day' => 'Sat', 'calories' => 1650, 'protein' => 132, 'carbs' => 188, 'fat' => 46],
            ['day' => 'Sun', 'calories' => 1240, 'protein' => 80, 'carbs' => 130, 'fat' => 32],
        ];

        $weightProgress = [
            ['week' => 'Week 1', 'weight' => 82.5],
            ['week' => 'Week 2', 'weight' => 81.8],
            ['week' => 'Week 3', 'weight' => 81.1],
            ['week' => 'Week 4', 'weight' => 80.4],
            ['week' => 'Week 5', 'weight' => 79.8],
            ['week' => 'Week 6', 'weight' => 79.2],
            ['week' => 'Week 7', 'weight' => 78.6],
            ['week' => 'Week 8', 'weight' => 78.2],
        ];

        $stats = [
            'currentWeight' => 78.2,
            'startWeight' => 82.5,
            'goalWeight' => 75.0,
            'lost' => 4.3,
            'remaining' => 3.2,
            'streakDays' => 28,
            'avgDailyCalories' => 1623,
            'adherenceRate' => 92,
        ];

        return view('user.nutrition', compact('todayStats', 'weeklyData', 'weightProgress', 'stats'));
    }

    public function orders()
    {
        $orders = [
            ['id' => 'ORD-2401', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-30', 'meals' => 3],
            ['id' => 'ORD-2387', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-29', 'meals' => 3],
            ['id' => 'ORD-2372', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-28', 'meals' => 3],
            ['id' => 'ORD-2358', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-27', 'meals' => 3],
            ['id' => 'ORD-2341', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-26', 'meals' => 3],
            ['id' => 'ORD-2329', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-25', 'meals' => 3],
            ['id' => 'ORD-2315', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'cancelled', 'date' => '2025-06-24', 'meals' => 3],
            ['id' => 'ORD-2302', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-23', 'meals' => 3],
        ];

        $stats = [
            'total' => 42,
            'delivered' => 40,
            'cancelled' => 2,
            'totalSpent' => 16800,
            'avgOrder' => 400,
        ];

        return view('user.orders', compact('orders', 'stats'));
    }

    public function delivery()
    {
        $upcoming = [
            ['id' => 'DLV-501', 'order' => 'ORD-2401', 'date' => 'Tomorrow', 'time' => '09:00 - 10:00', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'scheduled', 'meals' => 3],
            ['id' => 'DLV-502', 'order' => 'ORD-2402', 'date' => 'Wed, Jul 3', 'time' => '09:00 - 10:00', 'zone' => 'Riyadh Central', 'driver' => 'Unassigned', 'status' => 'scheduled', 'meals' => 3],
        ];

        $history = [
            ['id' => 'DLV-498', 'order' => 'ORD-2387', 'date' => 'Today', 'time' => '09:15', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'eta' => 'On time'],
            ['id' => 'DLV-487', 'order' => 'ORD-2372', 'date' => 'Yesterday', 'time' => '09:10', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'eta' => 'On time'],
            ['id' => 'DLV-475', 'order' => 'ORD-2358', 'date' => 'Jun 27', 'time' => '09:20', 'zone' => 'Riyadh Central', 'driver' => 'Hassan', 'status' => 'delivered', 'eta' => '5 min late'],
            ['id' => 'DLV-462', 'order' => 'ORD-2341', 'date' => 'Jun 26', 'time' => '09:05', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'eta' => 'On time'],
            ['id' => 'DLV-451', 'order' => 'ORD-2329', 'date' => 'Jun 25', 'time' => '09:15', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'eta' => 'On time'],
        ];

        $stats = [
            'totalDeliveries' => 40,
            'onTimeRate' => 95,
            'avgDeliveryTime' => '32 min',
            'preferredSlot' => '09:00 - 10:00',
        ];

        return view('user.delivery', compact('upcoming', 'history', 'stats'));
    }

    public function notifications()
    {
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

        $stats = [
            'unread' => 2,
            'total' => 48,
        ];

        return view('user.notifications', compact('notifications', 'preferences', 'stats'));
    }

    public function settings()
    {
        $authApi = app(AuthApiService::class);
        $apiUser = $authApi->user();

        $profile = [
            'name' => trim(($apiUser['first_name'] ?? 'John') . ' ' . ($apiUser['last_name'] ?? 'Doe')),
            'email' => $apiUser['email'] ?? 'john@example.com',
            'phone' => $apiUser['phone'] ?? '+966 55 123 4567',
            'dob' => '1990-05-15',
            'gender' => 'Male',
            'height' => 178,
            'weight' => 78.2,
            'goal' => 'Weight Loss',
            'activity' => 'Moderate',
            'address' => 'King Fahd Road, Riyadh, Saudi Arabia',
            'zone' => 'Riyadh Central',
        ];

        return view('user.settings', compact('profile'));
    }
}
