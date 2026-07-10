<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\AuthApiService;
use App\Services\Api\DeliveryApiService;
use App\Services\Api\HasApiData;
use App\Services\Api\MealApiService;
use App\Services\Api\MealScheduleApiService;
use App\Services\Api\MealSelectionApiService;
use App\Services\Api\NotificationApiService;
use App\Services\Api\NutritionApiService;
use App\Services\Api\OrderApiService;
use App\Services\Api\PaymentApiService;
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
        })->except(['paymentSuccess', 'paymentCancel']);
    }

    public function dashboard(
        Request $request,
        AuthApiService $authApi,
        MealApiService $mealApi,
        SubscriptionApiService $subscriptionApi,
        PlanApiService $planApi,
        MealSelectionApiService $selectionApi
    ) {
        $user = $this->apiData($authApi->me(), function () use ($authApi) {
            return $authApi->user() ?? [];
        });

        // Refresh session user data when API returns fresh data
        if (!empty($user['id'])) {
            session(['api_user' => $user]);
        }

        $mySubscriptions = $this->apiData($subscriptionApi->my(), function () {
            return [];
        });

        $activeSubscription = null;
        foreach ($mySubscriptions as $sub) {
            if (($sub['status'] ?? '') === 'active' || ($sub['status'] ?? '') === 'pending_payment') {
                $activeSubscription = $sub;
                break;
            }
        }
        if (!$activeSubscription && !empty($mySubscriptions)) {
            $activeSubscription = $mySubscriptions[0];
        }

        $planDetails = [];
        if ($activeSubscription && !empty($activeSubscription['plan_id'])) {
            $planDetails = $this->apiData($planApi->show($activeSubscription['plan_id']), function () {
                return [];
            });
        }

        $planName = $planDetails['name_en'] ?? $activeSubscription['plan_name'] ?? 'Active Plan';
        $calorieTarget = $planDetails['calories'] ?? $activeSubscription['calorie_target'] ?? 1800;
        $proteinTarget = $planDetails['protein_target'] ?? $activeSubscription['protein_target'] ?? 140;
        $carbsTarget = $planDetails['carbs_target'] ?? $activeSubscription['carbs_target'] ?? 200;
        $fatTarget = $planDetails['fat_target'] ?? $activeSubscription['fat_target'] ?? 55;
        $mealsPerDay = $planDetails['meals_per_day'] ?? 3;

        $meals = $this->apiData($mealApi->list(['limit' => 100, 'is_available' => true]), function () {
            return [];
        });

        $mealsById = [];
        foreach ($meals as $meal) {
            $id = $meal['id'] ?? 0;
            if ($id) {
                $mealsById[$id] = $meal;
            }
        }

        // Fetch plan items and user selections to build the real schedule
        $planItems = [];
        $selections = [];
        if ($activeSubscription && !empty($activeSubscription['plan_id'])) {
            $planItems = $this->apiData($planApi->listPlanItems($activeSubscription['plan_id']), function () {
                return [];
            });
            $selections = $this->apiData($selectionApi->my((int) $activeSubscription['id']), function () {
                return [];
            });
        }

        $weekMeals = $this->buildWeeklyScheduleFromPlan($planItems, $selections, $mealsById);

        $todayNumber = (int) date('N');
        $todayMeals = $weekMeals[$todayNumber - 1]['meals'] ?? [];

        // Fallback to generic meals if nothing is scheduled yet
        if (empty($todayMeals)) {
            foreach (array_slice($meals, 0, $mealsPerDay) as $meal) {
                $todayMeals[] = $this->normalizeMealForView($meal);
            }
        }

        $upcomingMeals = [];
        foreach (array_slice($todayMeals, 0, 4) as $meal) {
            $upcomingMeals[] = [
                'id' => $meal['id'] ?? 0,
                'name' => $meal['name'] ?? 'Meal',
                'time' => $meal['time'] ?? 'Upcoming',
                'calories' => $meal['calories'] ?? 0,
                'protein' => $meal['protein'] ?? 0,
                'carbs' => $meal['carbs'] ?? 0,
                'fat' => $meal['fat'] ?? 0,
                'image' => $meal['image'] ?? null,
                'status' => $meal['status'] ?? 'upcoming',
            ];
        }

        $weeklyProgress = $this->buildWeeklyProgressFromSchedule($weekMeals, (int) $calorieTarget);
        $recentOrders = $this->buildRecentOrders($activeSubscription ?? []);

        $currentWeight = $user['weight_kg'] ?? 0;
        $weightGoal = $user['fitness_goal'] === 'weight_loss' ? $currentWeight - 5 : ($user['fitness_goal'] === 'muscle_gain' ? $currentWeight + 3 : $currentWeight);
        $weightStart = $currentWeight + (($user['fitness_goal'] === 'weight_loss' ? 4.3 : -2) * -1);

        $todayStats = $this->calculateTodayStats($todayMeals);

        $totalPlanMeals = $planDetails['total_meals'] ?? $activeSubscription['total_meals'] ?? 84;
        $mealsConsumed = $activeSubscription['meals_consumed'] ?? 0;
        $remainingMeals = max(0, $totalPlanMeals - $mealsConsumed);

        $mealsThisWeek = 0;
        foreach ($weekMeals as $day) {
            foreach ($day['meals'] ?? [] as $meal) {
                if (($meal['status'] ?? '') !== 'skipped') {
                    $mealsThisWeek++;
                }
            }
        }

        $stats = [
            'activePlan' => $planName,
            'planPrice' => $planDetails['price'] ?? $activeSubscription['amount'] ?? 0,
            'planRenewal' => !empty($activeSubscription['end_date']) ? date('M d, Y', strtotime($activeSubscription['end_date'])) : 'N/A',
            'mealsThisWeek' => $mealsThisWeek,
            'mealsTotal' => $totalPlanMeals,
            'remainingMeals' => $remainingMeals,
            'totalOrders' => $activeSubscription['orders_count'] ?? 0,
            'dailyCalories' => $todayStats['calories'],
            'calorieTarget' => (int) $calorieTarget,
            'proteinTarget' => (int) $proteinTarget,
            'proteinToday' => $todayStats['protein'],
            'carbsTarget' => (int) $carbsTarget,
            'carbsToday' => $todayStats['carbs'],
            'fatTarget' => (int) $fatTarget,
            'fatToday' => $todayStats['fat'],
            'streakDays' => $user['streak_days'] ?? 0,
            'nextDelivery' => $activeSubscription['next_delivery'] ?? 'N/A',
            'nextMeal' => $upcomingMeals[0]['name'] ?? 'N/A',
            'weightStart' => (float) $weightStart,
            'weightCurrent' => (float) $currentWeight,
            'weightGoal' => (float) $weightGoal,
            'subscriptionStatus' => $activeSubscription['status'] ?? 'none',
        ];

        return view('user.dashboard', compact('user', 'stats', 'weeklyProgress', 'upcomingMeals', 'recentOrders', 'activeSubscription'));
    }

    /**
     * Build 7-day calorie progress from the user's weekly schedule.
     */
    private function buildWeeklyProgressFromSchedule(array $weekMeals, int $target): array
    {
        $progress = [];
        foreach ($weekMeals as $day) {
            $calories = $day['calories'] ?? 0;
            $progress[] = [
                'day' => $day['day'] ?? '',
                'calories' => $calories,
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
                    'id' => $order['id'] ?? ('ORD-' . ($order['order_number'] ?? 0)),
                    'plan' => $order['plan_name'] ?? 'Active Plan',
                    'amount' => $order['amount'] ?? 0,
                    'status' => $order['status'] ?? 'delivered',
                    'date' => $order['date'] ?? date('Y-m-d'),
                ];
            }
            return $recent;
        }

        return [];
    }

    /**
     * Calculate today's nutrition totals from scheduled meals.
     */
    private function calculateTodayStats(array $meals): array
    {
        $stats = ['calories' => 0, 'protein' => 0, 'carbs' => 0, 'fat' => 0];
        foreach ($meals as $meal) {
            $stats['calories'] += (int) ($meal['calories'] ?? 0);
            $stats['protein'] += (int) ($meal['protein'] ?? 0);
            $stats['carbs'] += (int) ($meal['carbs'] ?? 0);
            $stats['fat'] += (int) ($meal['fat'] ?? 0);
        }
        return $stats;
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
            'id' => $activeSubscription['id'] ?? null,
            'name' => $activePlanDetails['name_en'] ?? 'Active Plan',
            'price' => $activePlanDetails['price'] ?? ($activeSubscription['amount'] ?? 0),
            'duration' => ($activePlanDetails['duration_days'] ?? 28) . ' days',
            'status' => $activeSubscription['status'] ?? 'active',
            'payment_status' => $activeSubscription['payment_status'] ?? 'unpaid',
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
                'payment_status' => 'none',
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
                'subscribers' => $plan['subscribers_count'] ?? 0,
                'color' => $colors[$colorIndex % count($colors)],
                'current' => $isCurrent,
            ];
            $colorIndex++;
        }

        // Build subscription history
        $history = [];
        foreach ($mySubscriptions as $sub) {
            $plan = $plansById[$sub['plan_id'] ?? 0] ?? [];
            $history[] = [
                'plan' => $plan['name_en'] ?? 'Unknown Plan',
                'period' => !empty($sub['start_date']) ? date('M Y', strtotime($sub['start_date'])) : 'N/A',
                'status' => $sub['status'] ?? 'unknown',
                'payment_status' => $sub['payment_status'] ?? 'unpaid',
                'amount' => $sub['amount'] ?? 0,
            ];
        }

        return view('user.subscriptions', compact('activePlan', 'availablePlans', 'history'));
    }

    public function subscribe(Request $request, SubscriptionApiService $subscriptionApi, PaymentApiService $paymentApi)
    {
        $planId = (int) $request->input('plan_id');

        if ($planId <= 0) {
            return redirect()->route('user.subscriptions')->with('error', 'Invalid plan selected.');
        }

        $subscription = $this->apiData($subscriptionApi->create(['plan_id' => $planId]), function () {
            return [];
        });

        if (empty($subscription) || !empty($subscription['error']) || !isset($subscription['id'])) {
            return redirect()->route('user.subscriptions')->with('error', 'Failed to subscribe. Please try again.');
        }

        $subscriptionId = $subscription['id'];
        $checkout = $this->apiData($paymentApi->createCheckout($subscriptionId), function () {
            return [];
        });

        if (!empty($checkout['checkout_url'])) {
            return redirect()->away($checkout['checkout_url']);
        }

        return redirect()->route('user.subscriptions')->with('success', 'Subscription created! Please complete payment.');
    }

    public function pauseSubscription(int $subscriptionId, SubscriptionApiService $subscriptionApi)
    {
        if ($subscriptionId <= 0) {
            return redirect()->route('user.subscriptions')->with('error', 'Invalid subscription.');
        }

        $result = $this->apiData($subscriptionApi->pause($subscriptionId), function () {
            return [];
        });

        if (empty($result) || !empty($result['error']) || !isset($result['id'])) {
            $message = $result['detail'] ?? $result['message'] ?? 'Failed to pause subscription. Please try again.';
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        return redirect()->route('user.subscriptions')->with('success', 'Subscription paused successfully.');
    }

    public function resumeSubscription(int $subscriptionId, SubscriptionApiService $subscriptionApi)
    {
        if ($subscriptionId <= 0) {
            return redirect()->route('user.subscriptions')->with('error', 'Invalid subscription.');
        }

        $result = $this->apiData($subscriptionApi->resume($subscriptionId), function () {
            return [];
        });

        if (empty($result) || !empty($result['error']) || !isset($result['id'])) {
            $message = $result['detail'] ?? $result['message'] ?? 'Failed to resume subscription. Please try again.';
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        return redirect()->route('user.subscriptions')->with('success', 'Subscription resumed successfully.');
    }

    public function paymentSuccess(Request $request, PaymentApiService $paymentApi, AuthApiService $authApi)
    {
        $sessionId = $request->input('session_id');
        $paymentId = $request->input('payment_id');
        $isLoggedIn = $authApi->check();

        $payment = [];
        $verified = false;
        $error = null;

        if ($isLoggedIn && $sessionId) {
            $result = $this->apiData($paymentApi->verifySession($sessionId), function () {
                return [];
            });

            if (!empty($result['id'])) {
                $payment = $result;
                $verified = ($result['status'] ?? '') === 'paid';
            } elseif (!empty($result['detail']) || !empty($result['message'])) {
                $error = $result['detail'] ?? $result['message'] ?? 'Unable to confirm payment status.';
            }
        } elseif ($paymentId) {
            // No session id to verify; surface a pending state so the user can retry later.
            $payment = ['id' => $paymentId, 'status' => 'pending'];
        } elseif ($sessionId && !$isLoggedIn) {
            // Guest returning from Stripe cannot verify the session because the API requires auth.
            $payment = ['id' => $paymentId ?? 'pending', 'status' => 'pending'];
            $error = 'Please log in or create an account so we can confirm your payment and activate your subscription.';
        } else {
            $error = 'No payment information was received.';
        }

        return view('payment.success', compact('payment', 'verified', 'error', 'sessionId', 'isLoggedIn'));
    }

    public function paymentCancel(Request $request)
    {
        $paymentId = $request->input('payment_id');

        return view('payment.cancel', compact('paymentId'));
    }

    public function meals(
        MealApiService $mealApi,
        MealScheduleApiService $scheduleApi,
        SubscriptionApiService $subscriptionApi,
        PlanApiService $planApi,
        MealSelectionApiService $selectionApi
    ) {
        $meals = $this->apiData($mealApi->list(['limit' => 100, 'is_available' => true]), function () {
            return [];
        });

        $mealsById = [];
        foreach ($meals as $meal) {
            $id = $meal['id'] ?? 0;
            if ($id) {
                $mealsById[$id] = $meal;
            }
        }

        $mySubscriptions = $this->apiData($subscriptionApi->my(), function () {
            return [];
        });

        $activeSubscription = null;
        foreach ($mySubscriptions as $sub) {
            if (($sub['status'] ?? '') === 'active' || ($sub['status'] ?? '') === 'pending_payment') {
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

        // Fetch the user's plan items and their meal selections
        $planItems = [];
        $selections = [];
        if ($activeSubscription && !empty($activeSubscription['plan_id'])) {
            $planItems = $this->apiData($planApi->listPlanItems($activeSubscription['plan_id']), function () {
                return [];
            });
            $selections = $this->apiData($selectionApi->my((int) $activeSubscription['id']), function () {
                return [];
            });
        }

        // Build weekly schedule from plan items, applying user selections as overrides
        $weekMeals = $this->buildWeeklyScheduleFromPlan($planItems, $selections, $mealsById);

        // Today's meals are the meals scheduled for the current day
        $todayNumber = (int) date('N');
        $todayMeals = $weekMeals[$todayNumber - 1]['meals'] ?? [];

        // Fallback to first available meals if nothing is scheduled yet
        if (empty($todayMeals)) {
            foreach (array_slice($meals, 0, $mealsPerDay) as $meal) {
                $todayMeals[] = $this->normalizeMealForView($meal);
            }
        }

        // Calculate stats
        $totalThisWeek = min(count($meals), $mealsPerDay * 7);
        $avgCalories = $this->calculateAvgCalories($meals);
        $favoriteMeal = $this->findFavoriteMeal($meals);

        $stats = [
            'totalThisWeek' => $totalThisWeek,
            'totalPlan' => $totalPlanMeals,
            'remaining' => max(0, $totalPlanMeals - $mealsConsumed),
            'avgCalories' => $avgCalories,
            'favoriteMeal' => $favoriteMeal['name'] ?? 'N/A',
            'favoriteCount' => $favoriteMeal['count'] ?? 0,
        ];

        $hasActiveSubscription = $activeSubscription !== null;

        return view('user.meals', compact('todayMeals', 'weekMeals', 'stats', 'activeSubscription', 'hasActiveSubscription'));
    }

    /**
     * Normalize a meal array from the API for view rendering.
     */
    private function normalizeMealForView(array $meal): array
    {
        return [
            'id' => $meal['id'] ?? 0,
            'name' => $meal['name'] ?? $meal['name_en'] ?? 'Meal',
            'time' => $meal['meal_time'] ?? $meal['time'] ?? 'Meal',
            'calories' => (int) ($meal['calories'] ?? 0),
            'protein' => (int) ($meal['protein_g'] ?? $meal['protein'] ?? 0),
            'carbs' => (int) ($meal['carbs_g'] ?? $meal['carbs'] ?? 0),
            'fat' => (int) ($meal['fat_g'] ?? $meal['fat'] ?? 0),
            'status' => $meal['status'] ?? 'upcoming',
            'image' => $meal['image_url'] ?? $meal['image'] ?? null,
            'price' => $meal['price'] ?? 0,
            'category' => $meal['category']['name_en'] ?? ($meal['category_name'] ?? 'Meal'),
        ];
    }

    /**
     * Build weekly schedule from plan items, applying user selections as overrides.
     */
    private function buildWeeklyScheduleFromPlan(array $planItems, array $selections, array $mealsById): array
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weekly = [];

        // Index selections by day_number + meal_time for quick lookup
        $selectionsBySlot = [];
        foreach ($selections as $selection) {
            $key = ($selection['day_number'] ?? 1) . '|' . ($selection['meal_time'] ?? 'Meal');
            $selectionsBySlot[$key] = $selection;
        }

        // Group plan items by day_number
        $planItemsByDay = [];
        foreach ($planItems as $item) {
            $dayNumber = (int) ($item['day_number'] ?? 1);
            $planItemsByDay[$dayNumber][] = $item;
        }

        for ($dayNumber = 1; $dayNumber <= 7; $dayNumber++) {
            $dayMeals = [];
            $items = $planItemsByDay[$dayNumber] ?? [];

            foreach ($items as $item) {
                $mealTime = $item['meal_time'] ?? 'Meal';
                $slotKey = $dayNumber . '|' . $mealTime;
                $selection = $selectionsBySlot[$slotKey] ?? null;

                $mealId = $selection['meal_id'] ?? $item['meal_id'] ?? 0;
                $meal = $mealsById[$mealId] ?? [];
                if (empty($meal)) {
                    continue;
                }

                $normalized = $this->normalizeMealForView($meal);
                $normalized['time'] = $mealTime;
                $normalized['status'] = ($selection['is_skipped'] ?? false) ? 'skipped' : ($selection ? 'selected' : 'scheduled');
                $normalized['selection_id'] = $selection['id'] ?? null;
                $dayMeals[] = $normalized;
            }

            $calories = array_sum(array_column($dayMeals, 'calories'));
            $weekly[] = [
                'day' => $days[$dayNumber - 1],
                'date' => null,
                'meals' => $dayMeals,
                'mealCount' => count($dayMeals),
                'calories' => $calories,
                'completed' => $calories > 0 && count($dayMeals) > 0,
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
            return 0;
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
            return ['name' => 'N/A', 'count' => 0];
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

        $currentWeight = $user['weight_kg'] ?? 0;
        $fitnessGoal = $user['fitness_goal'] ?? 'maintenance';
        $targets = $this->calculateNutritionTargets($fitnessGoal, $currentWeight);

        // Prepare today's meals for display on the nutrition page
        $todayMeals = [];
        $slicedMeals = array_slice($meals, 0, 3);
        foreach ($slicedMeals as $index => $meal) {
            $todayMeals[] = [
                'id' => $meal['id'] ?? 0,
                'name' => $meal['name_en'] ?? ($meal['name'] ?? 'Meal'),
                'time' => $meal['meal_time'] ?? (['Breakfast', 'Lunch', 'Dinner'][$index] ?? 'Meal'),
                'image' => $meal['image_url'] ?? null,
                'category' => $meal['category']['name_en'] ?? ($meal['category_name'] ?? 'Meal'),
                'calories' => (int) ($meal['calories'] ?? 0),
                'protein' => (int) ($meal['protein_g'] ?? 0),
                'carbs' => (int) ($meal['carbs_g'] ?? 0),
                'fat' => (int) ($meal['fat_g'] ?? 0),
                'price' => $meal['price'] ?? 0,
                'orders' => $meal['orders_count'] ?? 0,
                'serving' => '1 serving',
                'status' => 'pending',
            ];
        }

        // If API provides today's nutrition, use it; otherwise calculate from meals
        if (!empty($apiNutrition)) {
            $todayStats = [
                'calories' => $apiNutrition['calories'] ?? 0,
                'calorieTarget' => max((int) ($apiNutrition['calorie_target'] ?? $targets['calories']), 1),
                'protein' => $apiNutrition['protein'] ?? 0,
                'proteinTarget' => max((int) ($apiNutrition['protein_target'] ?? $targets['protein']), 1),
                'carbs' => $apiNutrition['carbs'] ?? 0,
                'carbsTarget' => max((int) ($apiNutrition['carbs_target'] ?? $targets['carbs']), 1),
                'fat' => $apiNutrition['fat'] ?? 0,
                'fatTarget' => max((int) ($apiNutrition['fat_target'] ?? $targets['fat']), 1),
                'water' => $apiNutrition['water'] ?? 0,
                'waterTarget' => (int) ($apiNutrition['water_target'] ?? 8) ?: 8,
                'steps' => $apiNutrition['steps'] ?? 0,
                'stepsTarget' => (int) ($apiNutrition['steps_target'] ?? 10000) ?: 10000,
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
                'calorieTarget' => max((int) ($targets['calories'] ?? 1800), 1),
                'protein' => $todayProtein,
                'proteinTarget' => max((int) ($targets['protein'] ?? 140), 1),
                'carbs' => $todayCarbs,
                'carbsTarget' => max((int) ($targets['carbs'] ?? 200), 1),
                'fat' => $todayFat,
                'fatTarget' => max((int) ($targets['fat'] ?? 55), 1),
                'water' => 0,
                'waterTarget' => 8,
                'steps' => 0,
                'stepsTarget' => 10000,
            ];
        }

        $weeklyData = $this->buildWeeklyCalories($meals, $todayStats['calorieTarget']);

        // Weight progress from API or fallback
        if (!empty($apiWeight)) {
            $currentWeight = $apiWeight['current_weight'] ?? $currentWeight;
            $startWeight = $apiWeight['start_weight'] ?? $currentWeight;
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
            $startWeight = $currentWeight;
            $weightProgress = [];
            $lost = 0;
            $remaining = 0;
            $streakDays = 0;
            $adherenceRate = 0;
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

        return view('user.nutrition', compact('todayStats', 'weeklyData', 'weightProgress', 'stats', 'todayMeals'));
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
            return 0;
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

    public function createOrderFromSubscription(Request $request, OrderApiService $orderApi, SubscriptionApiService $subscriptionApi)
    {
        $subscriptionId = (int) $request->input('subscription_id');

        if ($subscriptionId <= 0) {
            // Try to find active subscription automatically
            $subscriptions = $this->apiData($subscriptionApi->my(), function () {
                return [];
            });
            foreach ($subscriptions as $sub) {
                if (($sub['status'] ?? '') === 'active') {
                    $subscriptionId = $sub['id'] ?? 0;
                    break;
                }
            }
        }

        if ($subscriptionId <= 0) {
            return redirect()->route('user.orders')->with('error', 'No active subscription found.');
        }

        $result = $this->apiData($orderApi->fromSubscription($subscriptionId), function () {
            return [];
        });

        if (empty($result) || !empty($result['error'])) {
            return redirect()->route('user.orders')->with('error', 'Failed to create order. Please try again.');
        }

        return redirect()->route('user.orders')->with('success', 'Order created successfully!');
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

        $zone = $user['location'] ?? '';

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

        $totalDeliveries = count($upcoming) + count($history);
        $delivered = count(array_filter($history, fn ($d) => ($d['status'] ?? '') === 'delivered'));

        $stats = [
            'totalDeliveries' => $totalDeliveries,
            'onTimeRate' => $totalDeliveries > 0 ? round(($delivered / $totalDeliveries) * 100, 1) : 0,
            'avgDeliveryTime' => 'N/A',
            'preferredSlot' => 'N/A',
        ];

        return view('user.delivery', compact('upcoming', 'history', 'stats'));
    }

    public function notifications(NotificationApiService $notificationApi, AuthApiService $authApi)
    {
        $apiNotifications = $this->apiData($notificationApi->my(), function () {
            return [];
        });

        $user = $this->apiData($authApi->me(), function () use ($authApi) {
            return $authApi->user() ?? [];
        });

        $userName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: 'User';

        $notifications = [];

        if (!empty($apiNotifications) && is_array($apiNotifications)) {
            foreach ($apiNotifications as $notification) {
                $createdAt = $notification['created_at'] ?? null;
                $time = $createdAt ? $this->timeAgo($createdAt) : 'Just now';
                $notifications[] = [
                    'id' => $notification['id'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                    'type' => $notification['notification_type'] ?? 'general',
                    'time' => $time,
                    'read' => (bool) ($notification['is_read'] ?? false),
                ];
            }
        }

        $preferences = [];

        $unread = count(array_filter($notifications, fn ($n) => !($n['read'] ?? false)));

        $stats = [
            'unread' => $unread,
            'total' => count($notifications),
        ];

        return view('user.notifications', compact('notifications', 'preferences', 'stats', 'userName'));
    }

    /**
     * Convert datetime to relative time string.
     */
    private function timeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) {
            return 'Just now';
        }
        if ($diff < 3600) {
            return round($diff / 60) . ' min ago';
        }
        if ($diff < 86400) {
            return round($diff / 3600) . ' hour' . (round($diff / 3600) > 1 ? 's' : '') . ' ago';
        }
        if ($diff < 604800) {
            return round($diff / 86400) . ' day' . (round($diff / 86400) > 1 ? 's' : '') . ' ago';
        }

        return date('M d', $time);
    }

    public function settings(ProfileApiService $profileApi)
    {
        $apiUser = $this->apiData($profileApi->fetch(), function () use ($profileApi) {
            return app(AuthApiService::class)->user() ?? [];
        });

        $profile = [
            'name' => trim(($apiUser['first_name'] ?? '') . ' ' . ($apiUser['last_name'] ?? '')),
            'email' => $apiUser['email'] ?? '',
            'phone' => $apiUser['phone'] ?? '',
            'dob' => $apiUser['date_of_birth'] ?? '',
            'gender' => ucfirst($apiUser['gender'] ?? ''),
            'height' => $apiUser['height_cm'] ?? 0,
            'weight' => $apiUser['weight_kg'] ?? 0,
            'goal' => ucfirst(str_replace('_', ' ', $apiUser['fitness_goal'] ?? '')),
            'activity' => $apiUser['activity_level'] ?? '',
            'address' => $apiUser['address'] ?? '',
            'zone' => $apiUser['location'] ?? '',
        ];

        return view('user.settings', compact('profile'));
    }
}
