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

    public function payments()
    {
        $payments = [
            ['id' => 'PAY-901', 'order' => 'ORD-2401', 'customer' => 'Ahmed Al-Saud', 'amount' => 420, 'method' => 'Credit Card', 'status' => 'completed', 'date' => '2025-06-30 09:05'],
            ['id' => 'PAY-900', 'order' => 'ORD-2400', 'customer' => 'Sarah Al-Otaibi', 'amount' => 380, 'method' => 'Apple Pay', 'status' => 'completed', 'date' => '2025-06-30 10:15'],
            ['id' => 'PAY-899', 'order' => 'ORD-2399', 'customer' => 'Khalid Al-Ghamdi', 'amount' => 295, 'method' => 'Mada', 'status' => 'pending', 'date' => '2025-06-30 10:45'],
            ['id' => 'PAY-898', 'order' => 'ORD-2398', 'customer' => 'Noura Al-Harbi', 'amount' => 510, 'method' => 'Credit Card', 'status' => 'completed', 'date' => '2025-06-29 08:10'],
            ['id' => 'PAY-897', 'order' => 'ORD-2397', 'customer' => 'Faisal Al-Qahtani', 'amount' => 420, 'method' => 'Bank Transfer', 'status' => 'failed', 'date' => '2025-06-30 11:20'],
            ['id' => 'PAY-896', 'order' => 'ORD-2396', 'customer' => 'Layla Al-Subaie', 'amount' => 380, 'method' => 'Apple Pay', 'status' => 'completed', 'date' => '2025-06-29 09:30'],
            ['id' => 'PAY-895', 'order' => 'ORD-2394', 'customer' => 'Reem Al-Mutairi', 'amount' => 510, 'method' => 'Credit Card', 'status' => 'refunded', 'date' => '2025-06-29 10:00'],
        ];

        $stats = [
            'totalRevenue' => 487320,
            'todayRevenue' => 2480,
            'successRate' => 98.6,
            'pending' => 12,
            'failed' => 3,
            'refunded' => 510,
        ];

        return view('admin.payments', compact('payments', 'stats'));
    }

    public function analytics()
    {
        $reports = [
            ['name' => 'Revenue Summary', 'type' => 'Financial', 'period' => 'Jun 2025', 'format' => 'PDF', 'date' => '2025-06-30'],
            ['name' => 'Customer Growth', 'type' => 'Analytics', 'period' => 'Q2 2025', 'format' => 'Excel', 'date' => '2025-06-28'],
            ['name' => 'Meal Performance', 'type' => 'Operations', 'period' => 'Jun 2025', 'format' => 'PDF', 'date' => '2025-06-27'],
            ['name' => 'Delivery Efficiency', 'type' => 'Operations', 'period' => 'Jun 2025', 'format' => 'Excel', 'date' => '2025-06-25'],
            ['name' => 'Churn Analysis', 'type' => 'Analytics', 'period' => 'Q2 2025', 'format' => 'PDF', 'date' => '2025-06-20'],
            ['name' => 'Payment Reconciliation', 'type' => 'Financial', 'period' => 'May 2025', 'format' => 'Excel', 'date' => '2025-06-05'],
        ];

        $chartData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'revenue' => [285000, 312000, 358000, 389000, 421800, 487320],
            'customers' => [820, 910, 1020, 1100, 1180, 1248],
        ];

        $stats = [
            'totalReports' => 48,
            'generatedThisMonth' => 12,
            'scheduled' => 4,
            'avgGenTime' => '3.2s',
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

        if (empty($notifications)) {
            $notifications = [
                ['id' => 1, 'title' => 'New subscription activated', 'message' => 'Ahmed Al-Saud subscribed to Weight Loss Pro', 'type' => 'subscription', 'channel' => 'email', 'status' => 'sent', 'time' => '2 min ago', 'recipient' => 'ahmed@example.com'],
                ['id' => 2, 'title' => 'Delivery completed', 'message' => 'Order ORD-2401 delivered to Riyadh Central', 'type' => 'delivery', 'channel' => 'sms', 'status' => 'sent', 'time' => '15 min ago', 'recipient' => '+966551234567'],
                ['id' => 3, 'title' => 'Payment failed', 'message' => 'Payment for ORD-2397 failed - Bank Transfer declined', 'type' => 'payment', 'channel' => 'email', 'status' => 'failed', 'time' => '32 min ago', 'recipient' => 'faisal@example.com'],
                ['id' => 4, 'title' => 'New customer registered', 'message' => 'Reem Al-Mutairi joined Nutrio Meals', 'type' => 'customer', 'channel' => 'whatsapp', 'status' => 'sent', 'time' => '1 hour ago', 'recipient' => '+966558901234'],
                ['id' => 5, 'title' => 'Meal plan reminder', 'message' => 'Your Weight Loss Pro plan renews in 3 days', 'type' => 'reminder', 'channel' => 'push', 'status' => 'sent', 'time' => '2 hours ago', 'recipient' => 'sarah@example.com'],
                ['id' => 6, 'title' => 'Weekly digest', 'message' => 'Your weekly nutrition summary is ready', 'type' => 'digest', 'channel' => 'email', 'status' => 'sent', 'time' => '5 hours ago', 'recipient' => 'all subscribers'],
                ['id' => 7, 'title' => 'Subscription paused', 'message' => 'Noura Al-Harbi paused Keto Premium subscription', 'type' => 'subscription', 'channel' => 'email', 'status' => 'sent', 'time' => '6 hours ago', 'recipient' => 'noura@example.com'],
                ['id' => 8, 'title' => 'Delivery delayed', 'message' => 'ORD-2399 delivery delayed by 15 minutes', 'type' => 'delivery', 'channel' => 'sms', 'status' => 'pending', 'time' => '8 hours ago', 'recipient' => '+966553456789'],
            ];
        }

        $templates = [
            ['name' => 'Welcome Email', 'type' => 'email', 'trigger' => 'New registration', 'sends' => 1248],
            ['name' => 'Delivery Notification', 'type' => 'sms', 'trigger' => 'Order dispatched', 'sends' => 2156],
            ['name' => 'Payment Receipt', 'type' => 'email', 'trigger' => 'Payment completed', 'sends' => 2104],
            ['name' => 'Renewal Reminder', 'type' => 'push', 'trigger' => '3 days before renewal', 'sends' => 342],
            ['name' => 'Weekly Digest', 'type' => 'email', 'trigger' => 'Every Monday', 'sends' => 1086],
        ];

        $totalSent = count($notifications);
        $failed = count(array_filter($notifications, fn ($n) => $n['status'] === 'failed'));
        $pending = count(array_filter($notifications, fn ($n) => $n['status'] === 'pending'));

        $stats = [
            'totalSent' => $totalSent,
            'todaySent' => $totalSent,
            'deliveryRate' => $totalSent > 0 ? round((($totalSent - $failed) / $totalSent) * 100, 1) : 98.4,
            'failed' => $failed,
            'pending' => $pending,
            'openRate' => 67.2,
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
        $pages = [
            ['id' => 1, 'title' => 'About Us', 'slug' => 'about-us', 'status' => 'published', 'updated' => '2025-06-15', 'views' => 3420],
            ['id' => 2, 'title' => 'FAQs', 'slug' => 'faqs', 'status' => 'published', 'updated' => '2025-06-20', 'views' => 5210],
            ['id' => 3, 'title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'status' => 'published', 'updated' => '2025-06-01', 'views' => 1820],
            ['id' => 4, 'title' => 'Terms of Service', 'slug' => 'terms-of-service', 'status' => 'published', 'updated' => '2025-06-01', 'views' => 1640],
            ['id' => 5, 'title' => 'Refund Policy', 'slug' => 'refund-policy', 'status' => 'published', 'updated' => '2025-06-01', 'views' => 980],
            ['id' => 6, 'title' => 'Food Safety', 'slug' => 'food-safety', 'status' => 'published', 'updated' => '2025-06-10', 'views' => 1240],
            ['id' => 7, 'title' => 'Partner With Us', 'slug' => 'partner-with-us', 'status' => 'published', 'updated' => '2025-06-12', 'views' => 760],
            ['id' => 8, 'title' => 'Contact Support', 'slug' => 'contact-support', 'status' => 'published', 'updated' => '2025-06-18', 'views' => 2890],
        ];

        $stats = [
            'totalPages' => 24,
            'published' => 22,
            'draft' => 2,
            'totalViews' => 18960,
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

        if (empty($kpis)) $kpis = [
            ['label' => 'Total Revenue', 'value' => 'SAR 487,320', 'delta' => '+15.4%', 'trend' => 'up', 'icon' => 'currency', 'color' => '#173327'],
            ['label' => 'Active Subscriptions', 'value' => '342', 'delta' => '+12', 'trend' => 'up', 'icon' => 'subscription', 'color' => '#033133'],
            ['label' => 'New Subscribers', 'value' => '48', 'delta' => '+8.2%', 'trend' => 'up', 'icon' => 'user-plus', 'color' => '#025C5F'],
            ['label' => 'Churn Rate', 'value' => '2.4%', 'delta' => '-0.3%', 'trend' => 'down', 'icon' => 'trending-down', 'color' => '#173327'],
            ['label' => 'Avg Order Value', 'value' => 'SAR 342', 'delta' => '+4.1%', 'trend' => 'up', 'icon' => 'shopping-cart', 'color' => '#6E7A25'],
            ['label' => 'Delivery On-Time', 'value' => '94.2%', 'delta' => '+1.8%', 'trend' => 'up', 'icon' => 'truck', 'color' => '#949B50'],
            ['label' => 'Payment Success', 'value' => '98.6%', 'delta' => '+0.2%', 'trend' => 'up', 'icon' => 'check-circle', 'color' => '#033133'],
            ['label' => 'Notification Delivery', 'value' => '98.4%', 'delta' => '+0.5%', 'trend' => 'up', 'icon' => 'bell', 'color' => '#033133'],
        ];

        $revenueTrend = !empty($revenueTrendApi) ? $revenueTrendApi : [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'current' => [285000, 312000, 358000, 389000, 421800, 487320],
            'previous' => [240000, 268000, 295000, 320000, 352000, 398000],
        ];

        $subscriptionFunnel = !empty($subscriptionFunnelApi) ? $subscriptionFunnelApi : [
            ['stage' => 'Site Visits', 'count' => 12480, 'pct' => 100],
            ['stage' => 'Trial Signups', 'count' => 3120, 'pct' => 25],
            ['stage' => 'Subscribed', 'count' => 1248, 'pct' => 10],
            ['stage' => 'Renewed', 'count' => 1086, 'pct' => 8.7],
        ];

        $deliverySla = !empty($deliverySlaApi) ? $deliverySlaApi : [
            ['zone' => 'Riyadh Central', 'onTime' => 96.2, 'total' => 412],
            ['zone' => 'Riyadh North', 'onTime' => 93.8, 'total' => 287],
            ['zone' => 'Riyadh South', 'onTime' => 91.5, 'total' => 198],
            ['zone' => 'Jeddah', 'onTime' => 88.4, 'total' => 142],
        ];

        $exceptions = !empty($exceptionsApi) ? $exceptionsApi : [
            ['id' => 'EXC-001', 'type' => 'Delivery Delay', 'zone' => 'Jeddah', 'severity' => 'warning', 'detail' => '3 deliveries delayed > 15 min', 'time' => '2025-06-30 10:15'],
            ['id' => 'EXC-002', 'type' => 'Payment Failure', 'zone' => 'Riyadh Central', 'severity' => 'critical', 'detail' => 'Bank Transfer declined for ORD-2397', 'time' => '2025-06-30 11:20'],
            ['id' => 'EXC-003', 'type' => 'Refund Request', 'zone' => 'Riyadh North', 'severity' => 'warning', 'detail' => 'Customer requested refund for ORD-2394', 'time' => '2025-06-30 09:45'],
            ['id' => 'EXC-004', 'type' => 'Stock Shortage', 'zone' => 'Riyadh South', 'severity' => 'info', 'detail' => 'Keto Salmon Salad low stock (12 left)', 'time' => '2025-06-30 08:30'],
        ];

        $operationalMetrics = !empty($operationalMetricsApi) ? $operationalMetricsApi : [
            ['label' => 'Pending Deliveries', 'value' => 18, 'color' => '#6E7A25'],
            ['label' => 'Failed Deliveries', 'value' => 3, 'color' => '#173327'],
            ['label' => 'Refund Requests Pending', 'value' => 5, 'color' => '#025C5F'],
            ['label' => 'Queue Lag', 'value' => '2.1s', 'color' => '#949B50'],
            ['label' => 'Campaign Throughput', 'value' => '420/min', 'color' => '#025C5F'],
        ];

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

        if (empty($kpis)) $kpis = [
            ['label' => 'Total Revenue', 'value' => 'SAR 487,320', 'delta' => '+15.4%', 'trend' => 'up', 'color' => '#173327'],
            ['label' => 'Captured Payments', 'value' => 'SAR 480,560', 'delta' => '+14.8%', 'trend' => 'up', 'color' => '#033133'],
            ['label' => 'Refund Volume', 'value' => 'SAR 6,760', 'delta' => '-2.1%', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Refund Ratio', 'value' => '1.4%', 'delta' => '-0.3%', 'trend' => 'down', 'color' => '#f9ac00'],
            ['label' => 'Payment Success Rate', 'value' => '98.6%', 'delta' => '+0.2%', 'trend' => 'up', 'color' => '#173327'],
            ['label' => 'Payment Failure Rate', 'value' => '1.4%', 'delta' => '-0.2%', 'trend' => 'down', 'color' => '#ef4444'],
        ];

        $revenueTrend = !empty($revenueTrendApi) ? $revenueTrendApi : [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'current' => [285000, 312000, 358000, 389000, 421800, 487320],
            'previous' => [240000, 268000, 295000, 320000, 352000, 398000],
        ];

        $paymentTrends = !empty($paymentTrendsApi) ? $paymentTrendsApi : [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'success' => [98.1, 98.3, 98.4, 98.5, 98.5, 98.6],
            'failure' => [1.9, 1.7, 1.6, 1.5, 1.5, 1.4],
        ];

        $refundVolume = !empty($refundVolumeApi) ? $refundVolumeApi : [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'amount' => [4200, 3800, 5100, 4600, 5200, 6760],
            'count' => [10, 9, 12, 11, 13, 16],
        ];

        $paymentMethods = !empty($paymentMethodsApi) ? $paymentMethodsApi : [
            ['method' => 'Credit Card', 'count' => 1248, 'volume' => 427080, 'pct' => 51.8, 'successRate' => 99.2],
            ['method' => 'Apple Pay', 'count' => 687, 'volume' => 234980, 'pct' => 28.5, 'successRate' => 99.5],
            ['method' => 'Mada', 'count' => 312, 'volume' => 106640, 'pct' => 13.0, 'successRate' => 97.8],
            ['method' => 'Bank Transfer', 'count' => 156, 'volume' => 53420, 'pct' => 6.5, 'successRate' => 96.1],
        ];

        $revenueByPlan = !empty($revenueByPlanApi) ? $revenueByPlanApi : [
            ['plan' => 'Weight Loss Pro', 'revenue' => 161280, 'pct' => 33.1, 'color' => '#173327'],
            ['plan' => 'Muscle Gain', 'revenue' => 118560, 'pct' => 24.3, 'color' => '#033133'],
            ['plan' => 'Keto Premium', 'revenue' => 89460, 'pct' => 18.4, 'color' => '#3b82f6'],
            ['plan' => 'Maintenance', 'revenue' => 74220, 'pct' => 15.2, 'color' => '#f9ac00'],
            ['plan' => 'Corporate', 'revenue' => 43800, 'pct' => 9.0, 'color' => '#8b5cf6'],
        ];

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

        if (empty($kpis)) $kpis = [
            ['label' => 'On-Time Rate', 'value' => '94.2%', 'delta' => '+1.8%', 'trend' => 'up', 'color' => '#173327'],
            ['label' => 'Total Deliveries', 'value' => '1,039', 'delta' => '+87', 'trend' => 'up', 'color' => '#033133'],
            ['label' => 'Failed Deliveries', 'value' => '12', 'delta' => '-3', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Avg Delivery Time', 'value' => '34 min', 'delta' => '-2 min', 'trend' => 'down', 'color' => '#f9ac00'],
        ];

        $onTimeTrend = !empty($onTimeTrendApi) ? $onTimeTrendApi : [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'rate' => [89.1, 90.5, 91.2, 92.8, 93.5, 94.2],
            'target' => [92, 92, 92, 92, 92, 92],
        ];

        $zonePerformance = !empty($zonePerformanceApi) ? $zonePerformanceApi : [
            ['zone' => 'Riyadh Central', 'onTime' => 96.2, 'total' => 412, 'avgTime' => '28 min', 'failed' => 3],
            ['zone' => 'Riyadh North', 'onTime' => 93.8, 'total' => 287, 'avgTime' => '32 min', 'failed' => 4],
            ['zone' => 'Riyadh South', 'onTime' => 91.5, 'total' => 198, 'avgTime' => '38 min', 'failed' => 2],
            ['zone' => 'Jeddah', 'onTime' => 88.4, 'total' => 142, 'avgTime' => '45 min', 'failed' => 3],
        ];

        $exceptionReasons = !empty($exceptionReasonsApi) ? $exceptionReasonsApi : [
            ['reason' => 'Customer Unavailable', 'count' => 5, 'pct' => 41.7],
            ['reason' => 'Traffic Delay', 'count' => 3, 'pct' => 25.0],
            ['reason' => 'Wrong Address', 'count' => 2, 'pct' => 16.7],
            ['reason' => 'Vehicle Breakdown', 'count' => 1, 'pct' => 8.3],
            ['reason' => 'Weather', 'count' => 1, 'pct' => 8.3],
        ];

        $driverProductivity = !empty($driverProductivityApi) ? $driverProductivityApi : [
            ['driver' => 'Yousef', 'deliveries' => 142, 'onTime' => 97.2, 'avgTime' => '26 min', 'rating' => 4.9],
            ['driver' => 'Hassan', 'deliveries' => 98, 'onTime' => 94.9, 'avgTime' => '31 min', 'rating' => 4.7],
            ['driver' => 'Ali', 'deliveries' => 76, 'onTime' => 92.1, 'avgTime' => '35 min', 'rating' => 4.5],
            ['driver' => 'Mahmoud', 'deliveries' => 54, 'onTime' => 89.8, 'avgTime' => '42 min', 'rating' => 4.3],
            ['driver' => 'Sami', 'deliveries' => 38, 'onTime' => 90.5, 'avgTime' => '39 min', 'rating' => 4.4],
        ];

        $deliveryHeatmap = !empty($deliveryHeatmapApi) ? $deliveryHeatmapApi : [
            ['day' => 'Mon', 'hours' => [12, 18, 28, 42, 38, 22, 14, 8]],
            ['day' => 'Tue', 'hours' => [14, 20, 32, 45, 40, 24, 16, 10]],
            ['day' => 'Wed', 'hours' => [15, 22, 30, 48, 42, 26, 18, 12]],
            ['day' => 'Thu', 'hours' => [16, 24, 34, 52, 46, 28, 20, 14]],
            ['day' => 'Fri', 'hours' => [8, 12, 18, 28, 24, 16, 10, 6]],
            ['day' => 'Sat', 'hours' => [18, 26, 38, 56, 48, 30, 22, 16]],
            ['day' => 'Sun', 'hours' => [14, 20, 28, 44, 38, 22, 14, 8]],
        ];
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

        if (empty($kpis)) $kpis = [
            ['label' => 'Active Subscriptions', 'value' => '342', 'delta' => '+12', 'trend' => 'up', 'color' => '#173327'],
            ['label' => 'New Subscribers', 'value' => '48', 'delta' => '+8.2%', 'trend' => 'up', 'color' => '#3b82f6'],
            ['label' => 'Churned', 'value' => '8', 'delta' => '-2', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Renewal Rate', 'value' => '87.1%', 'delta' => '+2.4%', 'trend' => 'up', 'color' => '#f9ac00'],
            ['label' => 'Churn Rate', 'value' => '2.4%', 'delta' => '-0.3%', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'MRR', 'value' => 'SAR 139,680', 'delta' => '+12.4%', 'trend' => 'up', 'color' => '#033133'],
        ];

        $newVsChurn = !empty($newVsChurnApi) ? $newVsChurnApi : [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'new' => [32, 38, 42, 45, 44, 48],
            'churn' => [12, 10, 9, 11, 10, 8],
        ];

        $renewalTrend = !empty($renewalTrendApi) ? $renewalTrendApi : [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'rate' => [82.1, 83.5, 84.8, 85.2, 86.0, 87.1],
        ];

        $planRanking = !empty($planRankingApi) ? $planRankingApi : [
            ['plan' => 'Weight Loss Pro', 'subscribers' => 128, 'revenue' => 53760, 'retention' => 91.4, 'churn' => 1.8, 'color' => '#173327'],
            ['plan' => 'Muscle Gain', 'subscribers' => 94, 'revenue' => 35720, 'retention' => 88.2, 'churn' => 2.5, 'color' => '#033133'],
            ['plan' => 'Maintenance', 'subscribers' => 76, 'revenue' => 22420, 'retention' => 85.5, 'churn' => 3.1, 'color' => '#f9ac00'],
            ['plan' => 'Keto Premium', 'subscribers' => 44, 'revenue' => 22440, 'retention' => 82.1, 'churn' => 3.8, 'color' => '#3b82f6'],
        ];

        $goalDistribution = !empty($goalDistributionApi) ? $goalDistributionApi : [
            ['goal' => 'Weight Loss', 'count' => 128, 'pct' => 37.4, 'color' => '#173327'],
            ['goal' => 'Muscle Gain', 'count' => 94, 'pct' => 27.5, 'color' => '#033133'],
            ['goal' => 'Maintenance', 'count' => 76, 'pct' => 22.2, 'color' => '#f9ac00'],
            ['goal' => 'Keto', 'count' => 44, 'pct' => 12.9, 'color' => '#3b82f6'],
        ];

        $corporateMetrics = !empty($corporateMetricsApi) ? $corporateMetricsApi : [
            ['label' => 'Active Corporate Accounts', 'value' => 12, 'color' => '#033133'],
            ['label' => 'Employee Enrollments', 'value' => 186, 'color' => '#173327'],
            ['label' => 'Corporate Utilization', 'value' => '72.4%', 'color' => '#f9ac00'],
            ['label' => 'Corporate Revenue Share', 'value' => '9.0%', 'color' => '#8b5cf6'],
        ];

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

        if (empty($kpis)) $kpis = [
            ['label' => 'Total Sent', 'value' => '18,420', 'delta' => '+1,240', 'trend' => 'up', 'color' => '#033133'],
            ['label' => 'Delivery Rate', 'value' => '98.4%', 'delta' => '+0.5%', 'trend' => 'up', 'color' => '#173327'],
            ['label' => 'Open Rate', 'value' => '67.2%', 'delta' => '+2.1%', 'trend' => 'up', 'color' => '#3b82f6'],
            ['label' => 'Failed Sends', 'value' => '12', 'delta' => '-4', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Campaign CTR', 'value' => '12.8%', 'delta' => '+1.4%', 'trend' => 'up', 'color' => '#f9ac00'],
            ['label' => 'Throughput', 'value' => '420/min', 'delta' => '+8%', 'trend' => 'up', 'color' => '#8b5cf6'],
        ];

        $sendVolumeByChannel = !empty($sendVolumeApi) ? $sendVolumeApi : [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'email' => [1820, 2140, 2480, 2720, 2980, 3120],
            'sms' => [980, 1120, 1340, 1480, 1620, 1780],
            'push' => [620, 780, 920, 1040, 1180, 1320],
            'whatsapp' => [280, 340, 420, 480, 540, 620],
        ];

        $channelMix = !empty($channelMixApi) ? $channelMixApi : [
            ['channel' => 'Email', 'count' => 14260, 'pct' => 51.6, 'color' => '#033133'],
            ['channel' => 'SMS', 'count' => 6320, 'pct' => 22.9, 'color' => '#173327'],
            ['channel' => 'Push', 'count' => 4280, 'pct' => 15.5, 'color' => '#f9ac00'],
            ['channel' => 'WhatsApp', 'count' => 2680, 'pct' => 9.7, 'color' => '#8b5cf6'],
        ];

        $campaignPerformance = !empty($campaignPerformanceApi) ? $campaignPerformanceApi : [
            ['name' => 'Summer Promo 2025', 'channel' => 'Email', 'sent' => 1248, 'opened' => 892, 'clicked' => 312, 'ctr' => 25.0, 'converted' => 48],
            ['name' => 'Ramadan Special', 'channel' => 'SMS', 'sent' => 2156, 'opened' => 0, 'clicked' => 287, 'ctr' => 13.3, 'converted' => 32],
            ['name' => 'Renewal Reminder', 'channel' => 'Push', 'sent' => 342, 'opened' => 0, 'clicked' => 89, 'ctr' => 26.0, 'converted' => 24],
            ['name' => 'Weekly Digest', 'channel' => 'Email', 'sent' => 1086, 'opened' => 742, 'clicked' => 198, 'ctr' => 18.2, 'converted' => 0],
            ['name' => 'New Menu Launch', 'channel' => 'WhatsApp', 'sent' => 820, 'opened' => 0, 'clicked' => 156, 'ctr' => 19.0, 'converted' => 18],
        ];

        $failedDiagnostics = !empty($failedDiagnosticsApi) ? $failedDiagnosticsApi : [
            ['id' => 'NF-001', 'channel' => 'Email', 'recipient' => 'user@mail.invalid', 'reason' => 'Bounced - Invalid Email', 'campaign' => 'Summer Promo', 'time' => '2025-06-30 09:15'],
            ['id' => 'NF-002', 'channel' => 'SMS', 'recipient' => '+966500000000', 'reason' => 'Carrier Rejected', 'campaign' => 'Ramadan Special', 'time' => '2025-06-30 08:42'],
            ['id' => 'NF-003', 'channel' => 'Push', 'recipient' => 'device_token_expired', 'reason' => 'Device Token Expired', 'campaign' => 'Renewal Reminder', 'time' => '2025-06-29 14:20'],
            ['id' => 'NF-004', 'channel' => 'Email', 'recipient' => 'bounce@mail.com', 'reason' => 'Bounced - Mailbox Full', 'campaign' => 'Weekly Digest', 'time' => '2025-06-29 10:05'],
            ['id' => 'NF-005', 'channel' => 'WhatsApp', 'recipient' => '+966511111111', 'reason' => 'Opt-Out', 'campaign' => 'New Menu Launch', 'time' => '2025-06-28 16:30'],
            ['id' => 'NF-006', 'channel' => 'SMS', 'recipient' => '+966522222222', 'reason' => 'Rate Limit Exceeded', 'campaign' => 'Ramadan Special', 'time' => '2025-06-28 11:15'],
        ];

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

        if (empty($kpis)) $kpis = [
            ['label' => 'Privileged Actions', 'value' => '1,248', 'delta' => '+82', 'trend' => 'up', 'color' => '#033133'],
            ['label' => 'Export Requests', 'value' => '47', 'delta' => '+12', 'trend' => 'up', 'color' => '#173327'],
            ['label' => 'Failed Access Attempts', 'value' => '3', 'delta' => '-1', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Compliance Score', 'value' => '98.2%', 'delta' => '+0.4%', 'trend' => 'up', 'color' => '#f9ac00'],
        ];

        $changeHotspots = !empty($changeHotspotsApi) ? $changeHotspotsApi : [
            ['module' => 'Subscriptions', 'changes' => 342, 'pct' => 27.4, 'color' => '#173327'],
            ['module' => 'Orders', 'changes' => 287, 'pct' => 23.0, 'color' => '#033133'],
            ['module' => 'Customers', 'changes' => 218, 'pct' => 17.5, 'color' => '#3b82f6'],
            ['module' => 'Payments', 'changes' => 156, 'pct' => 12.5, 'color' => '#f9ac00'],
            ['module' => 'Content', 'changes' => 124, 'pct' => 9.9, 'color' => '#8b5cf6'],
            ['module' => 'Settings', 'changes' => 121, 'pct' => 9.7, 'color' => '#ef4444'],
        ];

        $auditEvents = !empty($auditEventsApi) ? $auditEventsApi : [
            ['id' => 'AUD-001', 'actor' => 'admin@nutriomeals.com', 'action' => 'EXPORT_PDF', 'module' => 'Reports', 'detail' => 'Exported Revenue Summary report', 'ip' => '10.0.0.12', 'time' => '2025-06-30 14:20'],
            ['id' => 'AUD-002', 'actor' => 'admin@nutriomeals.com', 'action' => 'UPDATE', 'module' => 'Subscriptions', 'detail' => 'Modified Weight Loss Pro pricing 400→420', 'ip' => '10.0.0.12', 'time' => '2025-06-30 13:45'],
            ['id' => 'AUD-003', 'actor' => 'ops@nutriomeals.com', 'action' => 'DELETE', 'module' => 'Orders', 'detail' => 'Cancelled order ORD-2395', 'ip' => '10.0.0.18', 'time' => '2025-06-30 12:30'],
            ['id' => 'AUD-004', 'actor' => 'admin@nutriomeals.com', 'action' => 'EXPORT_EXCEL', 'module' => 'Reports', 'detail' => 'Exported Customer Growth report', 'ip' => '10.0.0.12', 'time' => '2025-06-30 11:15'],
            ['id' => 'AUD-005', 'actor' => 'finance@nutriomeals.com', 'action' => 'REFUND', 'module' => 'Payments', 'detail' => 'Processed refund for ORD-2394 (SAR 510)', 'ip' => '10.0.0.24', 'time' => '2025-06-30 10:00'],
            ['id' => 'AUD-006', 'actor' => 'admin@nutriomeals.com', 'action' => 'CREATE', 'module' => 'Meals', 'detail' => 'Created new meal: Avocado Toast Deluxe', 'ip' => '10.0.0.12', 'time' => '2025-06-30 09:30'],
            ['id' => 'AUD-007', 'actor' => 'marketing@nutriomeals.com', 'action' => 'SEND_CAMPAIGN', 'module' => 'Notifications', 'detail' => 'Launched Summer Promo 2025 campaign', 'ip' => '10.0.0.30', 'time' => '2025-06-30 08:45'],
            ['id' => 'AUD-008', 'actor' => 'admin@nutriomeals.com', 'action' => 'UPDATE_ROLE', 'module' => 'Settings', 'detail' => 'Changed user role: ops@nutriomeals.com → manager', 'ip' => '10.0.0.12', 'time' => '2025-06-29 16:20'],
        ];

        $exportHistory = !empty($exportHistoryApi) ? $exportHistoryApi : [
            ['id' => 'EXP-047', 'type' => 'Revenue Summary', 'format' => 'PDF', 'requested_by' => 'admin@nutriomeals.com', 'status' => 'completed', 'size' => '2.4 MB', 'time' => '2025-06-30 14:20'],
            ['id' => 'EXP-046', 'type' => 'Customer Growth', 'format' => 'Excel', 'requested_by' => 'admin@nutriomeals.com', 'status' => 'completed', 'size' => '1.8 MB', 'time' => '2025-06-30 11:15'],
            ['id' => 'EXP-045', 'type' => 'Delivery Efficiency', 'format' => 'Excel', 'requested_by' => 'ops@nutriomeals.com', 'status' => 'completed', 'size' => '3.2 MB', 'time' => '2025-06-29 15:30'],
            ['id' => 'EXP-044', 'type' => 'Churn Analysis', 'format' => 'PDF', 'requested_by' => 'admin@nutriomeals.com', 'status' => 'completed', 'size' => '1.6 MB', 'time' => '2025-06-28 10:45'],
            ['id' => 'EXP-043', 'type' => 'Audit Report', 'format' => 'PDF', 'requested_by' => 'admin@nutriomeals.com', 'status' => 'completed', 'size' => '4.1 MB', 'time' => '2025-06-27 09:00'],
            ['id' => 'EXP-042', 'type' => 'Campaign Performance', 'format' => 'Excel', 'requested_by' => 'marketing@nutriomeals.com', 'status' => 'failed', 'size' => '—', 'time' => '2025-06-26 14:15'],
        ];

        $lastUpdated = now()->format('Y-m-d H:i') . ' UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.audit', compact('kpis', 'changeHotspots', 'auditEvents', 'exportHistory', 'lastUpdated', 'timezone'));
    }
}
