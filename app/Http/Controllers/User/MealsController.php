<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class MealsController extends Controller
{
    public function index(Request $request)
    {
        $token = session('api_token');

        if (!$token) {
            return redirect()
                ->route('login')
                ->with('error', __('Please log in again.'));
        }

        [$weekStart, $weekEnd] = $this->resolveWeek($request->query('week'));
        $baseUrl = rtrim((string) config('api.base_url'), '/');

        try {
            $subscriptionResponse = $this->apiGet(
                $baseUrl . '/subscriptions/my/current-details',
                $token
            );

            if ($subscriptionResponse->status() === 401) {
                return $this->logoutAndRedirect();
            }

            $subscriptionPayload = $subscriptionResponse->successful()
                ? $this->safeArray($subscriptionResponse->json())
                : [];

            $subscription = $this->extractCurrentSubscription(
                $subscriptionPayload
            );

            /*
             * Orders are the source of truth for the customer's actual dated
             * meals. They contain delivery_date, category, nutrition and status.
             */
            $ordersResponse = $this->apiGet(
                $baseUrl . '/orders/my',
                $token
            );

            if ($ordersResponse->status() === 401) {
                return $this->logoutAndRedirect();
            }

            if ($ordersResponse->failed()) {
                $message = $ordersResponse->json('detail')
                    ?? $ordersResponse->json('message')
                    ?? __('Unable to load your meal schedule.');

                return view(
                    'user.meals',
                    $this->emptyPageData($weekStart, $weekEnd)
                )->with('error', $message);
            }

            $ordersPayload = $this->safeArray($ordersResponse->json());
            $orders = $this->extractOrders($ordersPayload);

            /*
             * Meal selections are only a fallback for future menu choices that
             * have not yet generated an Order.
             */
            $selectionQuery = [];

            if (!empty($subscription['id'])) {
                $selectionQuery['subscription_id'] = $subscription['id'];
            }

            $selectionsResponse = $this->apiGet(
                $baseUrl . '/meal-selections/my',
                $token,
                $selectionQuery
            );

            $selectionsPayload = $selectionsResponse->successful()
                ? $this->safeArray($selectionsResponse->json())
                : [];

            $selections = $this->extractList(
                $selectionsPayload,
                [
                    'meal_selections',
                    'selections',
                    'items',
                    'results',
                    'data',
                ]
            );

            $weekMeals = $this->buildWeekMealsFromOrders(
                $orders,
                $weekStart,
                $weekEnd
            );

            /*
             * Only use selections when there are no order-backed meals in the
             * requested week.
             */
            if (collect($weekMeals)->sum('mealCount') === 0 && !empty($selections)) {
                $scheduleStart = $this->scheduleStartDate(
                    $subscription,
                    $weekStart
                );

                $weekMeals = $this->buildWeekMealsFromSelections(
                    $selections,
                    $weekStart,
                    $weekEnd,
                    $scheduleStart
                );
            }

            $todayKey = now()->toDateString();
            $today = collect($weekMeals)->firstWhere('date', $todayKey);

            $todayMealsByCategory = $today['categories'] ?? [];
            $todayMeals = collect($todayMealsByCategory)
                ->flatMap(fn (array $category) => $category['meals'] ?? [])
                ->values()
                ->all();

            $stats = $this->buildStats(
                $weekMeals,
                $todayMeals,
                $subscription,
                $orders
            );

            return view('user.meals', [
                'weekMeals' => $weekMeals,
                'todayMeals' => $todayMeals,
                'todayMealsByCategory' => $todayMealsByCategory,
                'stats' => $stats,
                'hasActiveSubscription' => !empty($subscription),
                'activeSubscription' => $subscription,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return view(
                'user.meals',
                $this->emptyPageData($weekStart, $weekEnd)
            )->with(
                'error',
                __('Unable to connect to the meals service.')
            );
        }
    }

    private function apiGet(
        string $url,
        string $token,
        array $query = []
    ) {
        return Http::acceptJson()
            ->withToken($token)
            ->timeout(20)
            ->get($url, $query);
    }

    private function safeArray(mixed $value): array
    {
        return is_array($value) ? $value : [];
    }

    private function logoutAndRedirect()
    {
        session()->forget([
            'api_token',
            'api_user',
            'email_verified',
        ]);

        return redirect()
            ->route('login')
            ->with(
                'error',
                __('Your session has expired. Please log in again.')
            );
    }

    private function resolveWeek(?string $direction): array
    {
        $weekStart = now()->startOfWeek(Carbon::MONDAY);

        if ($direction === 'prev') {
            $weekStart->subWeek();
        } elseif ($direction === 'next') {
            $weekStart->addWeek();
        }

        return [
            $weekStart->copy(),
            $weekStart->copy()->endOfWeek(Carbon::SUNDAY),
        ];
    }

    private function extractCurrentSubscription(array $payload): ?array
    {
        $subscription = data_get($payload, 'subscription')
            ?? data_get($payload, 'data.subscription');

        if (!is_array($subscription) || empty($subscription)) {
            return null;
        }

        $plan = data_get($payload, 'plan')
            ?? data_get($payload, 'data.plan')
            ?? [];

        if (!is_array($plan)) {
            $plan = [];
        }

        return [
            ...$subscription,
            'plan' => $plan,
            'plan_name' => $plan['name_en']
                ?? $plan['name']
                ?? $subscription['plan_name']
                ?? __('Active Meal Plan'),
            'total_meals' => $plan['total_meals']
                ?? $subscription['total_meals']
                ?? null,
            'calorie_target' => $plan['daily_calories']
                ?? $plan['calorie_target']
                ?? null,
            'protein_target' => $plan['daily_protein_g']
                ?? $plan['protein_target']
                ?? null,
            'carbs_target' => $plan['daily_carbs_g']
                ?? $plan['carbs_target']
                ?? null,
            'fat_target' => $plan['daily_fat_g']
                ?? $plan['fat_target']
                ?? null,
        ];
    }

    private function extractOrders(array $payload): array
    {
        if (array_is_list($payload)) {
            return array_values(
                array_filter($payload, fn ($item) => is_array($item))
            );
        }

        $candidates = [
            $payload['orders'] ?? null,
            $payload['items'] ?? null,
            $payload['results'] ?? null,
            $payload['data'] ?? null,
            data_get($payload, 'data.orders'),
            data_get($payload, 'data.items'),
            data_get($payload, 'data.results'),
        ];

        foreach ($candidates as $candidate) {
            if (is_array($candidate) && array_is_list($candidate)) {
                return array_values(
                    array_filter($candidate, fn ($item) => is_array($item))
                );
            }
        }

        return [];
    }

    private function extractList(array $payload, array $keys): array
    {
        if (array_is_list($payload)) {
            return array_values(
                array_filter($payload, fn ($item) => is_array($item))
            );
        }

        foreach ($keys as $key) {
            $candidate = data_get($payload, $key);

            if (is_array($candidate) && array_is_list($candidate)) {
                return array_values(
                    array_filter($candidate, fn ($item) => is_array($item))
                );
            }
        }

        return [];
    }

    private function buildWeekMealsFromOrders(
        array $orders,
        Carbon $weekStart,
        Carbon $weekEnd
    ): array {
        $days = [];

        foreach (CarbonPeriod::create($weekStart, $weekEnd) as $date) {
            $dateKey = $date->toDateString();

            $dayOrders = collect($orders)->filter(
                fn (array $order) => $this->orderDate($order) === $dateKey
            );

            $mealRows = $dayOrders->flatMap(function (array $order) {
                $items = $this->orderItems($order);
                $orderStatus = $this->orderStatus($order);
                $deliveryStatus = $this->deliveryStatus($order);

                return collect($items)->map(function (array $item) use (
                    $order,
                    $orderStatus,
                    $deliveryStatus
                ) {
                    return [
                        'category' => $item['category_name']
                            ?? $item['meal_time']
                            ?? $order['category_name']
                            ?? $order['meal_category_name']
                            ?? 'Meal',
                        'meal' => $this->normalizeOrderItem(
                            $item,
                            $orderStatus,
                            $deliveryStatus
                        ),
                    ];
                });
            });

            $categories = $mealRows
                ->groupBy(fn (array $row) => Str::title((string) $row['category']))
                ->map(function ($rows, string $categoryName) {
                    return [
                        'name' => $categoryName,
                        'icon' => $this->categoryIcon($categoryName),
                        'meals' => $rows
                            ->pluck('meal')
                            ->values()
                            ->all(),
                    ];
                })
                ->values()
                ->all();

            $flatMeals = collect($categories)
                ->flatMap(fn (array $category) => $category['meals'] ?? []);

            $days[] = [
                'day' => $date->format('D'),
                'date' => $dateKey,
                'mealCount' => (int) $flatMeals->sum(
                    fn (array $meal) => $meal['quantity'] ?? 1
                ),
                'calories' => (float) $flatMeals->sum(
                    fn (array $meal) =>
                        ($meal['calories'] ?? 0)
                        * ($meal['quantity'] ?? 1)
                ),
                'categories' => $categories,
            ];
        }

        return $days;
    }

    private function orderDate(array $order): ?string
    {
        $raw = $order['delivery_date']
            ?? $order['date']
            ?? data_get($order, 'order.delivery_date')
            ?? null;

        if (!$raw) {
            return null;
        }

        try {
            return Carbon::parse($raw)->toDateString();
        } catch (Throwable) {
            return null;
        }
    }

    private function orderItems(array $order): array
    {
        $items = $order['items']
            ?? data_get($order, 'order.items')
            ?? [];

        if (is_array($items) && isset($items['items']) && is_array($items['items'])) {
            $items = $items['items'];
        }

        return is_array($items)
            ? array_values(
                array_filter($items, fn ($item) => is_array($item))
            )
            : [];
    }

    private function normalizeOrderItem(
        array $item,
        string $orderStatus,
        ?string $deliveryStatus
    ): array {
        return [
            'id' => $item['meal_id'] ?? $item['id'] ?? null,
            'name' => $item['meal_name']
                ?? $item['name_en']
                ?? $item['name']
                ?? __('Assigned meal'),
            'name_ar' => $item['meal_name_ar']
                ?? $item['name_ar']
                ?? null,
            'description' => $item['description']
                ?? $item['description_en']
                ?? null,
            'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
            'calories' => (float) ($item['calories'] ?? 0),
            'protein' => (float) (
                $item['protein_g']
                ?? $item['protein']
                ?? 0
            ),
            'carbs' => (float) (
                $item['carbs_g']
                ?? $item['carbs']
                ?? 0
            ),
            'fat' => (float) (
                $item['fat_g']
                ?? $item['fat']
                ?? 0
            ),
            'image_url' => $item['image_url'] ?? null,
            'is_skipped' => false,
            'skip_reason' => null,
            'order_status' => $orderStatus,
            'delivery_status' => $deliveryStatus,
        ];
    }

    private function orderStatus(array $order): string
    {
        return Str::lower((string) (
            $order['status']
            ?? data_get($order, 'order.status')
            ?? 'scheduled'
        ));
    }

    private function deliveryStatus(array $order): ?string
    {
        $status = data_get($order, 'delivery.status')
            ?? $order['delivery_status']
            ?? null;

        return $status
            ? Str::lower((string) $status)
            : null;
    }

    private function scheduleStartDate(
        ?array $subscription,
        Carbon $fallback
    ): Carbon {
        $raw = $subscription['start_date'] ?? null;

        if ($raw) {
            try {
                return Carbon::parse($raw)->startOfDay();
            } catch (Throwable) {
                // Continue with fallback.
            }
        }

        return $fallback->copy()->startOfDay();
    }

    private function selectionDate(
        array $selection,
        Carbon $scheduleStart
    ): ?string {
        $explicit = $selection['delivery_date']
            ?? $selection['scheduled_date']
            ?? $selection['date']
            ?? null;

        if ($explicit) {
            try {
                return Carbon::parse($explicit)->toDateString();
            } catch (Throwable) {
                return null;
            }
        }

        $dayNumber = (int) ($selection['day_number'] ?? 0);

        if ($dayNumber < 1) {
            return null;
        }

        return $scheduleStart
            ->copy()
            ->addDays($dayNumber - 1)
            ->toDateString();
    }

    private function buildWeekMealsFromSelections(
        array $selections,
        Carbon $weekStart,
        Carbon $weekEnd,
        Carbon $scheduleStart
    ): array {
        $days = [];

        foreach (CarbonPeriod::create($weekStart, $weekEnd) as $date) {
            $dateKey = $date->toDateString();

            $daySelections = collect($selections)
                ->filter(function (array $selection) use (
                    $dateKey,
                    $scheduleStart
                ) {
                    return $this->selectionDate(
                        $selection,
                        $scheduleStart
                    ) === $dateKey;
                });

            $categories = $daySelections
                ->groupBy(
                    fn (array $selection) => Str::title(
                        (string) ($selection['meal_time'] ?? 'Meal')
                    )
                )
                ->map(function ($group, string $categoryName) {
                    return [
                        'name' => $categoryName,
                        'icon' => $this->categoryIcon($categoryName),
                        'meals' => $group
                            ->map(fn (array $selection) =>
                                $this->normalizeSelection($selection)
                            )
                            ->values()
                            ->all(),
                    ];
                })
                ->values()
                ->all();

            $flatMeals = collect($categories)
                ->flatMap(fn (array $category) => $category['meals'] ?? []);

            $days[] = [
                'day' => $date->format('D'),
                'date' => $dateKey,
                'mealCount' => (int) $flatMeals->sum(
                    fn (array $meal) => $meal['quantity'] ?? 1
                ),
                'calories' => (float) $flatMeals->sum(
                    fn (array $meal) =>
                        ($meal['calories'] ?? 0)
                        * ($meal['quantity'] ?? 1)
                ),
                'categories' => $categories,
            ];
        }

        return $days;
    }

    private function normalizeSelection(array $selection): array
    {
        $meal = is_array($selection['meal'] ?? null)
            ? $selection['meal']
            : [];

        return [
            'id' => $meal['id']
                ?? $selection['meal_id']
                ?? null,
            'name' => $meal['name_en']
                ?? $meal['name']
                ?? __('Assigned meal'),
            'name_ar' => $meal['name_ar'] ?? null,
            'description' => $meal['description_en']
                ?? $meal['description']
                ?? null,
            'quantity' => max(1, (int) ($selection['quantity'] ?? 1)),
            'calories' => (float) ($meal['calories'] ?? 0),
            'protein' => (float) ($meal['protein_g'] ?? 0),
            'carbs' => (float) ($meal['carbs_g'] ?? 0),
            'fat' => (float) ($meal['fat_g'] ?? 0),
            'image_url' => $meal['image_url'] ?? null,
            'is_skipped' => (bool) ($selection['is_skipped'] ?? false),
            'skip_reason' => $selection['skip_reason'] ?? null,
            'order_status' => 'scheduled',
            'delivery_status' => null,
        ];
    }

    private function categoryIcon(string $name): string
    {
        $name = Str::lower($name);

        return match (true) {
            Str::contains($name, 'breakfast') => 'sunrise',
            Str::contains($name, 'lunch') => 'sun',
            Str::contains($name, 'dinner') => 'moon',
            Str::contains($name, 'snack') => 'cookie',
            default => 'meal',
        };
    }

    private function buildStats(
        array $weekMeals,
        array $todayMeals,
        ?array $subscription,
        array $orders
    ): array {
        $todayCalories = collect($todayMeals)->sum(
            fn (array $meal) =>
                ($meal['calories'] ?? 0)
                * ($meal['quantity'] ?? 1)
        );

        $todayProtein = collect($todayMeals)->sum(
            fn (array $meal) =>
                ($meal['protein'] ?? 0)
                * ($meal['quantity'] ?? 1)
        );

        $todayCarbs = collect($todayMeals)->sum(
            fn (array $meal) =>
                ($meal['carbs'] ?? 0)
                * ($meal['quantity'] ?? 1)
        );

        $todayFat = collect($todayMeals)->sum(
            fn (array $meal) =>
                ($meal['fat'] ?? 0)
                * ($meal['quantity'] ?? 1)
        );

        $totalPlan = (int) (
            data_get($subscription, 'total_meals') ?? 0
        );

        $consumed = collect($orders)
            ->filter(function (array $order) {
                return $this->orderStatus($order) === 'delivered'
                    || $this->deliveryStatus($order) === 'delivered';
            })
            ->sum(function (array $order) {
                $items = $this->orderItems($order);

                return empty($items)
                    ? 1
                    : collect($items)->sum(
                        fn (array $item) =>
                            max(1, (int) ($item['quantity'] ?? 1))
                    );
            });

        $remaining = max(0, $totalPlan - $consumed);
        $endDate = data_get($subscription, 'end_date');

        $daysRemaining = 0;

        if ($endDate) {
            try {
                $daysRemaining = max(
                    0,
                    now()->startOfDay()->diffInDays(
                        Carbon::parse($endDate)->startOfDay(),
                        false
                    )
                );
            } catch (Throwable) {
                $daysRemaining = 0;
            }
        }

        return [
            'mealsConsumed' => (int) $consumed,
            'totalPlan' => $totalPlan,
            'planProgress' => $totalPlan > 0
                ? min(100, round(($consumed / $totalPlan) * 100))
                : 0,
            'todayCalories' => $todayCalories,
            'calorieTarget' => (float) (
                data_get($subscription, 'calorie_target') ?? 0
            ),
            'todayProtein' => $todayProtein,
            'proteinTarget' => (float) (
                data_get($subscription, 'protein_target') ?? 0
            ),
            'todayCarbs' => $todayCarbs,
            'carbsTarget' => (float) (
                data_get($subscription, 'carbs_target') ?? 0
            ),
            'todayFat' => $todayFat,
            'fatTarget' => (float) (
                data_get($subscription, 'fat_target') ?? 0
            ),
            'remaining' => $remaining,
            'daysRemaining' => $daysRemaining,
            'planRenewal' => $endDate
                ? Carbon::parse($endDate)->format('M d, Y')
                : '-',
            'avgCalories' => collect($weekMeals)
                ->where('calories', '>', 0)
                ->avg('calories') ?? 0,
        ];
    }

    private function emptyPageData(
        Carbon $start,
        Carbon $end
    ): array {
        return [
            'weekMeals' => $this->buildWeekMealsFromOrders(
                [],
                $start,
                $end
            ),
            'todayMeals' => [],
            'todayMealsByCategory' => [],
            'stats' => [
                'mealsConsumed' => 0,
                'totalPlan' => 0,
                'planProgress' => 0,
                'todayCalories' => 0,
                'calorieTarget' => 0,
                'todayProtein' => 0,
                'proteinTarget' => 0,
                'todayCarbs' => 0,
                'carbsTarget' => 0,
                'todayFat' => 0,
                'fatTarget' => 0,
                'remaining' => 0,
                'daysRemaining' => 0,
                'planRenewal' => '-',
                'avgCalories' => 0,
            ],
            'hasActiveSubscription' => false,
            'activeSubscription' => null,
        ];
    }
}
