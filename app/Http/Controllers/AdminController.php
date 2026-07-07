<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\AuthApiService;
use App\Services\Api\AdminApiService;
use App\Services\Api\DeliveryApiService;
use App\Services\Api\MealApiService;
use App\Services\Api\NotificationApiService;
use App\Services\Api\OrderApiService;
use App\Services\Api\PlanApiService;
use App\Services\Api\ReportsApiService;
use App\Services\Api\SubscriptionApiService;
use App\Services\Api\HasApiData;

class AdminController extends Controller
{
    use HasApiData;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authApi = app(AuthApiService::class);
            if (!$authApi->check() || !$authApi->isAdmin()) {
                abort(403, 'Access denied. Admin only.');
            }
            return $next($request);
        });
    }

    public function dashboard(AdminApiService $adminApi, OrderApiService $orderApi, SubscriptionApiService $subscriptionApi, MealApiService $mealApi)
    {
        $usersResponse = $adminApi->usersList(['limit' => 1]);
        $subscriptionsResponse = $subscriptionApi->list(['limit' => 1, 'status' => 'active']);
        $ordersResponse = $orderApi->list(['limit' => 1]);
        $mealsResponse = $mealApi->list(['limit' => 1]);

        $totalUsers = $usersResponse['meta']['total'] ?? 0;
        $activeSubscriptions = $subscriptionsResponse['meta']['total'] ?? 0;
        $totalOrders = $ordersResponse['meta']['total'] ?? 0;
        $totalMeals = $mealsResponse['meta']['total'] ?? 0;

        $deliveriesResponse = app(DeliveryApiService::class)->list(['limit' => 1]);
        $totalDeliveries = $deliveriesResponse['meta']['total'] ?? 0;

        $stats = [
            'totalUsers' => $totalUsers,
            'newUsersThisWeek' => 0,
            'totalRevenue' => 0,
            'activeSubscriptions' => $activeSubscriptions,
            'totalMeals' => $totalMeals,
            'successRate' => 0,
            'ordersToday' => $totalOrders,
            'deliveriesToday' => $totalDeliveries,
            'pendingPayments' => 0,
            'avgOrderValue' => 0,
            'monthlyRevenue' => 0,
            'lastMonthRevenue' => 0,
            'totalCustomers' => $totalUsers,
            'newCustomersThisWeek' => 0,
            'churnRate' => 0,
            'retentionRate' => 0,
        ];

        $recentOrdersData = $this->apiData($orderApi->list(['limit' => 6]), function () {
            return [];
        });

        $recentOrders = [];
        if (!empty($recentOrdersData)) {
            foreach ($recentOrdersData as $order) {
                $recentOrders[] = [
                    'id' => $order['order_number'] ?? ('ORD-' . ($order['id'] ?? 0)),
                    'customer' => ($order['user']['first_name'] ?? '') . ' ' . ($order['user']['last_name'] ?? '') ?: 'Customer',
                    'plan' => $order['plan_name'] ?? 'Plan',
                    'amount' => $order['total_amount'] ?? 0,
                    'status' => $order['status'] ?? 'pending',
                ];
            }
        }

        $revenueTrend = [];
        $ordersTrend = [];

        $plansData = $this->apiData($adminApi->plansList(['limit' => 100]), function () {
            return [];
        });

        $planDistribution = [];
        if (!empty($plansData)) {
            $colors = ['#173327', '#033133', '#f9ac00', '#3b82f6', '#8b5cf6', '#ef4444'];
            $colorIndex = 0;
            foreach ($plansData as $plan) {
                $planDistribution[] = [
                    'name' => $plan['name_en'] ?? 'Plan',
                    'count' => $plan['subscribers_count'] ?? 0,
                    'color' => $colors[$colorIndex % count($colors)],
                ];
                $colorIndex++;
            }
        }

        $topMealsData = $this->apiData($mealApi->list(['limit' => 5]), fn () => []);
        $topMeals = [];
        foreach ($topMealsData as $meal) {
            $topMeals[] = [
                'name' => $meal['name_en'] ?? 'Meal',
                'orders' => $meal['orders_count'] ?? 0,
                'revenue' => $meal['revenue'] ?? 0,
            ];
        }

        $deliveryZones = [];

        return view('admin.dashboard', compact('stats', 'revenueTrend', 'ordersTrend', 'planDistribution', 'recentOrders', 'topMeals', 'deliveryZones'));
    }

    public function customers(AdminApiService $adminApi)
    {
        $usersData = $this->apiData($adminApi->usersList(['limit' => 100, 'role' => 'customer']), function () {
            return [];
        });

        $customers = [];
        if (!empty($usersData)) {
            foreach ($usersData as $user) {
                $customers[] = [
                    'id' => $user['id'] ?? 0,
                    'name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: 'Unknown',
                    'email' => $user['email'] ?? '',
                    'phone' => $user['phone'] ?? '',
                    'plan' => $user['subscription']['plan_name'] ?? 'No Plan',
                    'status' => $user['subscription']['status'] ?? ($user['is_active'] ?? true ? 'active' : 'inactive'),
                    'orders' => $user['orders_count'] ?? 0,
                    'spent' => $user['total_spent'] ?? 0,
                    'joined' => $user['created_at'] ?? date('Y-m-d'),
                ];
            }
        }

        $stats = [
            'total' => count($customers),
            'active' => count(array_filter($customers, fn ($c) => $c['status'] === 'active')),
            'paused' => count(array_filter($customers, fn ($c) => $c['status'] === 'paused')),
            'cancelled' => count(array_filter($customers, fn ($c) => $c['status'] === 'cancelled')),
            'newThisWeek' => 0,
        ];

        return view('admin.customers', compact('customers', 'stats'));
    }

    public function subscriptions(PlanApiService $planApi, SubscriptionApiService $subscriptionApi)
    {
        $plansData = $this->apiData($planApi->list(['limit' => 100]), function () {
            return [];
        });

        $subscriptionsData = $this->apiData($subscriptionApi->list(['limit' => 100]), function () {
            return [];
        });

        $plans = [];
        if (!empty($plansData)) {
            $colors = ['#173327', '#033133', '#f9ac00', '#3b82f6', '#8b5cf6', '#ef4444'];
            $colorIndex = 0;
            foreach ($plansData as $plan) {
                $subscriberCount = 0;
                if (!empty($subscriptionsData)) {
                    foreach ($subscriptionsData as $sub) {
                        if (($sub['plan_id'] ?? 0) === ($plan['id'] ?? 0)) {
                            $subscriberCount++;
                        }
                    }
                }
                $plans[] = [
                    'id' => $plan['id'] ?? 0,
                    'name' => $plan['name_en'] ?? 'Plan',
                    'price' => $plan['price'] ?? 0,
                    'duration' => ($plan['duration_days'] ?? 28) . ' days',
                    'meals' => $plan['total_meals'] ?? 84,
                    'subscribers' => $subscriberCount,
                    'status' => ($plan['is_active'] ?? true) ? 'active' : 'draft',
                    'calories' => $plan['calories'] ?? '1500-1800',
                    'color' => $colors[$colorIndex % count($colors)],
                ];
                $colorIndex++;
            }
        }

        $totalSubscribers = array_sum(array_column($plans, 'subscribers'));
        $activePlans = count(array_filter($plans, fn ($p) => $p['status'] === 'active'));
        $avgPrice = count($plans) > 0 ? round(array_sum(array_column($plans, 'price')) / count($plans)) : 0;

        $stats = [
            'total' => count($plans),
            'active' => $activePlans,
            'draft' => count($plans) - $activePlans,
            'totalSubscribers' => $totalSubscribers,
            'avgRevenue' => $avgPrice,
            'mrr' => $totalSubscribers * $avgPrice,
            'churnRate' => 0,
            'growthRate' => 0,
        ];

        return view('admin.subscriptions', compact('plans', 'stats'));
    }

    public function plans(PlanApiService $planApi, SubscriptionApiService $subscriptionApi)
    {
        $plansData = $this->apiData($planApi->list(['limit' => 100]), function () {
            return [];
        });

        $subscriptionsData = $this->apiData($subscriptionApi->list(['limit' => 100]), function () {
            return [];
        });

        $plans = [];
        if (!empty($plansData)) {
            $colors = ['#173327', '#033133', '#f9ac00', '#3b82f6', '#8b5cf6', '#ef4444'];
            $colorIndex = 0;
            foreach ($plansData as $plan) {
                $subscriberCount = 0;
                if (!empty($subscriptionsData)) {
                    foreach ($subscriptionsData as $sub) {
                        if (($sub['plan_id'] ?? 0) === ($plan['id'] ?? 0)) {
                            $subscriberCount++;
                        }
                    }
                }
                $plans[] = [
                    'id' => $plan['id'] ?? 0,
                    'name' => $plan['name_en'] ?? 'Plan',
                    'price' => $plan['price'] ?? 0,
                    'duration' => ($plan['duration_days'] ?? 28) . ' days',
                    'meals' => $plan['total_meals'] ?? 84,
                    'subscribers' => $subscriberCount,
                    'status' => ($plan['is_active'] ?? true) ? 'active' : 'draft',
                    'calories' => $plan['calories'] ?? '1500-1800',
                    'color' => $colors[$colorIndex % count($colors)],
                ];
                $colorIndex++;
            }
        }

        $totalSubscribers = array_sum(array_column($plans, 'subscribers'));
        $activePlans = count(array_filter($plans, fn ($p) => $p['status'] === 'active'));
        $avgPrice = count($plans) > 0 ? round(array_sum(array_column($plans, 'price')) / count($plans)) : 0;

        $stats = [
            'total' => count($plans),
            'active' => $activePlans,
            'totalSubscribers' => $totalSubscribers,
            'avgRevenue' => $avgPrice,
        ];

        return view('admin.plans', compact('plans', 'stats'));
    }

    public function storePlan(Request $request, PlanApiService $planApi)
    {
        $validator = validator($request->all(), [
            'name_en' => ['required', 'string', 'min:2', 'max:150'],
            'name_ar' => ['nullable', 'string', 'max:150'],
            'description_en' => ['nullable', 'string'],
            'description_ar' => ['nullable', 'string'],
            'plan_type' => ['required', 'in:weekly,monthly,custom,corporate'],
            'goal' => ['nullable', 'in:weight_loss,muscle_gain,maintenance'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'meals_per_day' => ['required', 'integer', 'min:1'],
            'total_meals' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Please fix the errors in the form.'),
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $response = $this->apiData($planApi->create($data), function () {
            return ['success' => false, 'message' => 'Failed to create plan.'];
        });

        $success = is_array($response) && ($response['success'] ?? true) !== false && !isset($response['errors']);
        $message = $response['message'] ?? ($success ? __('Plan created successfully.') : __('Failed to create plan.'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'redirect' => route('admin.plans'),
            ], $success ? 200 : 422);
        }

        if ($success) {
            return redirect()->route('admin.plans')->with('status', $message);
        }

        return back()->withErrors(['general' => $message])->withInput();
    }

    public function meals(MealApiService $mealApi)
    {
        $mealsData = $this->apiData($mealApi->list(['limit' => 100]), function () {
            return [];
        });

        $categoriesData = $this->apiData($mealApi->categoriesList(['limit' => 100]), function () {
            return [];
        });

        $meals = [];
        if (!empty($mealsData)) {
            foreach ($mealsData as $meal) {
                $meals[] = [
                    'id' => $meal['id'] ?? 0,
                    'name' => $meal['name_en'] ?? 'Meal',
                    'category' => $meal['category']['name_en'] ?? ($meal['category_name'] ?? 'Uncategorized'),
                    'calories' => $meal['calories'] ?? 0,
                    'protein' => $meal['protein_g'] ?? 0,
                    'carbs' => $meal['carbs_g'] ?? 0,
                    'fat' => $meal['fat_g'] ?? 0,
                    'orders' => $meal['orders_count'] ?? 0,
                    'rating' => $meal['rating'] ?? 0,
                    'status' => ($meal['is_available'] ?? true) ? 'active' : 'draft',
                    'image' => $meal['image_url'] ?? '',
                ];
            }
        }


        $categories = [];
        if (!empty($categoriesData)) {
            $colors = ['#173327', '#8b5cf6', '#3b82f6', '#f9ac00', '#033133'];
            $colorIndex = 0;
            foreach ($categoriesData as $category) {
                $categories[] = [
                    'name' => $category['name_en'] ?? 'Category',
                    'count' => $category['meals_count'] ?? 0,
                    'color' => $colors[$colorIndex % count($colors)],
                ];
                $colorIndex++;
            }
        }


        $activeMeals = count(array_filter($meals, fn ($m) => $m['status'] === 'active'));
        $totalOrders = array_sum(array_column($meals, 'orders'));
        $ratedMeals = array_filter($meals, fn ($m) => $m['rating'] > 0);
        $avgRating = count($ratedMeals) > 0 ? round(array_sum(array_column($ratedMeals, 'rating')) / count($ratedMeals), 1) : 0;

        $stats = [
            'total' => count($meals),
            'active' => $activeMeals,
            'draft' => count($meals) - $activeMeals,
            'categories' => count($categories),
            'avgRating' => $avgRating,
            'totalOrders' => $totalOrders,
        ];

        return view('admin.meals', compact('meals', 'categories', 'stats'));
    }

    public function orders(OrderApiService $orderApi)
    {
        $ordersData = $this->apiData($orderApi->list(['limit' => 100]), function () {
            return [];
        });

        $orders = [];
        if (!empty($ordersData)) {
            foreach ($ordersData as $order) {
                $orders[] = [
                    'id' => $order['order_number'] ?? ('ORD-' . ($order['id'] ?? 0)),
                    'customer' => trim(($order['user']['first_name'] ?? '') . ' ' . ($order['user']['last_name'] ?? '')) ?: 'Customer',
                    'plan' => $order['plan_name'] ?? 'Plan',
                    'amount' => $order['total_amount'] ?? 0,
                    'status' => $order['status'] ?? 'pending',
                    'date' => $order['created_at'] ?? date('Y-m-d'),
                    'delivery' => $order['delivery_date'] ?? 'N/A',
                ];
            }
        }


        $total = count($orders);
        $delivered = count(array_filter($orders, fn ($o) => $o['status'] === 'delivered'));
        $pending = count(array_filter($orders, fn ($o) => in_array($o['status'], ['pending', 'preparing'])));
        $revenue = array_sum(array_map(fn ($o) => $o['status'] !== 'cancelled' ? $o['amount'] : 0, $orders));

        $stats = [
            'total' => $total,
            'today' => count(array_filter($orders, fn ($o) => ($o['date'] ?? '') === date('Y-m-d'))),
            'pending' => $pending,
            'delivered' => $delivered,
            'revenue' => $revenue,
        ];

        return view('admin.orders', compact('orders', 'stats'));
    }

    public function deliveries(DeliveryApiService $deliveryApi)
    {
        $deliveriesData = $this->apiData($deliveryApi->list(['limit' => 100]), function () {
            return [];
        });

        $deliveries = [];
        if (!empty($deliveriesData)) {
            foreach ($deliveriesData as $delivery) {
                $deliveries[] = [
                    'id' => 'DLV-' . ($delivery['id'] ?? 0),
                    'order' => 'ORD-' . ($delivery['order_id'] ?? 0),
                    'customer' => trim(($delivery['user']['first_name'] ?? '') . ' ' . ($delivery['user']['last_name'] ?? '')) ?: 'Customer',
                    'zone' => $delivery['zone'] ?? 'N/A',
                    'driver' => $delivery['driver_name'] ?? 'Unassigned',
                    'status' => $delivery['status'] ?? 'pending',
                    'time' => !empty($delivery['scheduled_at']) ? date('H:i', strtotime($delivery['scheduled_at'])) : '--:--',
                    'eta' => $delivery['eta'] ?? 'On time',
                ];
            }
        }

        $zonesMap = [];
        foreach ($deliveries as $delivery) {
            $zoneName = $delivery['zone'] ?? 'N/A';
            if (!isset($zonesMap[$zoneName])) {
                $zonesMap[$zoneName] = ['name' => $zoneName, 'orders' => 0, 'drivers' => 0, 'completed' => 0];
            }
            $zonesMap[$zoneName]['orders']++;
            if (($delivery['status'] ?? '') === 'delivered') {
                $zonesMap[$zoneName]['completed']++;
            }
        }
        $zones = array_values($zonesMap);

        $total = count($deliveries);
        $delivered = count(array_filter($deliveries, fn ($d) => $d['status'] === 'delivered'));
        $enRoute = count(array_filter($deliveries, fn ($d) => in_array($d['status'], ['en_route', 'out_for_delivery'])));
        $preparing = count(array_filter($deliveries, fn ($d) => in_array($d['status'], ['preparing', 'pending', 'assigned', 'picked_up'])));
        $scheduled = count(array_filter($deliveries, fn ($d) => $d['status'] === 'scheduled'));

        $stats = [
            'total' => $total,
            'delivered' => $delivered,
            'enRoute' => $enRoute,
            'preparing' => $preparing,
            'scheduled' => $scheduled,
            'onTimeRate' => $total > 0 ? round(($delivered / $total) * 100, 1) : 0,
        ];

        return view('admin.deliveries', compact('deliveries', 'zones', 'stats'));
    }

    public function payments(OrderApiService $orderApi)
    {
        // NOTE: Backend /payments endpoints not implemented yet (see BACKEND_RECOMMENDATIONS.md).
        // Payments view is derived from orders data until the payments module exists.
        $ordersData = $this->apiData($orderApi->list(['limit' => 100]), fn () => []);

        $payments = [];
        foreach ($ordersData as $order) {
            $payments[] = [
                'id' => 'PAY-' . ($order['id'] ?? 0),
                'order' => $order['order_number'] ?? ('ORD-' . ($order['id'] ?? 0)),
                'customer' => trim(($order['user']['first_name'] ?? '') . ' ' . ($order['user']['last_name'] ?? '')) ?: 'Customer',
                'amount' => $order['total_amount'] ?? 0,
                'method' => $order['payment_method'] ?? 'N/A',
                'status' => $order['payment_status'] ?? (($order['status'] ?? '') === 'cancelled' ? 'refunded' : 'completed'),
                'date' => $order['created_at'] ?? '',
            ];
        }

        $completed = array_filter($payments, fn ($p) => $p['status'] === 'completed');
        $totalRevenue = array_sum(array_column($completed, 'amount'));

        $stats = [
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => array_sum(array_column(array_filter($completed, fn ($p) => str_starts_with($p['date'] ?? '', date('Y-m-d'))), 'amount')),
            'successRate' => count($payments) > 0 ? round((count($completed) / count($payments)) * 100, 1) : 0,
            'pending' => count(array_filter($payments, fn ($p) => $p['status'] === 'pending')),
            'failed' => count(array_filter($payments, fn ($p) => $p['status'] === 'failed')),
            'refunded' => array_sum(array_column(array_filter($payments, fn ($p) => $p['status'] === 'refunded'), 'amount')),
        ];

        return view('admin.payments', compact('payments', 'stats'));
    }

    public function analytics(ReportsApiService $reportsApi)
    {
        // NOTE: Backend /reports endpoints not implemented yet (see BACKEND_RECOMMENDATIONS.md).
        $revenueTrendApi = $this->apiData($reportsApi->revenueTrend(), fn () => []);
        $exportHistoryApi = $this->apiData($reportsApi->auditExportHistory(), fn () => []);

        $reports = [];
        foreach ($exportHistoryApi as $export) {
            $reports[] = [
                'name' => $export['type'] ?? 'Report',
                'type' => $export['category'] ?? 'General',
                'period' => $export['period'] ?? '',
                'format' => $export['format'] ?? 'PDF',
                'date' => $export['time'] ?? '',
            ];
        }

        $chartData = [
            'months' => $revenueTrendApi['labels'] ?? [],
            'revenue' => $revenueTrendApi['current'] ?? [],
            'customers' => $revenueTrendApi['customers'] ?? [],
        ];

        $stats = [
            'totalReports' => count($reports),
            'generatedThisMonth' => 0,
            'scheduled' => 0,
            'avgGenTime' => 'N/A',
        ];

        return view('admin.analytics', compact('reports', 'chartData', 'stats'));
    }

    public function notifications(NotificationApiService $notificationApi)
    {
        $notificationsData = $this->apiData($notificationApi->list(['limit' => 100]), function () {
            return [];
        });

        $notifications = [];
        if (!empty($notificationsData)) {
            foreach ($notificationsData as $notification) {
                $notifications[] = [
                    'id' => $notification['id'] ?? 0,
                    'title' => $notification['title'] ?? 'Notification',
                    'message' => $notification['message'] ?? '',
                    'type' => $notification['notification_type'] ?? 'general',
                    'channel' => $notification['channel'] ?? 'email',
                    'status' => ($notification['is_read'] ?? false) ? 'read' : 'sent',
                    'time' => !empty($notification['created_at']) ? $this->timeAgo($notification['created_at']) : 'Just now',
                    'recipient' => $notification['recipient'] ?? 'all',
                ];
            }
        }

        $templates = [];

        $totalSent = count($notifications);
        $failed = count(array_filter($notifications, fn ($n) => $n['status'] === 'failed'));
        $pending = count(array_filter($notifications, fn ($n) => $n['status'] === 'pending'));

        $stats = [
            'totalSent' => $totalSent,
            'todaySent' => $totalSent,
            'deliveryRate' => $totalSent > 0 ? round((($totalSent - $failed) / $totalSent) * 100, 1) : 0,
            'failed' => $failed,
            'pending' => $pending,
            'openRate' => 0,
        ];

        return view('admin.notifications', compact('notifications', 'templates', 'stats'));
    }

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

    public function content()
    {
        // NOTE: Backend /content endpoints not implemented yet (see BACKEND_RECOMMENDATIONS.md).
        $pages = [];

        $stats = [
            'totalPages' => 0,
            'published' => 0,
            'draft' => 0,
            'totalViews' => 0,
        ];

        return view('admin.content', compact('pages', 'stats'));
    }

    public function settings()
    {
        $settings = [
            'company' => [
                'name' => 'Nutrio Meals',
                'email' => 'support@nutriomeals.com',
                'phone' => '+966 11 234 5678',
                'address' => 'King Fahd Road, Riyadh, Saudi Arabia',
                'currency' => 'SAR',
                'timezone' => 'Asia/Riyadh',
            ],
            'delivery' => [
                'cutoff_time' => '18:00',
                'delivery_hours' => '08:00 - 20:00',
                'min_order' => 100,
                'free_delivery_threshold' => 300,
            ],
            'payment' => [
                'methods' => ['Credit Card', 'Apple Pay', 'Mada', 'Bank Transfer'],
                'auto_capture' => true,
                'refund_window' => 7,
            ],
        ];

        return view('admin.settings', compact('settings'));
    }

    // ─── Phase 11: Reporting ───

    public function reportDashboard(ReportsApiService $reportsApi)
    {
        $kpis = $this->apiData($reportsApi->dashboardKpis(), fn () => []);
        $revenueTrendApi = $this->apiData($reportsApi->dashboardRevenueTrend(), fn () => []);
        $subscriptionFunnelApi = $this->apiData($reportsApi->dashboardSubscriptionFunnel(), fn () => []);
        $deliverySlaApi = $this->apiData($reportsApi->dashboardDeliverySla(), fn () => []);
        $exceptionsApi = $this->apiData($reportsApi->dashboardExceptions(), fn () => []);
        $operationalMetricsApi = $this->apiData($reportsApi->dashboardOperationalMetrics(), fn () => []);

        $revenueTrend = !empty($revenueTrendApi) ? $revenueTrendApi : ['labels' => [], 'current' => [], 'previous' => []];
        $subscriptionFunnel = $subscriptionFunnelApi;
        $deliverySla = $deliverySlaApi;
        $exceptions = $exceptionsApi;
        $operationalMetrics = $operationalMetricsApi;

        $lastUpdated = now()->format('Y-m-d H:i') . ' UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.dashboard', compact('kpis', 'revenueTrend', 'subscriptionFunnel', 'deliverySla', 'exceptions', 'operationalMetrics', 'lastUpdated', 'timezone'));
    }

    public function reportRevenue(ReportsApiService $reportsApi)
    {
        $kpis = $this->apiData($reportsApi->revenueKpis(), fn () => []);
        $revenueTrendApi = $this->apiData($reportsApi->revenueTrend(), fn () => []);
        $paymentTrendsApi = $this->apiData($reportsApi->revenuePaymentTrends(), fn () => []);
        $refundVolumeApi = $this->apiData($reportsApi->revenueRefundVolume(), fn () => []);
        $paymentMethodsApi = $this->apiData($reportsApi->revenuePaymentMethods(), fn () => []);
        $revenueByPlanApi = $this->apiData($reportsApi->revenueByPlan(), fn () => []);

        $revenueTrend = !empty($revenueTrendApi) ? $revenueTrendApi : ['labels' => [], 'current' => [], 'previous' => []];
        $paymentTrends = !empty($paymentTrendsApi) ? $paymentTrendsApi : ['labels' => [], 'success' => [], 'failure' => []];
        $refundVolume = !empty($refundVolumeApi) ? $refundVolumeApi : ['labels' => [], 'amount' => [], 'count' => []];
        $paymentMethods = $paymentMethodsApi;
        $revenueByPlan = $revenueByPlanApi;

        $lastUpdated = now()->format('Y-m-d H:i') . ' UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.revenue', compact('kpis', 'revenueTrend', 'paymentTrends', 'refundVolume', 'paymentMethods', 'revenueByPlan', 'lastUpdated', 'timezone'));
    }

    public function reportDelivery(ReportsApiService $reportsApi)
    {
        $kpis = $this->apiData($reportsApi->deliveryKpis(), fn () => []);
        $onTimeTrendApi = $this->apiData($reportsApi->deliveryOnTimeTrend(), fn () => []);
        $zonePerformanceApi = $this->apiData($reportsApi->deliveryZonePerformance(), fn () => []);
        $exceptionReasonsApi = $this->apiData($reportsApi->deliveryExceptionReasons(), fn () => []);
        $driverProductivityApi = $this->apiData($reportsApi->deliveryDriverProductivity(), fn () => []);
        $deliveryHeatmapApi = $this->apiData($reportsApi->deliveryHeatmap(), fn () => []);

        $onTimeTrend = !empty($onTimeTrendApi) ? $onTimeTrendApi : ['labels' => [], 'rate' => [], 'target' => []];
        $zonePerformance = $zonePerformanceApi;
        $exceptionReasons = $exceptionReasonsApi;
        $driverProductivity = $driverProductivityApi;
        $deliveryHeatmap = $deliveryHeatmapApi;
        $heatmapHours = ['06-08', '08-10', '10-12', '12-14', '14-16', '16-18', '18-20', '20-22'];

        $lastUpdated = now()->format('Y-m-d H:i') . ' UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.delivery', compact('kpis', 'onTimeTrend', 'zonePerformance', 'exceptionReasons', 'driverProductivity', 'deliveryHeatmap', 'heatmapHours', 'lastUpdated', 'timezone'));
    }

    public function reportSubscriptions(ReportsApiService $reportsApi)
    {
        $kpis = $this->apiData($reportsApi->subscriptionsKpis(), fn () => []);
        $newVsChurnApi = $this->apiData($reportsApi->subscriptionsNewVsChurn(), fn () => []);
        $renewalTrendApi = $this->apiData($reportsApi->subscriptionsRenewalTrend(), fn () => []);
        $planRankingApi = $this->apiData($reportsApi->subscriptionsPlanRanking(), fn () => []);
        $goalDistributionApi = $this->apiData($reportsApi->subscriptionsGoalDistribution(), fn () => []);
        $corporateMetricsApi = $this->apiData($reportsApi->subscriptionsCorporateMetrics(), fn () => []);

        $newVsChurn = !empty($newVsChurnApi) ? $newVsChurnApi : ['labels' => [], 'new' => [], 'churn' => []];
        $renewalTrend = !empty($renewalTrendApi) ? $renewalTrendApi : ['labels' => [], 'rate' => []];
        $planRanking = $planRankingApi;
        $goalDistribution = $goalDistributionApi;
        $corporateMetrics = $corporateMetricsApi;

        $lastUpdated = now()->format('Y-m-d H:i') . ' UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.subscriptions', compact('kpis', 'newVsChurn', 'renewalTrend', 'planRanking', 'goalDistribution', 'corporateMetrics', 'lastUpdated', 'timezone'));
    }

    public function reportNotifications(ReportsApiService $reportsApi)
    {
        $kpis = $this->apiData($reportsApi->notificationsKpis(), fn () => []);
        $sendVolumeApi = $this->apiData($reportsApi->notificationsSendVolume(), fn () => []);
        $channelMixApi = $this->apiData($reportsApi->notificationsChannelMix(), fn () => []);
        $campaignPerformanceApi = $this->apiData($reportsApi->notificationsCampaignPerformance(), fn () => []);
        $failedDiagnosticsApi = $this->apiData($reportsApi->notificationsFailedDiagnostics(), fn () => []);

        $sendVolumeByChannel = !empty($sendVolumeApi) ? $sendVolumeApi : ['labels' => [], 'email' => [], 'sms' => [], 'push' => [], 'whatsapp' => []];
        $channelMix = $channelMixApi;
        $campaignPerformance = $campaignPerformanceApi;
        $failedDiagnostics = $failedDiagnosticsApi;

        $lastUpdated = now()->format('Y-m-d H:i') . ' UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.notifications', compact('kpis', 'sendVolumeByChannel', 'channelMix', 'campaignPerformance', 'failedDiagnostics', 'lastUpdated', 'timezone'));
    }

    public function reportAudit(ReportsApiService $reportsApi)
    {
        $kpis = $this->apiData($reportsApi->auditKpis(), fn () => []);
        $changeHotspotsApi = $this->apiData($reportsApi->auditChangeHotspots(), fn () => []);
        $auditEventsApi = $this->apiData($reportsApi->auditEvents(), fn () => []);
        $exportHistoryApi = $this->apiData($reportsApi->auditExportHistory(), fn () => []);

        $changeHotspots = $changeHotspotsApi;
        $auditEvents = $auditEventsApi;
        $exportHistory = $exportHistoryApi;

        $lastUpdated = now()->format('Y-m-d H:i') . ' UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.audit', compact('kpis', 'changeHotspots', 'auditEvents', 'exportHistory', 'lastUpdated', 'timezone'));
    }
}
