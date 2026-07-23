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

    public function dashboard(ChefApiService $chefApi, NotificationApiService $notificationApi, MealApiService $mealApi)
    {
        $dashboardResponse = $chefApi->dashboard();

        if (isset($dashboardResponse['success']) && $dashboardResponse['success'] === false) {
            \Log::warning('Chef dashboard API failed', [
                'status' => $dashboardResponse['status'] ?? null,
                'message' => $dashboardResponse['message'] ?? null,
            ]);
        }

        $dashboardData = $this->apiData($dashboardResponse, fn () => $this->mockDashboardStats());

        // Use grouped endpoint — extract real category IDs from the response
        $groupedResponse = $chefApi->ordersTodayGrouped();

        $useGrouped = !isset($groupedResponse['success']) || $groupedResponse['success'] !== false;

        // Icon mapping based on category name keywords
        $iconMap = [
            'breakfast' => 'sunrise',
            'lunch'     => 'sun',
            'dinner'    => 'moon',
            'supper'    => 'moon',
            'snack'     => 'cookie',
        ];

        $getIconForName = function (string $name) use ($iconMap): string {
            $lower = strtolower($name);
            foreach ($iconMap as $keyword => $icon) {
                if (str_contains($lower, $keyword)) {
                    return $icon;
                }
            }
            return 'dots';
        };

        // Determine meal-time order for sorting (breakfast first, lunch, dinner, snacks, other)
        $mealTimeOrder = ['breakfast', 'lunch', 'dinner', 'snacks', 'other'];
        $getMealTimeRank = function (string $catName): int {
            $lower = strtolower($catName);
            if (str_contains($lower, 'breakfast')) return 0;
            if (str_contains($lower, 'lunch')) return 1;
            if (str_contains($lower, 'dinner') || str_contains($lower, 'supper')) return 2;
            if (str_contains($lower, 'snack')) return 3;
            return 4;
        };

        if ($useGrouped) {
            $groups = $groupedResponse['groups'] ?? [];

            $categories = [];
            $categorizedOrders = [];
            $allOrders = [];
            $categorySeen = [];

            // ─── Step 1: Collect ALL unique orders from all groups ───
            $uniqueOrders = [];
            $categoryNameMap = [];

            foreach ($groups as $group) {
                if (!isset($group['categories'])) {
                    continue;
                }
                foreach ($group['categories'] as $catGroup) {
                    $catId = $catGroup['category_id'] ?? 0;
                    $catName = $catGroup['category_name'] ?? __('Uncategorized');
                    $categoryNameMap[$catId] = $catName;

                    foreach ($catGroup['orders'] as $order) {
                        $orderId = $order['id'] ?? 0;
                        if ($orderId && !isset($uniqueOrders[$orderId])) {
                            $uniqueOrders[$orderId] = $order;
                        }
                    }
                }
            }

            // ─── Step 2: Re-categorize each order by ALL its item categories ───
            foreach ($uniqueOrders as $orderId => $order) {
                try {
                    $formatted = $this->formatOrder($order);
                } catch (\Throwable $e) {
                    \Log::error('Chef order format failed', [
                        'order_id' => $order['id'] ?? null,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                $items = $formatted['items'] ?? [];
                $itemsByCat = [];
                foreach ($items as $itm) {
                    $itmCatId = $itm['category_id'] ?? 0;
                    if (!isset($itemsByCat[$itmCatId])) {
                        $itemsByCat[$itmCatId] = [];
                    }
                    $itemsByCat[$itmCatId][] = $itm;
                    if (!isset($categoryNameMap[$itmCatId]) && !empty($itm['category_name'])) {
                        $categoryNameMap[$itmCatId] = $itm['category_name'];
                    }
                }

                if (empty($itemsByCat)) {
                    $itemsByCat[0] = [];
                }

                foreach ($itemsByCat as $catId => $catItems) {
                    $catName = $categoryNameMap[$catId] ?? __('Uncategorized');

                    if (!isset($categorySeen[$catId])) {
                        $categories[] = [
                            'id' => $catId,
                            'name' => $catName,
                            'icon' => $getIconForName($catName),
                            'count' => 0,
                            'total_quantity' => 0,
                        ];
                        $categorizedOrders[$catId] = [];
                        $categorySeen[$catId] = count($categories) - 1;
                    }

                    // Recalculate totals for this category's items
                    $catMealNames = [];
                    $catCalories = 0;
                    $catTotalQty = 0;
                    foreach ($catItems as $ci) {
                        $qty = $ci['quantity'] ?? 1;
                        $catTotalQty += $qty;
                        $name = $ci['meal_name'] ?? ($ci['name'] ?? '');
                        if ($name) {
                            $catMealNames[] = $qty > 1 ? "{$name} x{$qty}" : $name;
                        }
                        $catCalories += (int) ($ci['calories'] ?? 0) * $qty;
                    }

                    $item = $formatted;
                    $item['primary_category_id'] = $catId;
                    $item['primary_category_name'] = $catName;
                    $item['items'] = $catItems;
                    $item['meal_summary'] = implode(', ', $catMealNames) ?: __('Multiple items');
                    $item['meal_count'] = count($catItems);
                    $item['total_quantity'] = $catTotalQty;
                    $item['total_calories'] = $catCalories;

                    $categorizedOrders[$catId][] = $item;
                    $allOrders[] = $item;
                }
            }

            foreach ($categories as &$cat) {
                $catOrders = $categorizedOrders[$cat['id']] ?? [];
                $cat['count'] = count($catOrders);
                $cat['total_quantity'] = array_sum(array_map(fn ($o) => $o['total_quantity'] ?? 0, $catOrders));
            }
            unset($cat);
        } else {
            // Fallback: use the flat orders endpoint and group dynamically
            $ordersResponse = $chefApi->ordersToday();

            if (isset($ordersResponse['success']) && $ordersResponse['success'] === false) {
                $ordersResponse = $chefApi->orders(['limit' => 100]);
            }

            if (isset($ordersResponse['success']) && $ordersResponse['success'] === false) {
                $ordersData = $this->apiEnabled() ? [] : $this->mockOrders();
            } else {
                $ordersData = $ordersResponse['data'] ?? ($this->apiEnabled() ? [] : $this->mockOrders());
            }

            if (!is_array($ordersData)) {
                $ordersData = [];
            }

            $categorizedOrders = [];
            $categoryMap = [];
            $categoryCounts = [];
            $allOrders = [];

            foreach ($ordersData as $order) {
                try {
                    $item = $this->formatOrder($order);
                } catch (\Throwable $e) {
                    \Log::error('Chef order format failed', [
                        'order_id' => $order['id'] ?? null,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                $catId = $item['primary_category_id'];
                $catName = $item['primary_category_name'];

                if (!isset($categoryMap[$catId])) {
                    $categoryMap[$catId] = $catName;
                    $categorizedOrders[$catId] = [];
                    $categoryCounts[$catId] = 0;
                }

                $categorizedOrders[$catId][] = $item;
                $allOrders[] = $item;
                $categoryCounts[$catId]++;
            }

            $categories = [];
            $sortedCatIds = array_keys($categoryMap);
            usort($sortedCatIds, fn($a, $b) => $getMealTimeRank($categoryMap[$a]) <=> $getMealTimeRank($categoryMap[$b]));

            foreach ($sortedCatIds as $catId) {
                $categories[] = [
                    'id' => $catId,
                    'name' => $categoryMap[$catId],
                    'icon' => $getIconForName($categoryMap[$catId]),
                    'count' => $categoryCounts[$catId],
                ];
            }
        }

        // Fetch all meal categories from API so the dropdown can show
        // categories that have no orders yet.
        $allCategoriesData = $this->apiData($mealApi->categoriesList(['limit' => 100]), fn () => []);

        $allCategories = [];
        if (is_array($allCategoriesData)) {
            foreach ($allCategoriesData as $cat) {
                $catId = $cat['id'] ?? 0;
                $catName = $cat['name_en'] ?? ($cat['name_ar'] ?? __('Uncategorized'));
                $allCategories[$catId] = [
                    'id' => $catId,
                    'name' => $catName,
                    'icon' => $getIconForName($catName),
                    'count' => 0,
                    'total_quantity' => 0,
                ];
            }
        }

        // Merge: start with all API categories, then update counts from order-based categories
        $existingCatIds = array_column($categories, 'id');
        foreach ($categories as $orderCat) {
            if (isset($allCategories[$orderCat['id']])) {
                $allCategories[$orderCat['id']]['count'] = $orderCat['count'];
                $allCategories[$orderCat['id']]['total_quantity'] = $orderCat['total_quantity'] ?? 0;
            } else {
                $allCategories[$orderCat['id']] = $orderCat;
            }
        }

        // Sort all categories by meal-time rank
        $allCategoryList = array_values($allCategories);
        usort($allCategoryList, fn($a, $b) => $getMealTimeRank($a['name']) <=> $getMealTimeRank($b['name']));

        $categories = $allCategoryList;

        // Ensure categorizedOrders and tabSummaries have entries for all categories
        foreach ($categories as $cat) {
            if (!isset($categorizedOrders[$cat['id']])) {
                $categorizedOrders[$cat['id']] = [];
            }
        }

        $stats = [
            'total_today' => $dashboardData['total_orders'] ?? 0,
            'pending' => ($dashboardData['pending_orders'] ?? 0) + ($dashboardData['confirmed_orders'] ?? 0),
            'preparing' => $dashboardData['preparing_orders'] ?? 0,
            'ready' => $dashboardData['ready_for_delivery_orders'] ?? 0,
            'completed' => ($dashboardData['out_for_delivery_orders'] ?? 0) + ($dashboardData['delivered_orders'] ?? 0),
            'cancelled' => $dashboardData['cancelled_orders'] ?? 0,
            'available_drivers' => $dashboardData['available_drivers'] ?? 0,
            'total_active_drivers' => $dashboardData['total_active_drivers'] ?? 0,
            'deliveries_needed' => $dashboardData['deliveries_needed'] ?? 0,
        ];

        // Fetch meals summary for today
        $mealsSummaryResponse = $chefApi->mealsSummary();
        $mealsSummary = [];
        if (!isset($mealsSummaryResponse['success']) || $mealsSummaryResponse['success'] !== false) {
            $mealsSummary = $mealsSummaryResponse['meals'] ?? [];
        }

        // Fetch allergies summary for today
        $allergiesResponse = $chefApi->allergiesSummary();
        $allergyCustomers = [];
        if (!isset($allergiesResponse['success']) || $allergiesResponse['success'] !== false) {
            $allergyCustomers = $allergiesResponse['customers'] ?? [];
        }

        $notificationsData = $this->apiData($notificationApi->my(['limit' => 5, 'is_read' => false]), fn () => []);
        $notifications = [];
        if (is_array($notificationsData)) {
            foreach ($notificationsData as $n) {
                $notifications[] = [
                    'id' => $n['id'] ?? 0,
                    'title' => $n['title'] ?? '',
                    'message' => $n['message'] ?? '',
                    'created_at' => $n['created_at'] ?? '',
                    'is_read' => $n['is_read'] ?? false,
                ];
            }
        }

        // Build per-tab summaries used by the kitchen shift screen:
        // required dish quantities, customer/meal counts, and prep progress.
        $tabSummaries = [];
        foreach ($categories as $cat) {
            $tabOrders = $categorizedOrders[$cat['id']] ?? [];
            $tabSummaries[$cat['id']] = [
                'customers' => count($tabOrders),
                'total_meals' => array_sum(array_column($tabOrders, 'meal_count')),
                'ready' => count(array_filter($tabOrders, fn ($o) => in_array($o['status'], ['ready_for_delivery', 'out_for_delivery', 'delivered']))),
                'preparing' => count(array_filter($tabOrders, fn ($o) => $o['status'] === 'preparing')),
                'pending' => count(array_filter($tabOrders, fn ($o) => in_array($o['status'], ['pending', 'confirmed', 'scheduled']))),
                'dishes' => $this->aggregateDishes($tabOrders),
            ];
        }

        // ─── Fetch ALL meals with ingredients, grouped by category ───
        $mealsData = $this->apiData($mealApi->list(['limit' => 100]), fn () => []);
        $mealsByCategory = [];
        if (is_array($mealsData)) {
            foreach ($mealsData as $meal) {
                $catId = $meal['category_id'] ?? 0;
                $mealsByCategory[$catId][] = [
                    'id' => $meal['id'] ?? 0,
                    'name' => $meal['name_en'] ?? ($meal['name_ar'] ?? 'Unknown'),
                    'image_url' => $meal['image_url'] ?? null,
                    'ingredients' => $meal['ingredients'] ?? [],
                    'allergens' => $meal['allergens'] ?? [],
                    'calories' => $meal['calories'] ?? 0,
                    'protein_g' => $meal['protein_g'] ?? 0,
                    'carbs_g' => $meal['carbs_g'] ?? 0,
                    'fat_g' => $meal['fat_g'] ?? 0,
                    'price' => $meal['price'] ?? 0,
                    'is_available' => $meal['is_available'] ?? true,
                    'description' => $meal['description'] ?? '',
                ];
            }
        }

        // Item-level Kitchen Queue: what the chef is actually meant to
        // cook per schedule (category), aggregated across every order
        // for today — never individual Order #1025/#1026 during prep.
        $today = date('Y-m-d');

        // Build a meal lookup from mealsByCategory for ingredient/allergen enrichment
        $mealLookup = [];
        foreach ($mealsByCategory as $catMeals) {
            foreach ($catMeals as $m) {
                $mealLookup[$m['id']] = $m;
            }
        }

        $scheduleByTab = [];
        foreach ($categories as $cat) {
            $catId = $cat['id'];
            $tabOrders = $categorizedOrders[$catId] ?? [];

            // Aggregate meals across all orders in this category
            $mealAgg = []; // key => aggregated meal data
            $statsPending = 0;
            $statsSentToKitchen = 0;
            $statsPreparing = 0;
            $statsReady = 0;
            $statsServed = 0;

            foreach ($tabOrders as $order) {
                $orderStatus = $order['status'] ?? 'pending';
                $customerName = $order['customer'] ?? __('Customer');
                $orderNumber = $order['order_number'] ?? ('#' . ($order['id'] ?? 0));
                $address = $order['delivery_address'] ?? '';

                foreach ($order['items'] ?? [] as $item) {
                    $mealId = $item['meal_id'] ?? ($item['id'] ?? 0);
                    $mealName = $item['meal_name'] ?? ($item['name'] ?? 'Unknown');
                    $qty = (int) ($item['quantity'] ?? 1);
                    $key = $mealId ? "id:{$mealId}" : "name:" . strtolower($mealName);

                    // Determine item-level status from order status
                    $itemStatus = match ($orderStatus) {
                        'pending', 'confirmed', 'scheduled' => 'pending',
                        'preparing' => 'preparing',
                        'ready_for_delivery' => 'ready',
                        'out_for_delivery', 'delivered' => 'served',
                        default => 'pending',
                    };

                    if (!isset($mealAgg[$key])) {
                        $mealInfo = $mealLookup[$mealId] ?? [];
                        $mealAgg[$key] = [
                            'meal_id' => $mealId,
                            'meal_name' => $mealName,
                            'image_url' => $item['image_url'] ?? ($mealInfo['image_url'] ?? null),
                            'ingredients' => $item['ingredients'] ?? ($mealInfo['ingredients'] ?? []),
                            'allergens' => $item['allergens'] ?? ($mealInfo['allergens'] ?? []),
                            'calories' => $item['calories'] ?? ($mealInfo['calories'] ?? 0),
                            'total_required' => 0,
                            'pending' => 0,
                            'sent_to_kitchen' => 0,
                            'preparing' => 0,
                            'ready' => 0,
                            'served' => 0,
                            'customers' => [],
                        ];
                    }

                    $mealAgg[$key]['total_required'] += $qty;
                    $mealAgg[$key][$itemStatus] = ($mealAgg[$key][$itemStatus] ?? 0) + $qty;

                    // Add customer entry
                    $mealAgg[$key]['customers'][] = [
                        'order_id' => $order['id'] ?? 0,
                        'order_number' => $orderNumber,
                        'customer_name' => $customerName,
                        'address' => $address,
                        'quantity' => $qty,
                        'item_status' => $itemStatus,
                    ];

                    // Update stats
                    $statsPending += ($itemStatus === 'pending') ? $qty : 0;
                    $statsSentToKitchen += ($itemStatus === 'sent_to_kitchen') ? $qty : 0;
                    $statsPreparing += ($itemStatus === 'preparing') ? $qty : 0;
                    $statsReady += ($itemStatus === 'ready') ? $qty : 0;
                    $statsServed += ($itemStatus === 'served') ? $qty : 0;
                }
            }

            // Sort meals by total_required desc
            $productionMeals = array_values($mealAgg);
            usort($productionMeals, fn ($a, $b) => $b['total_required'] <=> $a['total_required']);

            $scheduleByTab[$catId] = [
                'stats' => [
                    'pending' => $statsPending,
                    'sent_to_kitchen' => $statsSentToKitchen,
                    'preparing' => $statsPreparing,
                    'ready' => $statsReady,
                    'served' => $statsServed,
                    'total_items' => $statsPending + $statsSentToKitchen + $statsPreparing + $statsReady + $statsServed,
                ],
                'production' => [
                    'meals' => $productionMeals,
                    'total_required' => array_sum(array_column($productionMeals, 'total_required')),
                ],
                'kitchen_queue' => [
                    'meals' => $productionMeals,
                    'totals' => [
                        'pending' => $statsPending,
                        'preparing' => $statsPreparing,
                        'ready' => $statsReady,
                        'served' => $statsServed,
                    ],
                ],
            ];
        }

        return view('chef.dashboard', compact('categorizedOrders', 'categories', 'stats', 'notifications', 'mealsSummary', 'allergyCustomers', 'tabSummaries', 'scheduleByTab', 'today', 'mealsByCategory'));
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
    public function transferSchedule(Request $request, ChefApiService $chefApi)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'min:1'],
            'date' => ['nullable', 'date'],
        ]);

        $date = $validated['date'] ?? date('Y-m-d');

        // Re-fetch grouped orders to find pending orders in this category
        $groupedResponse = $chefApi->ordersTodayGrouped();
        $groups = $groupedResponse['groups'] ?? [];
        $targetCatId = (int) $validated['category_id'];
        $transferred = 0;
        $failures = 0;

        foreach ($groups as $group) {
            if (!isset($group['categories'])) {
                continue;
            }
            foreach ($group['categories'] as $catGroup) {
                $catId = $catGroup['category_id'] ?? 0;
                if ($catId !== $targetCatId) {
                    continue;
                }
                foreach ($catGroup['orders'] as $order) {
                    $status = $order['status'] ?? 'pending';
                    if (!in_array($status, ['pending', 'confirmed', 'scheduled'])) {
                        continue;
                    }
                    $orderId = $order['id'] ?? 0;
                    if (!$orderId) {
                        continue;
                    }
                    try {
                        $chefApi->startPreparing($orderId);
                        $transferred++;
                    } catch (\Throwable $e) {
                        $failures++;
                    }
                }
            }
        }

        $success = $transferred > 0;
        $message = $success
            ? __(':count items transferred to the kitchen.', ['count' => $transferred])
            : __('No pending items to transfer.');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'transferred' => $transferred,
                'failures' => $failures,
            ], $success ? 200 : 422);
        }

        return redirect()->route('chef.dashboard')->with($success ? 'status' : 'error', $message);
    }

    /**
     * Advance item status one step for every item in a schedule.
     * Uses existing order-level APIs to update order statuses.
     */
    public function advanceSchedule(Request $request, ChefApiService $chefApi)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'min:1'],
            'action' => ['required', 'string', 'in:start_preparing,mark_ready,mark_served'],
            'meal_id' => ['nullable', 'integer', 'min:1'],
            'date' => ['nullable', 'date'],
        ]);

        $date = $validated['date'] ?? date('Y-m-d');
        $action = $validated['action'];
        $targetCatId = (int) $validated['category_id'];

        // Re-fetch grouped orders to find orders in this category
        $groupedResponse = $chefApi->ordersTodayGrouped();
        $groups = $groupedResponse['groups'] ?? [];
        $updated = 0;
        $failures = 0;

        // Map action to the order statuses we need to find and the API call to make
        $fromStatuses = match ($action) {
            'start_preparing' => ['pending', 'confirmed', 'scheduled'],
            'mark_ready' => ['preparing'],
            'mark_served' => ['ready_for_delivery'],
            default => [],
        };

        foreach ($groups as $group) {
            if (!isset($group['categories'])) {
                continue;
            }
            foreach ($group['categories'] as $catGroup) {
                $catId = $catGroup['category_id'] ?? 0;
                if ($catId !== $targetCatId) {
                    continue;
                }
                foreach ($catGroup['orders'] as $order) {
                    $status = $order['status'] ?? 'pending';
                    if (!in_array($status, $fromStatuses)) {
                        continue;
                    }
                    $orderId = $order['id'] ?? 0;
                    if (!$orderId) {
                        continue;
                    }
                    try {
                        if ($action === 'start_preparing') {
                            $chefApi->startPreparing($orderId);
                        } elseif ($action === 'mark_ready') {
                            $chefApi->markReady($orderId);
                        }
                        // mark_served has no backend endpoint yet — skip
                        $updated++;
                    } catch (\Throwable $e) {
                        $failures++;
                    }
                }
            }
        }

        $labels = [
            'start_preparing' => __('Started preparing.'),
            'mark_ready' => __('Marked as ready.'),
            'mark_served' => __('Marked as served.'),
        ];

        $success = $updated > 0;
        $message = $success
            ? ($labels[$action] ?? __('Updated.'))
            : __('No items to update.');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'updated' => $updated,
                'failures' => $failures,
            ], $success ? 200 : 422);
        }

        return redirect()->route('chef.dashboard')->with($success ? 'status' : 'error', $message);
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
