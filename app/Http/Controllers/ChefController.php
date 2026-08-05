<?php

namespace App\Http\Controllers;

use App\Services\Api\AuthApiService;
use App\Services\Api\ChefApiService;
use App\Services\Api\HasApiData;
use App\Services\Api\MealApiService;
use App\Services\Api\NotificationApiService;
use Illuminate\Http\Request;

class ChefController extends Controller
{
    use HasApiData;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authApi = app(AuthApiService::class);
            if (!$authApi->check() || !$authApi->hasRole('chef')) {
                abort(403, 'Access denied. Chefs only.');
            }
            return $next($request);
        });
    }

    public function dashboard(
        ChefApiService $chefApi,
        NotificationApiService $notificationApi,
        MealApiService $mealApi
    ) {
        /*
         * The flat "today" endpoint is the source of truth here because every
         * Order already contains its delivery date, category and meal items.
         * We group those real order items for the kitchen instead of displaying
         * the complete meal catalogue.
         */
        $ordersResponse = $chefApi->ordersToday();

        if (
            isset($ordersResponse['success'])
            && $ordersResponse['success'] === false
        ) {
            $ordersResponse = $chefApi->orders([
                'limit' => 200,
                'date' => date('Y-m-d'),
            ]);
        }

        $ordersData = $this->extractApiList(
            is_array($ordersResponse) ? $ordersResponse : [],
            ['orders', 'items', 'results', 'data']
        );

        $today = date('Y-m-d');
        $formattedOrders = [];

        foreach ($ordersData as $order) {
            if (!is_array($order)) {
                continue;
            }

            $deliveryDate = $order['delivery_date']
                ?? $order['date']
                ?? null;

            if ($deliveryDate) {
                try {
                    if (date('Y-m-d', strtotime($deliveryDate)) !== $today) {
                        continue;
                    }
                } catch (\Throwable) {
                    continue;
                }
            }

            try {
                $formattedOrders[] = $this->formatOrder($order);
            } catch (\Throwable $exception) {
                \Log::error('Chef order formatting failed', [
                    'order_id' => $order['id'] ?? null,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        /*
         * Build a meal lookup so order snapshots can be enriched with current
         * ingredients and allergens when those fields are missing.
         */
        $mealsResponse = $mealApi->list(['limit' => 500]);
        $mealsData = $this->extractApiList(
            is_array($mealsResponse) ? $mealsResponse : [],
            ['meals', 'items', 'results', 'data']
        );

        $mealLookup = [];

        foreach ($mealsData as $meal) {
            if (!is_array($meal) || empty($meal['id'])) {
                continue;
            }

            $mealLookup[(int) $meal['id']] = [
                'id' => (int) $meal['id'],
                'name' => $meal['name_en']
                    ?? $meal['name']
                    ?? $meal['name_ar']
                    ?? __('Unknown meal'),
                'image_url' => $meal['image_url'] ?? null,
                'ingredients' => is_array($meal['ingredients'] ?? null)
                    ? $meal['ingredients']
                    : [],
                'allergens' => is_array($meal['allergens'] ?? null)
                    ? $meal['allergens']
                    : [],
                'calories' => (float) ($meal['calories'] ?? 0),
                'protein_g' => (float) ($meal['protein_g'] ?? 0),
                'carbs_g' => (float) ($meal['carbs_g'] ?? 0),
                'fat_g' => (float) ($meal['fat_g'] ?? 0),
            ];
        }

        $iconForCategory = static function (string $name): string {
            $name = strtolower($name);

            return match (true) {
                str_contains($name, 'breakfast') => 'sunrise',
                str_contains($name, 'lunch') => 'sun',
                str_contains($name, 'dinner'),
                str_contains($name, 'supper') => 'moon',
                str_contains($name, 'snack') => 'cookie',
                default => 'dots',
            };
        };

        $categoryRank = static function (string $name): int {
            $name = strtolower($name);

            return match (true) {
                str_contains($name, 'breakfast') => 0,
                str_contains($name, 'lunch') => 1,
                str_contains($name, 'dinner'),
                str_contains($name, 'supper') => 2,
                str_contains($name, 'snack') => 3,
                default => 4,
            };
        };

        $categoryMap = [];
        $categorizedOrders = [];
        $scheduleByTab = [];

        foreach ($formattedOrders as $order) {
            $itemsByCategory = [];

            foreach ($order['items'] ?? [] as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $categoryId = (int) (
                    $item['category_id']
                    ?? $item['meal_category_id']
                    ?? $order['primary_category_id']
                    ?? 0
                );

                $categoryName = $item['category_name']
                    ?? $item['meal_category_name']
                    ?? $order['primary_category_name']
                    ?? __('Other');

                /*
                 * Use a stable negative synthetic ID when an order item has a
                 * category name but no category ID.
                 */
                if ($categoryId === 0) {
                    $categoryId = -abs((int) crc32(strtolower($categoryName)));
                }

                $mealId = (int) (
                    $item['meal_id']
                    ?? $item['id']
                    ?? 0
                );

                $mealInfo = $mealLookup[$mealId] ?? [];

                $normalizedItem = [
                    ...$item,
                    'meal_id' => $mealId,
                    'meal_name' => $item['meal_name']
                        ?? $item['name_en']
                        ?? $item['name']
                        ?? $mealInfo['name']
                        ?? __('Unknown meal'),
                    'category_id' => $categoryId,
                    'category_name' => $categoryName,
                    'quantity' => max(1, (int) ($item['quantity'] ?? 1)),
                    'image_url' => $item['image_url']
                        ?? $mealInfo['image_url']
                        ?? null,
                    'ingredients' => is_array($item['ingredients'] ?? null)
                        && !empty($item['ingredients'])
                            ? $item['ingredients']
                            : ($mealInfo['ingredients'] ?? []),
                    'allergens' => is_array($item['allergens'] ?? null)
                        && !empty($item['allergens'])
                            ? $item['allergens']
                            : ($mealInfo['allergens'] ?? []),
                    'calories' => (float) (
                        $item['calories']
                        ?? $mealInfo['calories']
                        ?? 0
                    ),
                    'protein_g' => (float) (
                        $item['protein_g']
                        ?? $item['protein']
                        ?? $mealInfo['protein_g']
                        ?? 0
                    ),
                    'carbs_g' => (float) (
                        $item['carbs_g']
                        ?? $item['carbs']
                        ?? $mealInfo['carbs_g']
                        ?? 0
                    ),
                    'fat_g' => (float) (
                        $item['fat_g']
                        ?? $item['fat']
                        ?? $mealInfo['fat_g']
                        ?? 0
                    ),
                ];

                $itemsByCategory[$categoryId]['name'] = $categoryName;
                $itemsByCategory[$categoryId]['items'][] = $normalizedItem;
            }

            foreach ($itemsByCategory as $categoryId => $categoryData) {
                $categoryName = $categoryData['name'];
                $categoryItems = $categoryData['items'];

                $categoryMap[$categoryId] = [
                    'id' => $categoryId,
                    'name' => $categoryName,
                    'icon' => $iconForCategory($categoryName),
                ];

                $categoryOrder = $order;
                $categoryOrder['items'] = $categoryItems;
                $categoryOrder['primary_category_id'] = $categoryId;
                $categoryOrder['primary_category_name'] = $categoryName;
                $categoryOrder['meal_count'] = count($categoryItems);
                $categoryOrder['total_quantity'] = array_sum(
                    array_map(
                        fn (array $item) => $item['quantity'],
                        $categoryItems
                    )
                );

                $categorizedOrders[$categoryId][] = $categoryOrder;
            }
        }

        uasort(
            $categoryMap,
            fn (array $left, array $right) =>
                $categoryRank($left['name'])
                <=> $categoryRank($right['name'])
        );

        $categories = [];

        foreach ($categoryMap as $categoryId => $category) {
            $orders = $categorizedOrders[$categoryId] ?? [];
            $mealAggregation = [];
            $statusTotals = [
                'pending' => 0,
                'preparing' => 0,
                'ready' => 0,
                'served' => 0,
            ];

            foreach ($orders as $order) {
                $orderStatus = strtolower(
                    (string) ($order['status'] ?? 'pending')
                );

                $itemStatus = match ($orderStatus) {
                    'preparing' => 'preparing',
                    'ready_for_delivery' => 'ready',
                    'out_for_delivery', 'delivered' => 'served',
                    default => 'pending',
                };

                foreach ($order['items'] ?? [] as $item) {
                    $mealId = (int) ($item['meal_id'] ?? 0);
                    $mealName = $item['meal_name'] ?? __('Unknown meal');
                    $quantity = max(1, (int) ($item['quantity'] ?? 1));
                    $key = $mealId > 0
                        ? 'id:' . $mealId
                        : 'name:' . strtolower($mealName);

                    if (!isset($mealAggregation[$key])) {
                        $mealAggregation[$key] = [
                            'meal_id' => $mealId,
                            'meal_name' => $mealName,
                            'image_url' => $item['image_url'] ?? null,
                            'ingredients' => $item['ingredients'] ?? [],
                            'allergens' => $item['allergens'] ?? [],
                            'calories' => (float) ($item['calories'] ?? 0),
                            'protein_g' => (float) ($item['protein_g'] ?? 0),
                            'carbs_g' => (float) ($item['carbs_g'] ?? 0),
                            'fat_g' => (float) ($item['fat_g'] ?? 0),
                            'total_required' => 0,
                            'pending' => 0,
                            'preparing' => 0,
                            'ready' => 0,
                            'served' => 0,
                            'customers' => [],
                            'order_ids' => [],
                        ];
                    }

                    $mealAggregation[$key]['total_required'] += $quantity;
                    $mealAggregation[$key][$itemStatus] += $quantity;
                    $mealAggregation[$key]['order_ids'][] = $order['id'];

                    $mealAggregation[$key]['customers'][] = [
                        'order_id' => $order['id'],
                        'order_number' => $order['order_number'],
                        'customer_name' => $order['customer'],
                        'address' => $order['delivery_address'],
                        'quantity' => $quantity,
                        'item_status' => $itemStatus,
                    ];

                    $statusTotals[$itemStatus] += $quantity;
                }
            }

            $productionMeals = array_values($mealAggregation);

            usort(
                $productionMeals,
                fn (array $left, array $right) =>
                    $right['total_required']
                    <=> $left['total_required']
            );

            $totalQuantity = array_sum(
                array_column($productionMeals, 'total_required')
            );

            $categories[] = [
                ...$category,
                'count' => count($orders),
                'total_quantity' => $totalQuantity,
            ];

            $scheduleByTab[$categoryId] = [
                'stats' => [
                    ...$statusTotals,
                    'total_items' => $totalQuantity,
                ],
                'production' => [
                    'meals' => $productionMeals,
                    'total_required' => $totalQuantity,
                ],
                'kitchen_queue' => [
                    'meals' => $productionMeals,
                    'totals' => $statusTotals,
                ],
            ];
        }

        $tabSummaries = [];

        foreach ($categories as $category) {
            $categoryId = $category['id'];
            $categoryOrders = $categorizedOrders[$categoryId] ?? [];
            $schedule = $scheduleByTab[$categoryId] ?? [];

            $tabSummaries[$categoryId] = [
                'customers' => count(
                    array_unique(
                        array_map(
                            fn (array $order) => $order['customer'],
                            $categoryOrders
                        )
                    )
                ),
                'total_meals' => $category['total_quantity'],
                'pending' => data_get($schedule, 'stats.pending', 0),
                'preparing' => data_get($schedule, 'stats.preparing', 0),
                'ready' => data_get($schedule, 'stats.ready', 0),
                'served' => data_get($schedule, 'stats.served', 0),
                'dishes' => data_get(
                    $schedule,
                    'production.meals',
                    []
                ),
            ];
        }

        $stats = [
            'total_today' => count($formattedOrders),
            'pending' => count(
                array_filter(
                    $formattedOrders,
                    fn (array $order) => in_array(
                        $order['status'],
                        ['scheduled', 'pending', 'confirmed'],
                        true
                    )
                )
            ),
            'preparing' => count(
                array_filter(
                    $formattedOrders,
                    fn (array $order) =>
                        $order['status'] === 'preparing'
                )
            ),
            'ready' => count(
                array_filter(
                    $formattedOrders,
                    fn (array $order) =>
                        $order['status'] === 'ready_for_delivery'
                )
            ),
            'completed' => count(
                array_filter(
                    $formattedOrders,
                    fn (array $order) => in_array(
                        $order['status'],
                        ['out_for_delivery', 'delivered'],
                        true
                    )
                )
            ),
            'cancelled' => count(
                array_filter(
                    $formattedOrders,
                    fn (array $order) =>
                        $order['status'] === 'cancelled'
                )
            ),
        ];

        $notificationsData = $this->apiData(
            $notificationApi->my([
                'limit' => 5,
                'is_read' => false,
            ]),
            fn () => []
        );

        $notifications = is_array($notificationsData)
            ? $notificationsData
            : [];

        return view('chef.dashboard', compact(
            'categorizedOrders',
            'categories',
            'stats',
            'notifications',
            'tabSummaries',
            'scheduleByTab',
            'today'
        ));
    }

    private function extractApiList(
        array $payload,
        array $keys
    ): array {
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

        foreach ($keys as $outerKey) {
            $outer = data_get($payload, $outerKey);

            if (!is_array($outer)) {
                continue;
            }

            foreach ($keys as $innerKey) {
                $candidate = data_get($outer, $innerKey);

                if (is_array($candidate) && array_is_list($candidate)) {
                    return array_values(
                        array_filter(
                            $candidate,
                            fn ($item) => is_array($item)
                        )
                    );
                }
            }
        }

        return [];
    }

    /**
     * Aggregate dish names + quantities across a set of formatted orders,
     * powering the "quantities needed" card on the kitchen shift screen.
     */
    private function aggregateDishes(array $orders): array
    {
        $totals = [];
        foreach ($orders as $order) {
            foreach ($order['items'] ?? [] as $item) {
                $name = $item['meal_name'] ?? ($item['name'] ?? ($item['title'] ?? null));
                if (!$name) {
                    continue;
                }
                $qty = (int) ($item['quantity'] ?? 1);
                if (!isset($totals[$name])) {
                    $totals[$name] = 0;
                }
                $totals[$name] += $qty;
            }
        }

        $dishes = [];
        foreach ($totals as $name => $qty) {
            $dishes[] = ['name' => $name, 'quantity' => $qty];
        }

        usort($dishes, fn ($a, $b) => $b['quantity'] <=> $a['quantity']);

        return $dishes;
    }

    public function startPreparing(Request $request, int $orderId, ChefApiService $chefApi)
    {
        $response = $chefApi->startPreparing($orderId);
        $success = isset($response['order']) || (isset($response['success']) && $response['success'] === true);
        $message = $response['message'] ?? ($response['detail'] ?? ($success ? __('Preparation started.') : __('Failed to start preparation.')));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        return redirect()->route('chef.dashboard')->with($success ? 'status' : 'error', $message);
    }

    public function markReady(Request $request, int $orderId, ChefApiService $chefApi)
    {
        $response = $chefApi->markReady($orderId);
        $success = isset($response['order']) || (isset($response['success']) && $response['success'] === true);
        $message = $response['message'] ?? ($response['detail'] ?? ($success ? __('Order marked as ready.') : __('Failed to mark as ready.')));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        return redirect()->route('chef.dashboard')->with($success ? 'status' : 'error', $message);
    }

    public function drivers(ChefApiService $chefApi)
    {
        $drivers = $this->apiData($chefApi->drivers(true), fn () => []);
        return response()->json(['success' => true, 'drivers' => $drivers]);
    }

    public function assignDriver(Request $request, int $orderId, ChefApiService $chefApi)
    {
        $validated = $request->validate([
            'driver_id' => ['required', 'integer', 'min:1'],
            'scheduled_at' => ['nullable', 'string'],
        ]);

        $response = $chefApi->assignDriver($orderId, $validated['driver_id'], $validated['scheduled_at'] ?? null);
        $success = isset($response['delivery']) || isset($response['id']) || (isset($response['success']) && $response['success'] === true);
        $message = $response['message'] ?? ($response['detail'] ?? ($success ? __('Driver assigned successfully.') : __('Failed to assign driver.')));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        return redirect()->route('chef.dashboard')->with($success ? 'status' : 'error', $message);
    }

    public function bulkAssignDriver(Request $request, ChefApiService $chefApi)
    {
        $validated = $request->validate([
            'driver_id' => ['required', 'integer', 'min:1'],
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['integer', 'min:1'],
            'scheduled_at' => ['nullable', 'string'],
        ]);

        $response = $chefApi->bulkAssignDriver($validated['driver_id'], $validated['order_ids'], $validated['scheduled_at'] ?? null);
        $success = isset($response['assigned']) || (isset($response['success']) && $response['success'] === true);
        $message = $response['message'] ?? ($success ? __('Drivers assigned successfully.') : __('Failed to assign drivers.'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        return redirect()->route('chef.dashboard')->with($success ? 'status' : 'error', $message);
    }

    /**
     * Transfer all pending items in a schedule (category) to the kitchen
     * by marking all pending orders in that category as "preparing".
     */
    public function transferSchedule(
        Request $request,
        ChefApiService $chefApi
    ) {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'min:1'],
            'date' => ['nullable', 'date'],
        ]);

        $targetCategoryId = (int) $validated['category_id'];

        /*
         * Use the same flat today endpoint that powers the dashboard.
         *
         * The old code expected groups[].categories[].orders[], while the
         * current backend grouped response is groups[].meal_category_id with
         * groups[].orders[]. That mismatch made Laravel skip every order.
         */
        $response = $chefApi->ordersToday();

        $orders = $this->apiData(
            $response,
            fn () => []
        );

        if (isset($orders['data']) && is_array($orders['data'])) {
            $orders = $orders['data'];
        } elseif (isset($orders['items']) && is_array($orders['items'])) {
            $orders = $orders['items'];
        } elseif (isset($orders['orders']) && is_array($orders['orders'])) {
            $orders = $orders['orders'];
        }

        $orders = is_array($orders) ? array_values($orders) : [];
        $orderIds = [];

        foreach ($orders as $order) {
            if (!is_array($order)) {
                continue;
            }

            $categoryId = (int) (
                $order['meal_category_id']
                ?? $order['category_id']
                ?? $order['category']['id']
                ?? 0
            );

            $items = is_array($order['items'] ?? null)
                ? $order['items']
                : [];

            if ($categoryId <= 0) {
                foreach ($items as $item) {
                    $candidate = (int) (
                        $item['meal_category_id']
                        ?? $item['category_id']
                        ?? 0
                    );

                    if ($candidate > 0) {
                        $categoryId = $candidate;
                        break;
                    }
                }
            }

            if ($categoryId !== $targetCategoryId) {
                continue;
            }

            $status = strtolower(
                (string) ($order['status'] ?? 'pending')
            );

            if (
                !in_array(
                    $status,
                    ['pending', 'confirmed', 'scheduled'],
                    true
                )
            ) {
                continue;
            }

            $orderId = (int) (
                $order['id']
                ?? $order['order_id']
                ?? 0
            );

            if ($orderId > 0) {
                $orderIds[] = $orderId;
            }
        }

        $orderIds = array_values(array_unique($orderIds));

        if (empty($orderIds)) {
            return response()->json([
                'success' => false,
                'message' => __(
                    'No pending or confirmed orders were found in this meal category.'
                ),
                'transferred' => 0,
                'failures' => [],
            ], 422);
        }

        $transferred = 0;
        $failures = [];

        foreach ($orderIds as $orderId) {
            try {
                $chefApi->startPreparing($orderId);
                $transferred++;
            } catch (\Throwable $exception) {
                $failures[] = [
                    'order_id' => $orderId,
                    'message' => $exception->getMessage(),
                ];
            }
        }

        $success = $transferred > 0;

        return response()->json([
            'success' => $success,
            'message' => $success
                ? __(
                    ':count order(s) are now cooking.',
                    ['count' => $transferred]
                )
                : __('No orders could be moved to cooking.'),
            'transferred' => $transferred,
            'failures' => $failures,
        ], $success ? 200 : 422);
    }

    /**
     * Advance item status one step for every item in a schedule.
     * Uses existing order-level APIs to update order statuses.
     */
    public function advanceSchedule(
        Request $request,
        ChefApiService $chefApi
    ) {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'min:1'],
            'action' => [
                'required',
                'string',
                'in:start_preparing,mark_ready',
            ],
            'meal_id' => ['nullable', 'integer', 'min:1'],
            'date' => ['nullable', 'date'],
        ]);

        $targetCategoryId = (int) $validated['category_id'];
        $targetMealId = isset($validated['meal_id'])
            ? (int) $validated['meal_id']
            : null;
        $action = $validated['action'];

        $response = $chefApi->ordersToday();

        $orders = $this->apiData(
            $response,
            fn () => []
        );

        if (isset($orders['data']) && is_array($orders['data'])) {
            $orders = $orders['data'];
        } elseif (isset($orders['items']) && is_array($orders['items'])) {
            $orders = $orders['items'];
        } elseif (isset($orders['orders']) && is_array($orders['orders'])) {
            $orders = $orders['orders'];
        }

        $orders = is_array($orders) ? array_values($orders) : [];

        $fromStatuses = $action === 'start_preparing'
            ? ['pending', 'confirmed', 'scheduled']
            : ['preparing'];

        $orderIds = [];

        foreach ($orders as $order) {
            if (!is_array($order)) {
                continue;
            }

            $categoryId = (int) (
                $order['meal_category_id']
                ?? $order['category_id']
                ?? $order['category']['id']
                ?? 0
            );

            $items = is_array($order['items'] ?? null)
                ? $order['items']
                : [];

            if ($categoryId <= 0) {
                foreach ($items as $item) {
                    $candidate = (int) (
                        $item['meal_category_id']
                        ?? $item['category_id']
                        ?? 0
                    );

                    if ($candidate > 0) {
                        $categoryId = $candidate;
                        break;
                    }
                }
            }

            if ($categoryId !== $targetCategoryId) {
                continue;
            }

            if ($targetMealId !== null) {
                $containsMeal = false;

                foreach ($items as $item) {
                    if (
                        (int) (
                            $item['meal_id']
                            ?? $item['id']
                            ?? 0
                        ) === $targetMealId
                    ) {
                        $containsMeal = true;
                        break;
                    }
                }

                if (!$containsMeal) {
                    continue;
                }
            }

            $status = strtolower(
                (string) ($order['status'] ?? 'pending')
            );

            if (!in_array($status, $fromStatuses, true)) {
                continue;
            }

            $orderId = (int) (
                $order['id']
                ?? $order['order_id']
                ?? 0
            );

            if ($orderId > 0) {
                $orderIds[] = $orderId;
            }
        }

        $orderIds = array_values(array_unique($orderIds));
        $updated = 0;
        $failures = [];

        foreach ($orderIds as $orderId) {
            try {
                if ($action === 'start_preparing') {
                    $chefApi->startPreparing($orderId);
                } else {
                    $chefApi->markReady($orderId);
                }

                $updated++;
            } catch (\Throwable $exception) {
                $failures[] = [
                    'order_id' => $orderId,
                    'message' => $exception->getMessage(),
                ];
            }
        }

        $success = $updated > 0;

        $message = match ($action) {
            'start_preparing' => $success
                ? __(
                    ':count order(s) are now cooking.',
                    ['count' => $updated]
                )
                : __('No eligible orders could start cooking.'),
            'mark_ready' => $success
                ? __(
                    ':count order(s) are ready and waiting for the driver.',
                    ['count' => $updated]
                )
                : __('No cooking orders were available to mark ready.'),
        };

        return response()->json([
            'success' => $success,
            'message' => $message,
            'updated' => $updated,
            'failures' => $failures,
        ], $success ? 200 : 422);
    }

    private function formatOrder(array $order): array
    {
        $statusLabels = [
            'scheduled' => __('Scheduled'),
            'pending' => __('Pending'),
            'confirmed' => __('Confirmed'),
            'preparing' => __('Preparing'),
            'ready_for_delivery' => __('Ready for Delivery'),
            'out_for_delivery' => __('Out for Delivery'),
            'delivered' => __('Delivered'),
            'cancelled' => __('Cancelled'),
        ];

        $status = $order['status'] ?? 'pending';
        $customer = $order['customer'] ?? [];
        $delivery = $order['delivery'] ?? [];
        $items = $order['items'] ?? [];

        $deliveryDate = $order['delivery_date'] ?? null;

        // Extract categories from order items dynamically
        $primaryCategoryId = 0;
        $primaryCategoryName = __('Uncategorized');
        $mealNames = [];
        $totalCalories = 0;
        if (is_array($items)) {
            foreach ($items as $item) {
                $name = $item['meal_name'] ?? ($item['name'] ?? ($item['title'] ?? ''));
                if ($name) {
                    $qty = $item['quantity'] ?? 1;
                    $mealNames[] = $qty > 1 ? "{$name} x{$qty}" : $name;
                }
                $cal = $item['calories'] ?? 0;
                if ($cal) {
                    $totalCalories += (int) $cal * ($item['quantity'] ?? 1);
                }
                // Use first item's category as primary
                if ($primaryCategoryId === 0 && !empty($item['category_id'])) {
                    $primaryCategoryId = (int) $item['category_id'];
                    $primaryCategoryName = $item['category_name'] ?? __('Uncategorized');
                }
            }
        }

        $customerName = trim($customer['full_name'] ?? (($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''))) ?: __('Customer');

        return [
            'id' => $order['id'] ?? 0,
            'order_number' => $order['order_number'] ?? ('ORD-' . ($order['id'] ?? 0)),
            'status' => $status,
            'status_label' => $statusLabels[$status] ?? __(ucfirst(str_replace('_', ' ', $status))),
            'primary_category_id' => $primaryCategoryId,
            'primary_category_name' => $primaryCategoryName,
            'customer' => $customerName,
            'customer_phone' => $customer['phone'] ?? '',
            'delivery_address' => $order['delivery_address'] ?? '',
            'delivery_notes' => $order['delivery_notes'] ?? '',
            'delivery_date' => $deliveryDate,
            'time' => $deliveryDate ? date('H:i', strtotime($deliveryDate)) : '--:--',
            'items' => $items,
            'meal_summary' => implode(', ', $mealNames) ?: __('Multiple items'),
            'meal_count' => is_array($items) ? count($items) : 0,
            'total_calories' => $totalCalories,
            'total_amount' => $order['total_amount'] ?? 0,
            'delivery_status' => $delivery['status'] ?? null,
        ];
    }

    private function mockDashboardStats(): array
    {
        return [
            'total_orders' => 6,
            'pending_orders' => 4,
            'confirmed_orders' => 0,
            'preparing_orders' => 1,
            'ready_for_delivery_orders' => 1,
            'out_for_delivery_orders' => 0,
            'delivered_orders' => 0,
            'cancelled_orders' => 0,
            'deliveries_needed' => 1,
            'assigned_deliveries' => 0,
            'available_drivers' => 2,
            'total_active_drivers' => 3,
        ];
    }

    private function mockOrders(): array
    {
        $today = date('Y-m-d');

        return [
            [
                'id' => 1024,
                'order_number' => 'ORD-1024',
                'status' => 'pending',
                'user_id' => 1,
                'subscription_id' => null,
                'plan_id' => null,
                'total_amount' => 45.00,
                'delivery_date' => "{$today}T07:30:00",
                'delivery_address' => 'Riyadh, King Fahd District',
                'delivery_notes' => 'No nuts - allergy',
                'items' => [
                    ['meal_name' => 'Oatmeal with Berries', 'quantity' => 1, 'calories' => 350, 'category_id' => 1, 'category_name' => 'Breakfast'],
                ],
                'created_at' => "{$today}T06:00:00",
                'updated_at' => "{$today}T06:00:00",
                'customer' => [
                    'id' => 1,
                    'first_name' => 'Ahmed',
                    'last_name' => 'Al-Rashid',
                    'full_name' => 'Ahmed Al-Rashid',
                    'email' => 'ahmed@example.com',
                    'phone' => '0501234567',
                ],
                'delivery' => null,
            ],
            [
                'id' => 1025,
                'order_number' => 'ORD-1025',
                'status' => 'preparing',
                'user_id' => 2,
                'subscription_id' => null,
                'plan_id' => null,
                'total_amount' => 85.00,
                'delivery_date' => "{$today}T08:00:00",
                'delivery_address' => 'Riyadh, Olaya District',
                'delivery_notes' => '',
                'items' => [
                    ['meal_name' => 'Veggie Omelette', 'quantity' => 2, 'calories' => 420, 'category_id' => 1, 'category_name' => 'Breakfast'],
                ],
                'created_at' => "{$today}T06:00:00",
                'updated_at' => "{$today}T07:00:00",
                'customer' => [
                    'id' => 2,
                    'first_name' => 'Sara',
                    'last_name' => 'Mohammed',
                    'full_name' => 'Sara Mohammed',
                    'email' => 'sara@example.com',
                    'phone' => '0507654321',
                ],
                'delivery' => null,
            ],
            [
                'id' => 1026,
                'order_number' => 'ORD-1026',
                'status' => 'pending',
                'user_id' => 3,
                'subscription_id' => null,
                'plan_id' => null,
                'total_amount' => 55.00,
                'delivery_date' => "{$today}T12:30:00",
                'delivery_address' => 'Jeddah, Al-Balad',
                'delivery_notes' => 'Extra dressing on side',
                'items' => [
                    ['meal_name' => 'Grilled Chicken Salad', 'quantity' => 1, 'calories' => 550, 'category_id' => 2, 'category_name' => 'Lunch'],
                ],
                'created_at' => "{$today}T06:00:00",
                'updated_at' => "{$today}T06:00:00",
                'customer' => [
                    'id' => 3,
                    'first_name' => 'Khalid',
                    'last_name' => 'Omar',
                    'full_name' => 'Khalid Omar',
                    'email' => 'khalid@example.com',
                    'phone' => '0551234567',
                ],
                'delivery' => null,
            ],
            [
                'id' => 1027,
                'order_number' => 'ORD-1027',
                'status' => 'ready_for_delivery',
                'user_id' => 4,
                'subscription_id' => null,
                'plan_id' => null,
                'total_amount' => 65.00,
                'delivery_date' => "{$today}T13:00:00",
                'delivery_address' => 'Riyadh, Nakheel District',
                'delivery_notes' => '',
                'items' => [
                    ['meal_name' => 'Quinoa Buddha Bowl', 'quantity' => 1, 'calories' => 480, 'category_id' => 2, 'category_name' => 'Lunch'],
                ],
                'created_at' => "{$today}T06:00:00",
                'updated_at' => "{$today}T11:00:00",
                'customer' => [
                    'id' => 4,
                    'first_name' => 'Fatima',
                    'last_name' => 'Ali',
                    'full_name' => 'Fatima Ali',
                    'email' => 'fatima@example.com',
                    'phone' => '0561234567',
                ],
                'delivery' => null,
            ],
            [
                'id' => 1028,
                'order_number' => 'ORD-1028',
                'status' => 'pending',
                'user_id' => 5,
                'subscription_id' => null,
                'plan_id' => null,
                'total_amount' => 95.00,
                'delivery_date' => "{$today}T19:00:00",
                'delivery_address' => 'Riyadh, Diplomatic Quarter',
                'delivery_notes' => 'Well done salmon',
                'items' => [
                    ['meal_name' => 'Salmon with Roasted Vegetables', 'quantity' => 1, 'calories' => 620, 'category_id' => 3, 'category_name' => 'Dinner'],
                ],
                'created_at' => "{$today}T06:00:00",
                'updated_at' => "{$today}T06:00:00",
                'customer' => [
                    'id' => 5,
                    'first_name' => 'Omar',
                    'last_name' => 'Hassan',
                    'full_name' => 'Omar Hassan',
                    'email' => 'omar@example.com',
                    'phone' => '0571234567',
                ],
                'delivery' => null,
            ],
            [
                'id' => 1029,
                'order_number' => 'ORD-1029',
                'status' => 'pending',
                'user_id' => 6,
                'subscription_id' => null,
                'plan_id' => null,
                'total_amount' => 75.00,
                'delivery_date' => "{$today}T19:30:00",
                'delivery_address' => 'Riyadh, Al-Malqa District',
                'delivery_notes' => 'Spicy',
                'items' => [
                    ['meal_name' => 'Beef Stir Fry with Rice', 'quantity' => 2, 'calories' => 580, 'category_id' => 3, 'category_name' => 'Dinner'],
                ],
                'created_at' => "{$today}T06:00:00",
                'updated_at' => "{$today}T06:00:00",
                'customer' => [
                    'id' => 6,
                    'first_name' => 'Layla',
                    'last_name' => 'Ibrahim',
                    'full_name' => 'Layla Ibrahim',
                    'email' => 'layla@example.com',
                    'phone' => '0581234567',
                ],
                'delivery' => null,
            ],
        ];
    }
}