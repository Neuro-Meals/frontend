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
        MealSelectionApiService $selectionApi,
        OrderApiService $orderApi
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

        // Fetch current-details (today + weekly menu grouped by category)
        $currentDetails = [];
        if ($activeSubscription) {
            $currentDetails = $this->apiData($subscriptionApi->currentDetails(), function () {
                return [];
            });
        }

        // Use real plan data from current-details if available
        if (!empty($currentDetails['plan'])) {
            $planDetails = $currentDetails['plan'];
        }
        // Use real subscription data from current-details if available
        $realSubscription = $currentDetails['subscription'] ?? $activeSubscription ?? [];

        // Fetch real orders from API
        $apiOrders = $this->apiData($orderApi->my(), function () {
            return [];
        });

        // Build today's meals from current-details API
        $todayMeals = [];
        if (!empty($currentDetails['today']['categories'])) {
            foreach ($currentDetails['today']['categories'] as $catGroup) {
                $catName = $catGroup['category']['name_en'] ?? ($catGroup['category']['name_ar'] ?? 'Meal');
                foreach ($catGroup['meals'] ?? [] as $mealItem) {
                    $normalized = $this->normalizeMenuMealForView($mealItem, $catName);
                    $todayMeals[] = $normalized;
                }
            }
        }

        // Build weekly schedule from current-details API
        $weekMeals = [];
        $dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $dayMap = [
            'monday' => 0, 'tuesday' => 1, 'wednesday' => 2, 'thursday' => 3,
            'friday' => 4, 'saturday' => 5, 'sunday' => 6,
        ];

        if (!empty($currentDetails['weekly_menu'])) {
            foreach ($currentDetails['weekly_menu'] as $dayMenu) {
                $dayKey = $dayMenu['day_of_week'] ?? '';
                $dayIndex = $dayMap[$dayKey] ?? 0;
                $dayMeals = [];

                foreach ($dayMenu['categories'] ?? [] as $catGroup) {
                    $catName = $catGroup['category']['name_en'] ?? ($catGroup['category']['name_ar'] ?? 'Meal');
                    foreach ($catGroup['meals'] ?? [] as $mealItem) {
                        $dayMeals[] = $this->normalizeMenuMealForView($mealItem, $catName);
                    }
                }

                $calories = array_sum(array_column($dayMeals, 'calories'));
                $weekMeals[$dayIndex] = [
                    'day' => $dayLabels[$dayIndex] ?? ucfirst($dayKey),
                    'date' => null,
                    'meals' => $dayMeals,
                    'mealCount' => count($dayMeals),
                    'calories' => $calories,
                    'completed' => $calories > 0 && count($dayMeals) > 0,
                ];
            }
        }

        // Fill missing days
        for ($i = 0; $i < 7; $i++) {
            if (!isset($weekMeals[$i])) {
                $weekMeals[$i] = [
                    'day' => $dayLabels[$i],
                    'date' => null,
                    'meals' => [],
                    'mealCount' => 0,
                    'calories' => 0,
                    'completed' => false,
                ];
            }
        }
        ksort($weekMeals);
        $weekMeals = array_values($weekMeals);

        // Fallback: build real meal data from available meals when no subscription
        $hasSubscription = !empty($activeSubscription);
        if (empty($todayMeals) && !empty($meals)) {
            // Pick real meals for today - get variety from different categories
            $todayMeals = [];
            $usedIds = [];
            $categoryMeals = [];
            foreach ($meals as $meal) {
                $cat = $meal['category']['name_en'] ?? ($meal['category_name'] ?? 'Other');
                if (!isset($categoryMeals[$cat])) {
                    $categoryMeals[$cat] = [];
                }
                $categoryMeals[$cat][] = $meal;
            }
            // Pick one meal per category for variety
            foreach ($categoryMeals as $cat => $catMeals) {
                if (count($todayMeals) >= $mealsPerDay) break;
                $pick = $catMeals[array_rand($catMeals)];
                $normalized = $this->normalizeMealForView($pick);
                $normalized['description'] = $pick['description_en'] ?? ($pick['description'] ?? '');
                $todayMeals[] = $normalized;
                $usedIds[] = $pick['id'] ?? 0;
            }
            // Fill remaining slots
            if (count($todayMeals) < $mealsPerDay) {
                foreach ($meals as $meal) {
                    if (count($todayMeals) >= $mealsPerDay) break;
                    if (in_array($meal['id'] ?? 0, $usedIds)) continue;
                    $normalized = $this->normalizeMealForView($meal);
                    $normalized['description'] = $meal['description_en'] ?? ($meal['description'] ?? '');
                    $todayMeals[] = $normalized;
                    $usedIds[] = $meal['id'] ?? 0;
                }
            }
        }

        // Build weekly chart data from real meals when no subscription
        if (empty($weekMeals) || (count(array_filter(array_column($weekMeals, 'mealCount'))) === 0)) {
            if (!empty($meals)) {
                $mealsPerDay = max($mealsPerDay, 3);
                for ($i = 0; $i < 7; $i++) {
                    if (isset($weekMeals[$i]) && $weekMeals[$i]['mealCount'] > 0) continue;
                    $dayMeals = [];
                    $offset = $i * $mealsPerDay;
                    for ($j = 0; $j < $mealsPerDay; $j++) {
                        $idx = ($offset + $j) % count($meals);
                        $meal = $meals[$idx];
                        $normalized = $this->normalizeMealForView($meal);
                        $dayMeals[] = $normalized;
                    }
                    $calories = array_sum(array_column($dayMeals, 'calories'));
                    $weekMeals[$i] = [
                        'day' => $dayLabels[$i],
                        'date' => null,
                        'meals' => $dayMeals,
                        'mealCount' => count($dayMeals),
                        'calories' => $calories,
                        'completed' => false,
                    ];
                }
                ksort($weekMeals);
                $weekMeals = array_values($weekMeals);
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
                'description' => $meal['description'] ?? '',
                'status' => $meal['status'] ?? 'upcoming',
            ];
        }

        $weeklyProgress = $this->buildWeeklyProgressFromSchedule($weekMeals, (int) $calorieTarget);

        // Build recent orders from real API orders
        $recentOrders = [];
        $mealsConsumed = 0;
        $streakDays = 0;
        $nextDelivery = 'N/A';
        $totalOrders = 0;

        if (!empty($apiOrders) && is_array($apiOrders)) {
            $totalOrders = count($apiOrders);
            $deliveredDates = [];
            $upcomingOrders = [];

            foreach ($apiOrders as $order) {
                $status = $order['status'] ?? 'pending';
                $deliveryDate = $order['delivery_date'] ?? ($order['created_at'] ?? date('Y-m-d'));
                $orderDate = date('Y-m-d', strtotime($deliveryDate));

                // Count meals from delivered orders
                if ($status === 'delivered') {
                    $items = $order['items'] ?? [];
                    if (is_array($items)) {
                        foreach ($items as $item) {
                            $mealsConsumed += (int) ($item['quantity'] ?? 1);
                        }
                    }
                    $deliveredDates[] = $orderDate;
                }

                // Track upcoming deliveries
                if (in_array($status, ['scheduled', 'pending', 'confirmed', 'preparing', 'ready_for_delivery', 'out_for_delivery'])) {
                    if (strtotime($deliveryDate) >= strtotime(date('Y-m-d'))) {
                        $upcomingOrders[] = $order;
                    }
                }
            }

            // Calculate streak days from consecutive delivered dates
            if (!empty($deliveredDates)) {
                $deliveredDates = array_unique($deliveredDates);
                sort($deliveredDates);
                $streakDays = 1;
                for ($i = count($deliveredDates) - 1; $i > 0; $i--) {
                    $prev = strtotime($deliveredDates[$i - 1]);
                    $curr = strtotime($deliveredDates[$i]);
                    $diff = ($curr - $prev) / 86400;
                    if ($diff == 1) {
                        $streakDays++;
                    } else {
                        break;
                    }
                }
                // Only count streak if last delivery was today or yesterday
                $lastDelivery = end($deliveredDates);
                $dayDiff = (strtotime(date('Y-m-d')) - strtotime($lastDelivery)) / 86400;
                if ($dayDiff > 1) {
                    $streakDays = 0;
                }
            }

            // Get next delivery from upcoming orders
            if (!empty($upcomingOrders)) {
                usort($upcomingOrders, function ($a, $b) {
                    return strtotime($a['delivery_date'] ?? '') <=> strtotime($b['delivery_date'] ?? '');
                });
                $nextOrder = $upcomingOrders[0];
                $nextDelivery = !empty($nextOrder['delivery_date']) ? date('M d, Y', strtotime($nextOrder['delivery_date'])) : 'Soon';
            }

            // Build recent orders list (latest 4)
            $sortedOrders = $apiOrders;
            usort($sortedOrders, function ($a, $b) {
                return strtotime($b['created_at'] ?? '') <=> strtotime($a['created_at'] ?? '');
            });
            foreach (array_slice($sortedOrders, 0, 4) as $order) {
                $recentOrders[] = [
                    'id' => $order['order_number'] ?? ('ORD-' . ($order['id'] ?? 0)),
                    'plan' => $planName,
                    'amount' => (float) ($order['total_amount'] ?? 0),
                    'status' => $order['status'] ?? 'pending',
                    'date' => $order['delivery_date'] ?? ($order['created_at'] ?? date('Y-m-d')),
                ];
            }
        }

        // Build chart data for Chart.js
        $weekLabels = [];
        $weekCalories = [];
        $weekProtein = [];
        $weekCarbs = [];
        $weekFat = [];
        $weekMealCounts = [];

        foreach ($weekMeals as $day) {
            $weekLabels[] = $day['day'] ?? '';
            $dayCalories = 0;
            $dayProtein = 0;
            $dayCarbs = 0;
            $dayFat = 0;
            $dayMealCount = 0;
            foreach ($day['meals'] ?? [] as $meal) {
                if (($meal['status'] ?? '') !== 'skipped') {
                    $dayCalories += (int) ($meal['calories'] ?? 0);
                    $dayProtein += (int) ($meal['protein'] ?? 0);
                    $dayCarbs += (int) ($meal['carbs'] ?? 0);
                    $dayFat += (int) ($meal['fat'] ?? 0);
                    $dayMealCount++;
                }
            }
            $weekCalories[] = $dayCalories;
            $weekProtein[] = $dayProtein;
            $weekCarbs[] = $dayCarbs;
            $weekFat[] = $dayFat;
            $weekMealCounts[] = $dayMealCount;
        }

        $chartData = [
            'labels' => $weekLabels,
            'calories' => $weekCalories,
            'protein' => $weekProtein,
            'carbs' => $weekCarbs,
            'fat' => $weekFat,
            'mealCounts' => $weekMealCounts,
            'calorieTarget' => (int) $calorieTarget,
        ];

        $currentWeight = $user['weight_kg'] ?? 0;
        $weightGoal = $user['fitness_goal'] === 'weight_loss' ? $currentWeight - 5 : ($user['fitness_goal'] === 'muscle_gain' ? $currentWeight + 3 : $currentWeight);
        $weightStart = $currentWeight + (($user['fitness_goal'] === 'weight_loss' ? 4.3 : -2) * -1);

        // Build weight history data points (simulated trend from start to current)
        $weightHistory = [];
        $weightLabels = [];
        $weightData = [];
        $daysToShow = 7;
        for ($i = $daysToShow - 1; $i >= 0; $i--) {
            $weightLabels[] = date('D', strtotime("-{$i} days"));
            if ($weightStart != $currentWeight) {
                $ratio = ($daysToShow - 1 - $i) / max($daysToShow - 1, 1);
                $weightData[] = round($weightStart + ($currentWeight - $weightStart) * $ratio, 1);
            } else {
                $weightData[] = round($currentWeight, 1);
            }
        }
        $weightHistory = [
            'labels' => $weightLabels,
            'data' => $weightData,
            'start' => round($weightStart, 1),
            'current' => round($currentWeight, 1),
            'goal' => round($weightGoal, 1),
        ];

        $todayStats = $this->calculateTodayStats($todayMeals);

        $totalPlanMeals = (int) ($planDetails['total_meals'] ?? 0);
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
            'planPrice' => $planDetails['price'] ?? $realSubscription['amount'] ?? 0,
            'planRenewal' => !empty($realSubscription['end_date']) ? date('M d, Y', strtotime($realSubscription['end_date'])) : 'N/A',
            'mealsThisWeek' => $mealsThisWeek,
            'mealsTotal' => $totalPlanMeals,
            'mealsConsumed' => $mealsConsumed,
            'remainingMeals' => $remainingMeals,
            'totalOrders' => $totalOrders,
            'dailyCalories' => $todayStats['calories'],
            'calorieTarget' => (int) $calorieTarget,
            'proteinTarget' => (int) $proteinTarget,
            'proteinToday' => $todayStats['protein'],
            'carbsTarget' => (int) $carbsTarget,
            'carbsToday' => $todayStats['carbs'],
            'fatTarget' => (int) $fatTarget,
            'fatToday' => $todayStats['fat'],
            'streakDays' => $streakDays,
            'nextDelivery' => $nextDelivery,
            'nextMeal' => $upcomingMeals[0]['name'] ?? 'N/A',
            'weightStart' => (float) $weightStart,
            'weightCurrent' => (float) $currentWeight,
            'weightGoal' => (float) $weightGoal,
            'subscriptionStatus' => $realSubscription['status'] ?? 'none',
            'subscriptionPaymentStatus' => $realSubscription['payment_status'] ?? 'none',
            'hasSubscription' => $hasSubscription,
            'availableMealsCount' => count($meals),
        ];

        return view('user.dashboard', compact('user', 'stats', 'weeklyProgress', 'upcomingMeals', 'recentOrders', 'activeSubscription', 'chartData', 'weightHistory'));
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

    public function subscriptions(PlanApiService $planApi, SubscriptionApiService $subscriptionApi, PaymentApiService $paymentApi)
    {
        $mySubscriptions = $this->apiData($subscriptionApi->my(), function () {
            return [];
        });

        $plans = $this->apiData($planApi->list(['limit' => 100]), function () {
            return [];
        });

        $myPayments = $this->apiData($paymentApi->my(), function () {
            return [];
        });

        // Index plans by ID for quick lookup
        $plansById = [];
        foreach ($plans as $plan) {
            $plansById[$plan['id'] ?? 0] = $plan;
        }

        // Index payments by subscription ID for receipt lookup
        $paymentsBySubscription = [];
        foreach ($myPayments as $payment) {
            $subId = $payment['subscription_id'] ?? 0;
            if ($subId && !isset($paymentsBySubscription[$subId])) {
                $paymentsBySubscription[$subId] = $payment;
            }
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

        $activePayment = $paymentsBySubscription[$activeSubscription['id'] ?? 0] ?? [];
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
            'period' => !empty($activeSubscription['start_date']) ? date('M Y', strtotime($activeSubscription['start_date'])) : 'N/A',
            'paid_at' => !empty($activePayment['paid_at']) ? date('M d, Y', strtotime($activePayment['paid_at'])) : (!empty($activeSubscription['start_date']) ? date('M d, Y', strtotime($activeSubscription['start_date'])) : 'N/A'),
            'created_at' => !empty($activePayment['created_at']) ? date('M d, Y', strtotime($activePayment['created_at'])) : (!empty($activeSubscription['created_at']) ? date('M d, Y', strtotime($activeSubscription['created_at'])) : 'N/A'),
            'transaction_id' => $activePayment['tap_charge_id'] ?? $activePayment['tap_payment_reference'] ?? $activePayment['tap_gateway_reference'] ?? null,
            'tap_charge_id' => $activePayment['tap_charge_id'] ?? null,
            'tap_payment_reference' => $activePayment['tap_payment_reference'] ?? null,
            'tap_gateway_reference' => $activePayment['tap_gateway_reference'] ?? null,
            'tap_response_code' => $activePayment['tap_response_code'] ?? null,
            'tap_response_message' => $activePayment['tap_response_message'] ?? null,
            'payment_provider' => $activePayment['provider'] ?? 'Tap',
            'currency' => $activePayment['currency'] ?? 'SAR',
            'receipt' => !empty($activePayment) && ($activeSubscription['payment_status'] ?? 'unpaid') === 'paid',
            'pause_count' => (int) ($activeSubscription['pause_count'] ?? 0),
            'max_pauses' => 2,
            'remaining_pauses' => max(0, 2 - (int) ($activeSubscription['pause_count'] ?? 0)),
            'total_paused_seconds' => (int) ($activeSubscription['total_paused_seconds'] ?? 0),
            'total_paused_days' => round((int) ($activeSubscription['total_paused_seconds'] ?? 0) / 86400, 1),
            'max_pause_days' => 7,
            'paused_at' => $activeSubscription['paused_at'] ?? null,
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
                'period' => 'N/A',
                'paid_at' => 'N/A',
                'created_at' => 'N/A',
                'transaction_id' => null,
                'tap_charge_id' => null,
                'tap_payment_reference' => null,
                'tap_gateway_reference' => null,
                'tap_response_code' => null,
                'tap_response_message' => null,
                'payment_provider' => 'Tap',
                'currency' => 'SAR',
                'receipt' => false,
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
            $payment = $paymentsBySubscription[$sub['id'] ?? 0] ?? [];
            $history[] = [
                'id' => $sub['id'] ?? null,
                'plan' => $plan['name_en'] ?? 'Unknown Plan',
                'period' => !empty($sub['start_date']) ? date('M Y', strtotime($sub['start_date'])) : 'N/A',
                'status' => $sub['status'] ?? 'unknown',
                'payment_status' => $sub['payment_status'] ?? 'unpaid',
                'amount' => $sub['amount'] ?? 0,
                'paid_at' => !empty($payment['paid_at']) ? date('M d, Y', strtotime($payment['paid_at'])) : (!empty($sub['start_date']) ? date('M d, Y', strtotime($sub['start_date'])) : 'N/A'),
                'created_at' => !empty($payment['created_at']) ? date('M d, Y', strtotime($payment['created_at'])) : (!empty($sub['created_at']) ? date('M d, Y', strtotime($sub['created_at'])) : 'N/A'),
                'transaction_id' => $payment['tap_charge_id'] ?? $payment['tap_payment_reference'] ?? $payment['tap_gateway_reference'] ?? null,
                'tap_charge_id' => $payment['tap_charge_id'] ?? null,
                'tap_payment_reference' => $payment['tap_payment_reference'] ?? null,
                'tap_gateway_reference' => $payment['tap_gateway_reference'] ?? null,
                'tap_response_code' => $payment['tap_response_code'] ?? null,
                'tap_response_message' => $payment['tap_response_message'] ?? null,
                'payment_provider' => $payment['provider'] ?? 'Tap',
                'currency' => $payment['currency'] ?? 'SAR',
                'receipt' => !empty($payment) && ($sub['payment_status'] ?? 'unpaid') === 'paid',
            ];
        }

        // Build payment history from all user payments
        $paymentHistory = [];
        foreach ($myPayments as $pm) {
            $sub = null;
            foreach ($mySubscriptions as $s) {
                if (($s['id'] ?? null) === ($pm['subscription_id'] ?? null)) {
                    $sub = $s;
                    break;
                }
            }
            $plan = $plansById[$sub['plan_id'] ?? 0] ?? [];
            $pmStatus = $pm['status'] ?? 'pending';
            $paymentHistory[] = [
                'id' => $pm['id'] ?? null,
                'subscription_id' => $pm['subscription_id'] ?? null,
                'plan_name' => $plan['name_en'] ?? 'Unknown Plan',
                'amount' => $pm['amount'] ?? 0,
                'currency' => $pm['currency'] ?? 'SAR',
                'status' => $pmStatus,
                'provider' => $pm['provider'] ?? 'moyasar',
                'provider_payment_id' => $pm['provider_payment_id'] ?? null,
                'paid_at' => !empty($pm['paid_at']) ? date('M d, Y H:i', strtotime($pm['paid_at'])) : null,
                'created_at' => !empty($pm['created_at']) ? date('M d, Y H:i', strtotime($pm['created_at'])) : 'N/A',
                'is_plan_change' => !empty($pm['plan_change_id']),
            ];
        }

        return view('user.subscriptions', compact('activePlan', 'availablePlans', 'history', 'paymentHistory'));
    }

    public function subscribe(Request $request, SubscriptionApiService $subscriptionApi, PaymentApiService $paymentApi)
    {
        $planId = (int) $request->input('plan_id');
        $wantsJson = $request->wantsJson() || $request->input('json') === '1';

        if ($planId <= 0) {
            if ($wantsJson) {
                return response()->json(['success' => false, 'message' => __('Invalid plan selected.')], 422);
            }
            return redirect()->route('user.subscriptions')->with('error', __('Invalid plan selected.'));
        }

        $subscriptionResponse = $subscriptionApi->create(['plan_id' => $planId]);

        $subscriptionId = null;

        if (($subscriptionResponse['success'] ?? true) === false) {
            // Check if error is "already has subscription" — if so, reuse that subscription
            $existingId = $subscriptionResponse['subscription_id']
                ?? ($subscriptionResponse['errors']['subscription_id'] ?? null);
            if (!$existingId) {
                $message = $this->apiErrorMessage($subscriptionResponse);
                if ($wantsJson) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return redirect()->route('user.subscriptions')->with('error', $message);
            }
            $subscriptionId = (int) $existingId;

            // If existing subscription is already paid, use change-plan flow
            $existingStatus = $subscriptionResponse['status'] ?? '';
            $existingPaymentStatus = $subscriptionResponse['payment_status'] ?? '';
            if ($existingPaymentStatus === 'paid') {
                $changeResponse = $subscriptionApi->changePlan($subscriptionId, $planId);

                if (($changeResponse['success'] ?? true) === false) {
                    $message = $this->apiErrorMessage($changeResponse);
                    if ($wantsJson) {
                        return response()->json(['success' => false, 'message' => $message], 400);
                    }
                    return redirect()->route('user.subscriptions')->with('error', $message);
                }

                $changeResult = $changeResponse['data'] ?? $changeResponse;
                $requiresPayment = $changeResult['requires_payment'] ?? false;
                $planChangeId = $changeResult['plan_change']['id'] ?? null;

                if (!$requiresPayment || !$planChangeId) {
                    // Downgrade — no payment needed, scheduled for end of cycle
                    $message = $changeResult['message'] ?? 'Plan change scheduled successfully.';
                    if ($wantsJson) {
                        return response()->json([
                            'success' => true,
                            'message' => $message,
                            'requires_payment' => false,
                        ]);
                    }
                    return redirect()->route('user.subscriptions')->with('success', $message);
                }

                // Upgrade — create plan change checkout
                $checkoutResponse = $paymentApi->createPlanChangeCheckout((int) $planChangeId);
            } else {
                // Existing subscription is unpaid/pending — create regular checkout
                $checkoutResponse = $paymentApi->createCheckout($subscriptionId);
            }
        } else {
            $subscription = $subscriptionResponse['data'] ?? $subscriptionResponse;
            if (empty($subscription) || !empty($subscription['error']) || !isset($subscription['id'])) {
                if ($wantsJson) {
                    return response()->json(['success' => false, 'message' => __('Failed to subscribe. Please try again.')], 400);
                }
                return redirect()->route('user.subscriptions')->with('error', __('Failed to subscribe. Please try again.'));
            }
            $subscriptionId = (int) $subscription['id'];
            $checkoutResponse = $paymentApi->createCheckout($subscriptionId);
        }

        if (($checkoutResponse['success'] ?? true) === false) {
            $message = $this->apiErrorMessage($checkoutResponse);
            \Illuminate\Support\Facades\Log::warning('Subscription payment checkout failed', ['response' => $checkoutResponse]);
            if ($wantsJson) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        $checkout = $checkoutResponse['data'] ?? $checkoutResponse;

        if (!empty($checkout['payment_id']) && !empty($checkout['publishable_api_key'])) {
            if ($wantsJson) {
                return response()->json([
                    'success' => true,
                    'checkout' => $checkout,
                    'subscription_id' => $subscriptionId,
                    'payment_id' => $checkout['payment_id'],
                ]);
            }
            return view('payment.checkout', [
                'checkout' => $checkout,
                'subscriptionId' => $subscriptionId,
            ]);
        }

        \Illuminate\Support\Facades\Log::warning('Subscription payment checkout returned no Moyasar data', ['response' => $checkoutResponse]);
        if ($wantsJson) {
            return response()->json(['success' => false, 'message' => __('Unable to start payment. Please try again.')], 400);
        }
        return redirect()->route('user.subscriptions')->with('success', __('Subscription created! Please complete payment.'));
    }

    protected function apiErrorMessage(array $response): string
    {
        $detail = $response['detail'] ?? null;
        if (is_array($detail)) {
            $detail = $detail['message'] ?? $detail['detail'] ?? json_encode($detail);
        }

        return $detail ?? $response['message'] ?? $response['errors'] ?? 'Request failed. Please try again.';
    }

    public function paySubscription(int $subscriptionId, PaymentApiService $paymentApi)
    {
        if ($subscriptionId <= 0) {
            return redirect()->route('user.subscriptions')->with('error', __('Invalid subscription.'));
        }

        $checkoutResponse = $paymentApi->createCheckout($subscriptionId);

        if (($checkoutResponse['success'] ?? true) === false) {
            $message = $this->apiErrorMessage($checkoutResponse);
            \Illuminate\Support\Facades\Log::warning('Payment checkout failed', ['response' => $checkoutResponse]);
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        $checkout = $checkoutResponse['data'] ?? $checkoutResponse;

        if (!empty($checkout['payment_id']) && !empty($checkout['publishable_api_key'])) {
            return view('payment.checkout', [
                'checkout' => $checkout,
                'subscriptionId' => $subscriptionId,
            ]);
        }

        if (!empty($checkout['error']) || !empty($checkout['detail'])) {
            $message = $this->apiErrorMessage($checkout);
            \Illuminate\Support\Facades\Log::warning('Payment checkout missing Moyasar data', ['response' => $checkoutResponse]);
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        \Illuminate\Support\Facades\Log::warning('Payment checkout returned no Moyasar data', ['response' => $checkoutResponse]);
        return redirect()->route('user.subscriptions')->with('error', __('Unable to start payment. Please try again.'));
    }

    public function checkoutJson(int $subscriptionId, PaymentApiService $paymentApi)
    {
        if ($subscriptionId <= 0) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid subscription.'),
            ], 422);
        }

        $checkoutResponse = $paymentApi->createCheckout($subscriptionId);

        if (($checkoutResponse['success'] ?? true) === false) {
            $message = $this->apiErrorMessage($checkoutResponse);
            \Illuminate\Support\Facades\Log::warning('Payment checkout JSON failed', ['response' => $checkoutResponse]);
            return response()->json([
                'success' => false,
                'message' => $message,
                'response' => $checkoutResponse,
            ], 400);
        }

        $checkout = $checkoutResponse['data'] ?? $checkoutResponse;

        if (!empty($checkout['payment_id']) && !empty($checkout['publishable_api_key'])) {
            return response()->json([
                'success' => true,
                'checkout' => $checkout,
                'payment_id' => $checkout['payment_id'],
                'amount' => $checkout['amount'] ?? 0,
                'currency' => $checkout['currency'] ?? 'SAR',
                'description' => $checkout['description'] ?? '',
                'publishable_api_key' => $checkout['publishable_api_key'],
                'callback_url' => $checkout['callback_url'] ?? '',
                'metadata' => $checkout['metadata'] ?? [],
                'supported_networks' => $checkout['supported_networks'] ?? ['mada', 'visa', 'mastercard'],
                'methods' => $checkout['methods'] ?? ['creditcard'],
            ]);
        }

        \Illuminate\Support\Facades\Log::warning('Payment checkout JSON returned no Moyasar data', ['response' => $checkoutResponse]);
        return response()->json([
            'success' => false,
            'message' => $this->apiErrorMessage($checkout),
            'response' => $checkout,
        ], 400);
    }

    public function attachMoyasarAjax(int $paymentId, Request $request, PaymentApiService $paymentApi)
    {
        $moyasarPaymentId = $request->input('moyasar_payment_id');
        if ($paymentId <= 0 || !$moyasarPaymentId) {
            return response()->json([
                'success' => false,
                'message' => __('Invalid payment data.'),
            ], 422);
        }

        $response = $paymentApi->attachMoyasarPayment($paymentId, $moyasarPaymentId);

        if (($response['success'] ?? true) === false) {
            $message = $this->apiErrorMessage($response);
            \Illuminate\Support\Facades\Log::warning('Moyasar attach failed (AJAX)', ['response' => $response]);
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 400);
        }

        $payment = $response['data'] ?? $response;
        return response()->json([
            'success' => true,
            'payment' => $payment,
            'status' => $payment['status'] ?? 'pending',
        ]);
    }

    public function pauseSubscription(int $subscriptionId, SubscriptionApiService $subscriptionApi)
    {
        if ($subscriptionId <= 0) {
            return redirect()->route('user.subscriptions')->with('error', __('Invalid subscription.'));
        }

        $response = $subscriptionApi->pause($subscriptionId);

        if (($response['success'] ?? true) === false) {
            $message = $this->apiErrorMessage($response);
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        $result = $response['data'] ?? $response;
        if (empty($result['subscription_id']) && empty($result['id'])) {
            $message = $result['detail'] ?? $result['message'] ?? 'Failed to pause subscription. Please try again.';
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        $message = $result['message'] ?? 'Subscription paused successfully.';
        return redirect()->route('user.subscriptions')->with('success', $message);
    }

    public function resumeSubscription(int $subscriptionId, SubscriptionApiService $subscriptionApi)
    {
        if ($subscriptionId <= 0) {
            return redirect()->route('user.subscriptions')->with('error', __('Invalid subscription.'));
        }

        $response = $subscriptionApi->resume($subscriptionId);

        if (($response['success'] ?? true) === false) {
            $message = $this->apiErrorMessage($response);
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        $result = $response['data'] ?? $response;
        if (empty($result['subscription_id']) && empty($result['id'])) {
            $message = $result['detail'] ?? $result['message'] ?? 'Failed to resume subscription. Please try again.';
            return redirect()->route('user.subscriptions')->with('error', $message);
        }

        $message = $result['message'] ?? 'Subscription resumed successfully.';
        return redirect()->route('user.subscriptions')->with('success', $message);
    }

    public function paymentCallback(Request $request, PaymentApiService $paymentApi, AuthApiService $authApi)
    {
        $status = strtolower($request->input('status', ''));
        $message = $request->input('message', '');
        $moyasarPaymentId = $request->input('id');
        $localPaymentId = $request->input('payment_id');
        $paymentId = $localPaymentId;
        $gatewaySaidApproved = in_array($status, ['approved', 'paid', 'succeeded', 'success', 'authorized', 'captured']);

        // Failed / declined / cancelled
        if (in_array($status, ['failed', 'declined', 'cancelled', 'canceled', 'expired'])) {
            $declineReason = urldecode($message);
            return view('payment.cancel', compact('paymentId', 'declineReason', 'status'));
        }

        // Approved / paid / succeeded - verify the payment
        if ($gatewaySaidApproved) {
            $isLoggedIn = $authApi->check();
            $payment = [];
            $verified = false;
            $error = null;
            $chargeId = null;
            $sessionId = null;
            $verifiedStatuses = ['paid', 'approved', 'authorized', 'captured', 'succeeded', 'success'];

            if ($isLoggedIn && $moyasarPaymentId && $localPaymentId) {
                $verifyResponse = $paymentApi->verifyPayment((int) $localPaymentId);
                $result = $verifyResponse['data'] ?? $verifyResponse;

                // Fallback: try attaching Moyasar payment ID first, then verify again
                if (empty($result['id'])) {
                    \Illuminate\Support\Facades\Log::info('Payment callback: initial verify empty, trying attach', [
                        'local_payment_id' => $localPaymentId,
                        'moyasar_id' => $moyasarPaymentId,
                    ]);
                    $attachResponse = $paymentApi->attachMoyasarPayment((int) $localPaymentId, $moyasarPaymentId);
                    if (!empty($attachResponse['success']) && $attachResponse['success'] !== false) {
                        $verifyResponse = $paymentApi->verifyPayment((int) $localPaymentId);
                        $result = $verifyResponse['data'] ?? $verifyResponse;
                    } else {
                        \Illuminate\Support\Facades\Log::warning('Payment callback: attach failed', [
                            'response' => $attachResponse,
                        ]);
                    }
                }

                if (!empty($result['id'])) {
                    $payment = $result;
                    $paymentStatus = strtolower($result['status'] ?? '');
                    $verified = in_array($paymentStatus, $verifiedStatuses);

                    // If gateway said approved but backend status isn't verified yet,
                    // treat as pending rather than error
                    if (!$verified && $gatewaySaidApproved) {
                        \Illuminate\Support\Facades\Log::info('Payment callback: gateway approved but backend status differs', [
                            'backend_status' => $paymentStatus,
                            'gateway_status' => $status,
                        ]);
                        $error = null; // Don't show error - show pending
                    }
                } else {
                    // Backend verification failed entirely, but gateway said approved
                    \Illuminate\Support\Facades\Log::warning('Payment callback: verify returned no payment', [
                        'response' => $verifyResponse,
                        'gateway_status' => $status,
                    ]);
                    // Show pending rather than error since gateway confirmed approval
                    $payment = ['id' => $localPaymentId ?? 'pending', 'status' => 'pending'];
                    $error = null;
                }
            } elseif ($moyasarPaymentId && !$isLoggedIn) {
                // Not logged in but gateway approved - show positive pending state
                $payment = ['id' => $localPaymentId ?? 'pending', 'status' => 'pending'];
                $error = null;
            } else {
                $payment = ['id' => $localPaymentId ?? 'pending', 'status' => 'pending'];
                $error = null;
            }

            return view('payment.success', compact('payment', 'verified', 'error', 'chargeId', 'paymentId', 'sessionId', 'isLoggedIn', 'moyasarPaymentId'));
        }

        // Unknown status - treat as pending/cancel
        $declineReason = $message ? urldecode($message) : null;
        return view('payment.cancel', compact('paymentId', 'declineReason', 'status'));
    }

    public function paymentSuccess(Request $request, PaymentApiService $paymentApi, AuthApiService $authApi)
    {
        // Moyasar redirect: ?id=MOYASAR_PAYMENT_ID&payment_id=LOCAL_PAYMENT_ID
        $moyasarPaymentId = $request->input('id');
        $localPaymentId = $request->input('payment_id');

        // Legacy Tap/Stripe params
        $chargeId = $request->input('tap_id') ?? $request->input('charge_id');
        $paymentId = $localPaymentId ?? $request->input('payment_id');
        $sessionId = $request->input('session_id');
        $isLoggedIn = $authApi->check();

        $payment = [];
        $verified = false;
        $error = null;
        $verifiedStatuses = ['paid', 'approved', 'authorized', 'captured', 'succeeded', 'success'];

        // Moyasar flow: verify payment after 3DS redirect (attach was done before redirect via AJAX)
        if ($isLoggedIn && $moyasarPaymentId && $localPaymentId) {
            $verifyResponse = $paymentApi->verifyPayment((int) $localPaymentId);
            $result = $verifyResponse['data'] ?? $verifyResponse;

            // Fallback: if verify fails for any reason, try attaching the
            // Moyasar payment ID first, then verify again. This covers cases
            // where the AJAX attach before 3DS redirect failed or was skipped.
            if (empty($result['id'])) {
                $attachResponse = $paymentApi->attachMoyasarPayment((int) $localPaymentId, $moyasarPaymentId);
                if (!empty($attachResponse['success']) && $attachResponse['success'] !== false) {
                    $verifyResponse = $paymentApi->verifyPayment((int) $localPaymentId);
                    $result = $verifyResponse['data'] ?? $verifyResponse;
                } else {
                    \Illuminate\Support\Facades\Log::warning('Moyasar fallback attach failed', ['response' => $attachResponse]);
                }
            }

            if (!empty($result['id'])) {
                $payment = $result;
                $verified = in_array(strtolower($result['status'] ?? ''), $verifiedStatuses);
            } else {
                // Verification failed but we have Moyasar params - show pending, not error
                \Illuminate\Support\Facades\Log::warning('Moyasar payment verify failed, showing pending', ['response' => $verifyResponse]);
                $payment = ['id' => $localPaymentId ?? 'pending', 'status' => 'pending'];
                $error = null;
            }
        }
        // Tap gateway: verify by charge_id (tap_id) if available.
        elseif ($isLoggedIn && $chargeId) {
            $response = $paymentApi->verifyCharge($chargeId);
            $result = $response['data'] ?? $response;

            if (!empty($result['id'])) {
                $payment = $result;
                $verified = in_array(strtolower($result['status'] ?? ''), $verifiedStatuses);
            } else {
                \Illuminate\Support\Facades\Log::warning('Payment charge verification failed, showing pending', ['response' => $response]);
                $payment = ['id' => $localPaymentId ?? 'pending', 'status' => 'pending'];
                $error = null;
            }
        }
        // Legacy Stripe fallback
        elseif ($isLoggedIn && $sessionId) {
            $response = $paymentApi->verifySession($sessionId);
            $result = $response['data'] ?? $response;

            if (!empty($result['id'])) {
                $payment = $result;
                $verified = in_array(strtolower($result['status'] ?? ''), $verifiedStatuses);
            } else {
                \Illuminate\Support\Facades\Log::warning('Payment session verification failed, showing pending', ['response' => $response]);
                $payment = ['id' => $localPaymentId ?? 'pending', 'status' => 'pending'];
                $error = null;
            }
        } elseif ($moyasarPaymentId && !$isLoggedIn) {
            $payment = ['id' => $localPaymentId ?? 'pending', 'status' => 'pending'];
            $error = null;
        } elseif ($moyasarPaymentId && !$localPaymentId) {
            $payment = ['id' => 'pending', 'status' => 'pending'];
            $error = null;
        } elseif ($chargeId && !$isLoggedIn) {
            $payment = ['id' => $localPaymentId ?? 'pending', 'status' => 'pending'];
            $error = null;
        } else {
            $payment = ['id' => 'pending', 'status' => 'pending'];
            $error = null;
        }

        return view('payment.success', compact('payment', 'verified', 'error', 'chargeId', 'paymentId', 'sessionId', 'isLoggedIn', 'moyasarPaymentId'));
    }

    public function paymentCancel(Request $request)
    {
        $paymentId = $request->input('payment_id');

        return view('payment.cancel', compact('paymentId'));
    }

    public function meals(
        Request $request,
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

        // Fetch all meal categories for icons and display
        $allCategoriesData = $this->apiData($mealApi->categoriesList(['limit' => 100]), function () {
            return [];
        });

        $allCategories = [];
        $categoryIcons = [];
        foreach ($allCategoriesData as $cat) {
            $catId = $cat['id'] ?? 0;
            $catName = $cat['name_en'] ?? ($cat['name_ar'] ?? 'Uncategorized');
            $allCategories[$catId] = [
                'id' => $catId,
                'name' => $catName,
                'icon' => $this->getCategoryIcon($catName),
            ];
            $categoryIcons[$catId] = $this->getCategoryIcon($catName);
        }

        // Fetch current-details (today + weekly menu grouped by category)
        $currentDetails = [];
        if ($activeSubscription) {
            $currentDetails = $this->apiData($subscriptionApi->currentDetails(), function () {
                return [];
            });
        }

        // Build today's meals grouped by category
        $todayMealsByCategory = [];
        $todayMeals = [];

        if (!empty($currentDetails['today']['categories'])) {
            foreach ($currentDetails['today']['categories'] as $catGroup) {
                $catInfo = $catGroup['category'] ?? null;
                $catId = $catInfo['id'] ?? 0;
                $catName = $catInfo['name_en'] ?? ($catInfo['name_ar'] ?? 'Meal');
                $catIcon = $categoryIcons[$catId] ?? $this->getCategoryIcon($catName);

                $catMeals = [];
                foreach ($catGroup['meals'] ?? [] as $mealItem) {
                    $normalized = $this->normalizeMenuMealForView($mealItem, $catName);
                    $catMeals[] = $normalized;
                    $todayMeals[] = $normalized;
                }

                if (!empty($catMeals)) {
                    $todayMealsByCategory[] = [
                        'id' => $catId,
                        'name' => $catName,
                        'icon' => $catIcon,
                        'meals' => $catMeals,
                    ];
                }
            }
        }

        // Fallback: if no scheduled meals, show first available meals
        if (empty($todayMeals)) {
            foreach (array_slice($meals, 0, $mealsPerDay) as $meal) {
                $normalized = $this->normalizeMealForView($meal);
                $todayMeals[] = $normalized;
            }
            // Group fallback meals by their category
            $grouped = [];
            foreach ($todayMeals as $meal) {
                $catName = $meal['category'] ?? 'Meal';
                if (!isset($grouped[$catName])) {
                    $grouped[$catName] = [
                        'name' => $catName,
                        'icon' => $this->getCategoryIcon($catName),
                        'meals' => [],
                    ];
                }
                $grouped[$catName]['meals'][] = $meal;
            }
            $todayMealsByCategory = array_values($grouped);
        }

        // Build weekly schedule grouped by day and category
        $weekMeals = [];
        $dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $dayMap = [
            'monday' => 0, 'tuesday' => 1, 'wednesday' => 2, 'thursday' => 3,
            'friday' => 4, 'saturday' => 5, 'sunday' => 6,
        ];

        // Calculate this week's dates (Monday to Sunday) with optional week offset
        $weekParam = $request->query('week', 'current');
        $weekOffset = 0;
        if ($weekParam === 'prev') $weekOffset = -1;
        elseif ($weekParam === 'next') $weekOffset = 1;

        $todayDate = new \DateTime();
        $mondayDate = (clone $todayDate)->modify('monday this week')->modify("{$weekOffset} week");
        $weekDates = [];
        for ($i = 0; $i < 7; $i++) {
            $d = (clone $mondayDate)->modify("+{$i} days");
            $weekDates[$i] = $d->format('Y-m-d');
        }

        if (!empty($currentDetails['weekly_menu'])) {
            foreach ($currentDetails['weekly_menu'] as $dayMenu) {
                $dayKey = $dayMenu['day_of_week'] ?? '';
                $dayIndex = $dayMap[$dayKey] ?? 0;
                $dayMeals = [];
                $dayCategories = [];

                foreach ($dayMenu['categories'] ?? [] as $catGroup) {
                    $catInfo = $catGroup['category'] ?? null;
                    $catId = $catInfo['id'] ?? 0;
                    $catName = $catInfo['name_en'] ?? ($catInfo['name_ar'] ?? 'Meal');
                    $catIcon = $categoryIcons[$catId] ?? $this->getCategoryIcon($catName);

                    $catMeals = [];
                    foreach ($catGroup['meals'] ?? [] as $mealItem) {
                        $normalized = $this->normalizeMenuMealForView($mealItem, $catName);
                        $catMeals[] = $normalized;
                        $dayMeals[] = $normalized;
                    }

                    if (!empty($catMeals)) {
                        $dayCategories[] = [
                            'id' => $catId,
                            'name' => $catName,
                            'icon' => $catIcon,
                            'meals' => $catMeals,
                        ];
                    }
                }

                $calories = array_sum(array_column($dayMeals, 'calories'));
                $weekMeals[$dayIndex] = [
                    'day' => $dayLabels[$dayIndex] ?? ucfirst($dayKey),
                    'date' => $weekDates[$dayIndex] ?? null,
                    'meals' => $dayMeals,
                    'categories' => $dayCategories,
                    'mealCount' => count($dayMeals),
                    'calories' => $calories,
                    'completed' => $calories > 0 && count($dayMeals) > 0,
                ];
            }
        }

        // Fill missing days
        for ($i = 0; $i < 7; $i++) {
            if (!isset($weekMeals[$i])) {
                $weekMeals[$i] = [
                    'day' => $dayLabels[$i],
                    'date' => $weekDates[$i] ?? null,
                    'meals' => [],
                    'categories' => [],
                    'mealCount' => 0,
                    'calories' => 0,
                    'completed' => false,
                ];
            }
        }
        ksort($weekMeals);
        $weekMeals = array_values($weekMeals);

        // Calculate stats
        $totalThisWeek = 0;
        foreach ($weekMeals as $day) {
            $totalThisWeek += $day['mealCount'] ?? 0;
        }
        $avgCalories = $this->calculateAvgCalories($todayMeals);
        $favoriteMeal = $this->findFavoriteMeal($todayMeals);

        // Calculate today's nutrition totals from real meal data
        $todayStats = $this->calculateTodayStats($todayMeals);

        // Plan targets from API
        $calorieTarget = $planDetails['calories'] ?? $activeSubscription['calorie_target'] ?? 1800;
        $proteinTarget = $planDetails['protein_target'] ?? $activeSubscription['protein_target'] ?? 140;
        $carbsTarget = $planDetails['carbs_target'] ?? $activeSubscription['carbs_target'] ?? 200;
        $fatTarget = $planDetails['fat_target'] ?? $activeSubscription['fat_target'] ?? 55;

        // Subscription progress
        $remainingMeals = max(0, $totalPlanMeals - $mealsConsumed);
        $planProgress = $totalPlanMeals > 0 ? round(($mealsConsumed / $totalPlanMeals) * 100) : 0;

        // Days remaining in subscription
        $daysRemaining = 0;
        if (!empty($activeSubscription['end_date'])) {
            $endDate = strtotime($activeSubscription['end_date']);
            $now = time();
            $daysRemaining = max(0, (int) ceil(($endDate - $now) / 86400));
        }

        $subscriptionStatus = $activeSubscription['status'] ?? 'none';
        $subscriptionPaymentStatus = $activeSubscription['payment_status'] ?? 'unpaid';

        $stats = [
            'totalThisWeek' => $totalThisWeek,
            'totalPlan' => $totalPlanMeals,
            'remaining' => $remainingMeals,
            'mealsConsumed' => $mealsConsumed,
            'planProgress' => $planProgress,
            'avgCalories' => $avgCalories,
            'favoriteMeal' => $favoriteMeal['name'] ?? 'N/A',
            'favoriteCount' => $favoriteMeal['count'] ?? 0,
            // Today's real macros from API meal data
            'todayCalories' => $todayStats['calories'],
            'todayProtein' => $todayStats['protein'],
            'todayCarbs' => $todayStats['carbs'],
            'todayFat' => $todayStats['fat'],
            // Plan targets from API
            'calorieTarget' => (int) $calorieTarget,
            'proteinTarget' => (int) $proteinTarget,
            'carbsTarget' => (int) $carbsTarget,
            'fatTarget' => (int) $fatTarget,
            'mealsPerDay' => $mealsPerDay,
            // Subscription info
            'daysRemaining' => $daysRemaining,
            'subscriptionStatus' => $subscriptionStatus,
            'subscriptionPaymentStatus' => $subscriptionPaymentStatus,
            'planName' => $planDetails['name_en'] ?? $activeSubscription['plan_name'] ?? 'Active Plan',
            'planPrice' => $planDetails['price'] ?? $activeSubscription['amount'] ?? 0,
            'planRenewal' => !empty($activeSubscription['end_date']) ? date('M d, Y', strtotime($activeSubscription['end_date'])) : 'N/A',
        ];

        $hasActiveSubscription = $activeSubscription !== null;

        return view('user.meals', compact('todayMeals', 'todayMealsByCategory', 'weekMeals', 'stats', 'activeSubscription', 'hasActiveSubscription', 'allCategories'));
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
     * Normalize a meal from the plan menu API (build_customer_menu_item format).
     */
    private function normalizeMenuMealForView(array $mealItem, string $categoryName): array
    {
        return [
            'id' => $mealItem['id'] ?? 0,
            'name' => $mealItem['name_en'] ?? ($mealItem['name'] ?? 'Meal'),
            'time' => $categoryName,
            'calories' => (int) ($mealItem['calories'] ?? 0),
            'protein' => (int) ($mealItem['protein_g'] ?? 0),
            'carbs' => (int) ($mealItem['carbs_g'] ?? 0),
            'fat' => (int) ($mealItem['fat_g'] ?? 0),
            'status' => 'upcoming',
            'image' => $mealItem['image_url'] ?? null,
            'price' => $mealItem['price'] ?? 0,
            'category' => $categoryName,
            'quantity' => $mealItem['quantity'] ?? 1,
            'ingredients' => $mealItem['ingredients'] ?? [],
            'allergens' => $mealItem['allergens'] ?? [],
        ];
    }

    /**
     * Get icon name for a meal category based on its name.
     */
    private function getCategoryIcon(string $name): string
    {
        $nameLower = strtolower($name);
        if (str_contains($nameLower, 'breakfast')) return 'sunrise';
        if (str_contains($nameLower, 'lunch')) return 'sun';
        if (str_contains($nameLower, 'dinner')) return 'moon';
        if (str_contains($nameLower, 'snack')) return 'cookie';
        return 'dots';
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
            $todayCalories = 0;
            $todayProtein = 0;
            $todayCarbs = 0;
            $todayFat = 0;
            foreach ($todayMeals as $meal) {
                $todayCalories += (int) ($meal['calories'] ?? 0);
                $todayProtein += (int) ($meal['protein'] ?? 0);
                $todayCarbs += (int) ($meal['carbs'] ?? 0);
                $todayFat += (int) ($meal['fat'] ?? 0);
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

        // Fetch active subscription info
        $mySubscriptions = $this->apiData($subscriptionApi->my(), function () {
            return [];
        });

        $activeSubscription = null;
        foreach ($mySubscriptions as $sub) {
            $status = $sub['status'] ?? '';
            $paymentStatus = $sub['payment_status'] ?? '';
            if ($status === 'active' || ($status === 'pending_payment' && $paymentStatus === 'paid') || $status === 'paused') {
                $activeSubscription = $sub;
                break;
            }
        }

        // Get plan details for the active subscription
        $planDetails = [];
        if ($activeSubscription && !empty($activeSubscription['plan_id'])) {
            $planDetails = $this->apiData($planApi->show($activeSubscription['plan_id']), function () {
                return [];
            });
        }

        $orders = [];
        $total = 0;
        $delivered = 0;
        $cancelled = 0;
        $inProgress = 0;
        $totalSpent = 0;
        $totalCalories = 0;
        $upcomingCount = 0;

        if (!empty($apiOrders) && is_array($apiOrders)) {
            foreach ($apiOrders as $order) {
                $status = $order['status'] ?? 'pending';
                $amount = (float) ($order['total_amount'] ?? 0);
                $deliveryDate = $order['delivery_date'] ?? ($order['created_at'] ?? date('Y-m-d'));

                // Parse order items and group by category
                $rawItems = $order['items'] ?? [];
                if (!is_array($rawItems)) {
                    $rawItems = [];
                }

                $mealsByCategory = [];
                $orderCalories = 0;
                $orderProtein = 0;
                $orderCarbs = 0;
                $orderFat = 0;
                $mealCount = 0;

                foreach ($rawItems as $item) {
                    if (!is_array($item)) continue;

                    $catName = $item['category_name'] ?? 'Other';
                    $catLower = strtolower($catName);
                    if (str_contains($catLower, 'breakfast')) {
                        $catKey = 'breakfast';
                        $catIcon = 'sunrise';
                    } elseif (str_contains($catLower, 'lunch')) {
                        $catKey = 'lunch';
                        $catIcon = 'sun';
                    } elseif (str_contains($catLower, 'dinner') || str_contains($catLower, 'supper')) {
                        $catKey = 'dinner';
                        $catIcon = 'moon';
                    } elseif (str_contains($catLower, 'snack')) {
                        $catKey = 'snacks';
                        $catIcon = 'cookie';
                    } else {
                        $catKey = 'other';
                        $catIcon = 'other';
                    }

                    if (!isset($mealsByCategory[$catKey])) {
                        $mealsByCategory[$catKey] = [
                            'name' => ucfirst($catKey),
                            'icon' => $catIcon,
                            'meals' => [],
                        ];
                    }

                    $quantity = (int) ($item['quantity'] ?? 1);
                    $calories = (int) ($item['calories'] ?? 0);

                    $mealsByCategory[$catKey]['meals'][] = [
                        'name' => $item['meal_name'] ?? 'Meal',
                        'quantity' => $quantity,
                        'calories' => $calories,
                        'protein' => (int) ($item['protein_g'] ?? 0),
                        'carbs' => (int) ($item['carbs_g'] ?? 0),
                        'fat' => (int) ($item['fat_g'] ?? 0),
                        'image' => $item['image_url'] ?? null,
                        'unit_price' => (float) ($item['unit_price'] ?? 0),
                        'line_total' => (float) ($item['line_total'] ?? 0),
                    ];

                    $orderCalories += $calories * $quantity;
                    $orderProtein += (int) ($item['protein_g'] ?? 0) * $quantity;
                    $orderCarbs += (int) ($item['carbs_g'] ?? 0) * $quantity;
                    $orderFat += (int) ($item['fat_g'] ?? 0) * $quantity;
                    $mealCount += $quantity;
                }

                // Sort categories by meal time order
                $catOrder = ['breakfast', 'lunch', 'dinner', 'snacks', 'other'];
                $sortedCategories = [];
                foreach ($catOrder as $key) {
                    if (isset($mealsByCategory[$key])) {
                        $sortedCategories[] = $mealsByCategory[$key];
                    }
                }

                $orders[] = [
                    'id' => $order['order_number'] ?? ('ORD-' . $order['id']),
                    'raw_id' => $order['id'] ?? 0,
                    'plan' => $planDetails['name_en'] ?? ($order['plan_name'] ?? 'Plan'),
                    'meals' => $mealCount,
                    'amount' => $amount,
                    'date' => $deliveryDate,
                    'created_at' => $order['created_at'] ?? date('Y-m-d'),
                    'status' => $status,
                    'delivery_address' => $order['delivery_address'] ?? '',
                    'delivery_date' => $deliveryDate,
                    'categories' => $sortedCategories,
                    'total_calories' => $orderCalories,
                    'total_protein' => $orderProtein,
                    'total_carbs' => $orderCarbs,
                    'total_fat' => $orderFat,
                ];

                $total++;
                $totalSpent += $amount;
                $totalCalories += $orderCalories;

                if ($status === 'delivered') {
                    $delivered++;
                } elseif ($status === 'cancelled') {
                    $cancelled++;
                } else {
                    $inProgress++;
                    if (strtotime($deliveryDate) >= strtotime(date('Y-m-d'))) {
                        $upcomingCount++;
                    }
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
            'inProgress' => $inProgress,
            'upcoming' => $upcomingCount,
            'totalSpent' => $totalSpent,
            'avgOrder' => $total > 0 ? round($totalSpent / $total) : 0,
            'totalCalories' => $totalCalories,
            'avgCalories' => $total > 0 ? round($totalCalories / max($total, 1)) : 0,
        ];

        // Build subscription info for the view
        $subscriptionInfo = null;
        if ($activeSubscription) {
            $mealsConsumed = $activeSubscription['meals_consumed'] ?? 0;
            $totalPlanMeals = $planDetails['total_meals'] ?? 84;
            $remaining = max(0, $totalPlanMeals - $mealsConsumed);
            $subscriptionInfo = [
                'plan_name' => $planDetails['name_en'] ?? $activeSubscription['plan_name'] ?? 'Active Plan',
                'status' => $activeSubscription['status'] ?? 'active',
                'meals_consumed' => $mealsConsumed,
                'total_meals' => $totalPlanMeals,
                'remaining' => $remaining,
                'progress' => $totalPlanMeals > 0 ? round(($mealsConsumed / $totalPlanMeals) * 100) : 0,
                'meals_per_day' => $planDetails['meals_per_day'] ?? 3,
                'start_date' => !empty($activeSubscription['start_date']) ? date('M d, Y', strtotime($activeSubscription['start_date'])) : 'N/A',
                'end_date' => !empty($activeSubscription['end_date']) ? date('M d, Y', strtotime($activeSubscription['end_date'])) : 'N/A',
                'payment_status' => $activeSubscription['payment_status'] ?? 'unpaid',
            ];
        }

        return view('user.orders', compact('orders', 'stats', 'subscriptionInfo'));
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
            return redirect()->route('user.orders')->with('error', __('No active subscription found.'));
        }

        $result = $this->apiData($orderApi->fromSubscription($subscriptionId), function () {
            return [];
        });

        if (empty($result) || !empty($result['error'])) {
            return redirect()->route('user.orders')->with('error', __('Failed to create order. Please try again.'));
        }

        return redirect()->route('user.orders')->with('success', __('Order created successfully!'));
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

        $today = date('Y-m-d');
        $todayDeliveries = [];
        $totalToday = 0;
        $deliveredToday = 0;
        $inTransitToday = 0;
        $pendingToday = 0;
        $totalMealsToday = 0;

        // Delivery status steps for stepper
        $statusSteps = [
            'pending' => ['label' => 'Order Placed', 'icon' => 'clipboard', 'step' => 1],
            'assigned' => ['label' => 'Driver Assigned', 'icon' => 'user', 'step' => 2],
            'picked_up' => ['label' => 'Picked Up', 'icon' => 'bag', 'step' => 3],
            'out_for_delivery' => ['label' => 'On the Way', 'icon' => 'truck', 'step' => 4],
            'delivered' => ['label' => 'Delivered', 'icon' => 'check', 'step' => 5],
        ];

        if (!empty($apiDeliveries) && is_array($apiDeliveries)) {
            foreach ($apiDeliveries as $delivery) {
                $status = $delivery['status'] ?? 'pending';
                $scheduledAt = $delivery['scheduled_at'] ?? null;
                $createdAt = $delivery['created_at'] ?? null;
                $pickedUpAt = $delivery['picked_up_at'] ?? null;
                $deliveredAt = $delivery['delivered_at'] ?? null;

                // Filter: only today's deliveries
                $deliveryDate = $scheduledAt ?? $createdAt;
                if ($deliveryDate && date('Y-m-d', strtotime($deliveryDate)) !== $today) {
                    continue;
                }

                $order = $delivery['order'] ?? [];
                $driver = $delivery['driver'] ?? null;
                $mealCount = $delivery['meal_count'] ?? 0;

                // Parse order items for meal names
                $mealNames = [];
                if (!empty($order['items']) && is_array($order['items'])) {
                    $items = $order['items'];
                    if (isset($items['items']) && is_array($items['items'])) {
                        $items = $items['items'];
                    }
                    foreach ($items as $item) {
                        if (is_array($item) && isset($item['meal_name'])) {
                            $mealNames[] = $item['meal_name'];
                        }
                    }
                }

                // Build stepper data
                $currentStep = $statusSteps[$status]['step'] ?? 1;
                $stepper = [];
                foreach ($statusSteps as $stepKey => $stepInfo) {
                    $stepReached = $stepInfo['step'] <= $currentStep;
                    $stepTime = null;
                    if ($stepKey === 'pending' && $createdAt) {
                        $stepTime = date('H:i', strtotime($createdAt));
                    } elseif ($stepKey === 'picked_up' && $pickedUpAt) {
                        $stepTime = date('H:i', strtotime($pickedUpAt));
                    } elseif ($stepKey === 'delivered' && $deliveredAt) {
                        $stepTime = date('H:i', strtotime($deliveredAt));
                    } elseif ($stepKey === 'assigned' && $driver) {
                        $stepTime = $driver ? 'Assigned' : null;
                    } elseif ($stepKey === 'out_for_delivery' && $status === 'out_for_delivery') {
                        $stepTime = date('H:i', strtotime($delivery['updated_at'] ?? 'now'));
                    }

                    $stepper[] = [
                        'key' => $stepKey,
                        'label' => $stepInfo['label'],
                        'icon' => $stepInfo['icon'],
                        'step' => $stepInfo['step'],
                        'completed' => $stepReached && $status !== 'pending' ? true : ($stepKey === 'pending' ? true : $stepReached),
                        'active' => $stepKey === $status,
                        'time' => $stepTime,
                    ];
                }

                $todayDeliveries[] = [
                    'id' => 'DLV-' . $delivery['id'],
                    'order_number' => $order['order_number'] ?? ('ORD-' . $delivery['order_id']),
                    'status' => $status,
                    'scheduled_time' => $scheduledAt ? date('H:i', strtotime($scheduledAt)) : '--:--',
                    'scheduled_date' => $scheduledAt ? date('M d, Y', strtotime($scheduledAt)) : 'Today',
                    'address' => $delivery['delivery_address'] ?? $user['address'] ?? '',
                    'notes' => $delivery['delivery_notes'] ?? '',
                    'driver_name' => $driver['full_name'] ?? null,
                    'driver_phone' => $driver['phone'] ?? null,
                    'meal_count' => $mealCount,
                    'meal_names' => $mealNames,
                    'stepper' => $stepper,
                    'current_step' => $currentStep,
                    'total_steps' => 5,
                    'picked_up_at' => $pickedUpAt ? date('H:i', strtotime($pickedUpAt)) : null,
                    'delivered_at' => $deliveredAt ? date('H:i', strtotime($deliveredAt)) : null,
                    'latitude' => $delivery['current_latitude'] ?? null,
                    'longitude' => $delivery['current_longitude'] ?? null,
                    'failure_reason' => $delivery['failure_reason'] ?? null,
                ];

                $totalToday++;
                $totalMealsToday += $mealCount;
                if ($status === 'delivered') {
                    $deliveredToday++;
                } elseif (in_array($status, ['picked_up', 'out_for_delivery'])) {
                    $inTransitToday++;
                } elseif (in_array($status, ['pending', 'assigned'])) {
                    $pendingToday++;
                }
            }
        }

        // Sort by scheduled time ascending
        usort($todayDeliveries, function ($a, $b) {
            return strcmp($a['scheduled_time'], $b['scheduled_time']);
        });

        $stats = [
            'totalToday' => $totalToday,
            'deliveredToday' => $deliveredToday,
            'inTransitToday' => $inTransitToday,
            'pendingToday' => $pendingToday,
            'totalMealsToday' => $totalMealsToday,
            'completionRate' => $totalToday > 0 ? round(($deliveredToday / $totalToday) * 100) : 0,
        ];

        return view('user.delivery', compact('todayDeliveries', 'stats'));
    }

    public function notifications(NotificationApiService $notificationApi, AuthApiService $authApi)
    {
        $apiNotifications = $this->apiData($notificationApi->my(['limit' => 100]), function () {
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
                $type = $notification['notification_type'] ?? 'general';
                $notifications[] = [
                    'id' => $notification['id'],
                    'title' => $notification['title'] ?? 'Notification',
                    'message' => $notification['message'] ?? '',
                    'type' => $type,
                    'channel' => $notification['channel'] ?? 'in_app',
                    'time' => $time,
                    'read' => (bool) ($notification['is_read'] ?? false),
                    'created_at' => $createdAt,
                ];
            }
        }

        $unread = count(array_filter($notifications, fn ($n) => !($n['read'] ?? false)));

        // Count by type
        $byType = [];
        foreach ($notifications as $n) {
            $type = $n['type'];
            if (!isset($byType[$type])) {
                $byType[$type] = 0;
            }
            $byType[$type]++;
        }

        $stats = [
            'unread' => $unread,
            'total' => count($notifications),
            'byType' => $byType,
        ];

        return view('user.notifications', compact('notifications', 'stats', 'userName'));
    }

    public function markNotificationRead(Request $request, int $id, NotificationApiService $notificationApi)
    {
        $response = $this->apiData($notificationApi->markAsRead($id), fn () => []);
        $success = is_array($response);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success]);
        }

        return redirect()->route('user.notifications')->with('success', __('Notification marked as read.'));
    }

    public function markAllNotificationsRead(Request $request, NotificationApiService $notificationApi)
    {
        $response = $this->apiData($notificationApi->markAllAsRead(), fn () => []);
        $success = is_array($response) || !empty($response);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success]);
        }

        return redirect()->route('user.notifications')->with('success', __('All notifications marked as read.'));
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

    public function settings(ProfileApiService $profileApi, SubscriptionApiService $subscriptionApi, PlanApiService $planApi, PaymentApiService $paymentApi)
    {
        $apiUser = $this->apiData($profileApi->fetch(), function () use ($profileApi) {
            return app(AuthApiService::class)->user() ?? [];
        });

        $profile = [
            'name' => trim(($apiUser['first_name'] ?? '') . ' ' . ($apiUser['last_name'] ?? '')),
            'first_name' => $apiUser['first_name'] ?? '',
            'last_name' => $apiUser['last_name'] ?? '',
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

        // Fetch active subscription
        $mySubscriptions = $this->apiData($subscriptionApi->my(), function () {
            return [];
        });

        $activeSubscription = null;
        foreach ($mySubscriptions as $sub) {
            $status = $sub['status'] ?? '';
            $paymentStatus = $sub['payment_status'] ?? '';
            if ($status === 'active' || ($status === 'pending_payment' && $paymentStatus === 'paid') || $status === 'paused') {
                $activeSubscription = $sub;
                break;
            }
        }

        $planDetails = [];
        if ($activeSubscription && !empty($activeSubscription['plan_id'])) {
            $planDetails = $this->apiData($planApi->show($activeSubscription['plan_id']), function () {
                return [];
            });
        }

        $subscriptionInfo = null;
        if ($activeSubscription) {
            $mealsConsumed = $activeSubscription['meals_consumed'] ?? 0;
            $totalPlanMeals = $planDetails['total_meals'] ?? 84;
            $remaining = max(0, $totalPlanMeals - $mealsConsumed);
            $subscriptionInfo = [
                'id' => $activeSubscription['id'] ?? null,
                'plan_name' => $planDetails['name_en'] ?? $activeSubscription['plan_name'] ?? 'Active Plan',
                'status' => $activeSubscription['status'] ?? 'active',
                'payment_status' => $activeSubscription['payment_status'] ?? 'unpaid',
                'meals_consumed' => $mealsConsumed,
                'total_meals' => $totalPlanMeals,
                'remaining' => $remaining,
                'progress' => $totalPlanMeals > 0 ? round(($mealsConsumed / $totalPlanMeals) * 100) : 0,
                'meals_per_day' => $planDetails['meals_per_day'] ?? 3,
                'start_date' => !empty($activeSubscription['start_date']) ? date('M d, Y', strtotime($activeSubscription['start_date'])) : 'N/A',
                'end_date' => !empty($activeSubscription['end_date']) ? date('M d, Y', strtotime($activeSubscription['end_date'])) : 'N/A',
                'price' => $planDetails['price'] ?? ($activeSubscription['amount'] ?? 0),
                'duration_days' => $planDetails['duration_days'] ?? 28,
                'calories' => $planDetails['calories'] ?? '1500-1800',
                'pause_count' => (int) ($activeSubscription['pause_count'] ?? 0),
                'remaining_pauses' => max(0, 2 - (int) ($activeSubscription['pause_count'] ?? 0)),
            ];
        }

        // Fetch payment history
        $myPayments = $this->apiData($paymentApi->my(), function () {
            return [];
        });

        $paymentHistory = [];
        $totalSpent = 0;
        if (!empty($myPayments) && is_array($myPayments)) {
            foreach ($myPayments as $pm) {
                $sub = null;
                foreach ($mySubscriptions as $s) {
                    if (($s['id'] ?? null) === ($pm['subscription_id'] ?? null)) {
                        $sub = $s;
                        break;
                    }
                }
                $plan = $planDetails;
                if ($sub && !empty($sub['plan_id']) && $sub['plan_id'] !== ($activeSubscription['plan_id'] ?? null)) {
                    $plan = $this->apiData($planApi->show($sub['plan_id']), function () use ($plan) {
                        return $plan;
                    });
                }
                $pmStatus = $pm['status'] ?? 'pending';
                $amount = (float) ($pm['amount'] ?? 0);
                if ($pmStatus === 'paid' || $pmStatus === 'completed') {
                    $totalSpent += $amount;
                }
                $paymentHistory[] = [
                    'id' => $pm['id'] ?? null,
                    'plan_name' => $plan['name_en'] ?? 'Plan',
                    'amount' => $amount,
                    'currency' => $pm['currency'] ?? 'SAR',
                    'status' => $pmStatus,
                    'provider' => $pm['provider'] ?? 'moyasar',
                    'provider_payment_id' => $pm['provider_payment_id'] ?? null,
                    'paid_at' => !empty($pm['paid_at']) ? date('M d, Y H:i', strtotime($pm['paid_at'])) : null,
                    'created_at' => !empty($pm['created_at']) ? date('M d, Y H:i', strtotime($pm['created_at'])) : 'N/A',
                ];
            }
        }

        // Sort payment history by date desc
        usort($paymentHistory, function ($a, $b) {
            return strcmp($b['created_at'], $a['created_at']);
        });

        return view('user.settings', compact('profile', 'subscriptionInfo', 'paymentHistory', 'totalSpent'));
    }
}
