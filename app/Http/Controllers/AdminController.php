<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->isAdmin()) {
                abort(403, 'Access denied. Admin only.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'totalUsers' => \App\Models\User::count(),
            'newUsersThisWeek' => \App\Models\User::where('created_at', '>=', now()->subWeek())->count(),
            'totalRevenue' => 487320,
            'activeSubscriptions' => 342,
            'totalMeals' => 128,
            'successRate' => 98.6,
            'ordersToday' => 87,
            'deliveriesToday' => 64,
            'pendingPayments' => 12,
            'avgOrderValue' => 342,
            'monthlyRevenue' => 487320,
            'lastMonthRevenue' => 421800,
            'totalCustomers' => 1248,
            'newCustomersThisWeek' => 23,
            'churnRate' => 2.4,
            'retentionRate' => 94.2,
        ];

        // Demo revenue trend (last 14 days)
        $revenueTrend = [28400, 31200, 29800, 34500, 38900, 42100, 39800, 36700, 41300, 45200, 43800, 48900, 52400, 49800];

        // Demo orders trend (last 7 days)
        $ordersTrend = [42, 55, 48, 67, 73, 81, 87];

        // Demo plan distribution
        $planDistribution = [
            ['name' => 'Weight Loss', 'count' => 128, 'color' => '#259B00'],
            ['name' => 'Muscle Gain', 'count' => 94, 'color' => '#033133'],
            ['name' => 'Maintenance', 'count' => 76, 'color' => '#f9ac00'],
            ['name' => 'Keto', 'count' => 44, 'color' => '#3b82f6'],
        ];

        // Demo recent orders
        $recentOrders = [
            ['id' => 'ORD-2401', 'customer' => 'Ahmed Al-Saud', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered'],
            ['id' => 'ORD-2400', 'customer' => 'Sarah Al-Otaibi', 'plan' => 'Muscle Gain', 'amount' => 380, 'status' => 'en_route'],
            ['id' => 'ORD-2399', 'customer' => 'Khalid Al-Ghamdi', 'plan' => 'Maintenance', 'amount' => 295, 'status' => 'preparing'],
            ['id' => 'ORD-2398', 'customer' => 'Noura Al-Harbi', 'plan' => 'Keto Premium', 'amount' => 510, 'status' => 'delivered'],
            ['id' => 'ORD-2397', 'customer' => 'Faisal Al-Qahtani', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'pending'],
            ['id' => 'ORD-2396', 'customer' => 'Layla Al-Subaie', 'plan' => 'Muscle Gain', 'amount' => 380, 'status' => 'delivered'],
        ];

        // Demo top meals
        $topMeals = [
            ['name' => 'Grilled Chicken Bowl', 'orders' => 342, 'revenue' => 41200],
            ['name' => 'Quinoa Buddha Bowl', 'orders' => 287, 'revenue' => 32100],
            ['name' => 'Protein Breakfast Plate', 'orders' => 256, 'revenue' => 24800],
            ['name' => 'Keto Salmon Salad', 'orders' => 198, 'revenue' => 28400],
            ['name' => 'Beef & Rice Power Bowl', 'orders' => 174, 'revenue' => 22300],
        ];

        // Demo delivery zones
        $deliveryZones = [
            ['zone' => 'Riyadh Central', 'orders' => 34, 'drivers' => 8],
            ['zone' => 'Riyadh North', 'orders' => 22, 'drivers' => 5],
            ['zone' => 'Riyadh South', 'orders' => 18, 'drivers' => 4],
            ['zone' => 'Jeddah', 'orders' => 13, 'drivers' => 3],
        ];

        return view('admin.dashboard', compact('stats', 'revenueTrend', 'ordersTrend', 'planDistribution', 'recentOrders', 'topMeals', 'deliveryZones'));
    }

    public function customers()
    {
        $customers = [
            ['id' => 1, 'name' => 'Ahmed Al-Saud', 'email' => 'ahmed@example.com', 'phone' => '+966551234567', 'plan' => 'Weight Loss Pro', 'status' => 'active', 'orders' => 42, 'spent' => 17640, 'joined' => '2024-01-15'],
            ['id' => 2, 'name' => 'Sarah Al-Otaibi', 'email' => 'sarah@example.com', 'phone' => '+966552345678', 'plan' => 'Muscle Gain', 'status' => 'active', 'orders' => 38, 'spent' => 14440, 'joined' => '2024-02-03'],
            ['id' => 3, 'name' => 'Khalid Al-Ghamdi', 'email' => 'khalid@example.com', 'phone' => '+966553456789', 'plan' => 'Maintenance', 'status' => 'active', 'orders' => 25, 'spent' => 7375, 'joined' => '2024-03-20'],
            ['id' => 4, 'name' => 'Noura Al-Harbi', 'email' => 'noura@example.com', 'phone' => '+966554567890', 'plan' => 'Keto Premium', 'status' => 'paused', 'orders' => 19, 'spent' => 9690, 'joined' => '2024-04-10'],
            ['id' => 5, 'name' => 'Faisal Al-Qahtani', 'email' => 'faisal@example.com', 'phone' => '+966555678901', 'plan' => 'Weight Loss Pro', 'status' => 'active', 'orders' => 31, 'spent' => 13020, 'joined' => '2024-05-05'],
            ['id' => 6, 'name' => 'Layla Al-Subaie', 'email' => 'layla@example.com', 'phone' => '+966556789012', 'plan' => 'Muscle Gain', 'status' => 'active', 'orders' => 27, 'spent' => 10260, 'joined' => '2024-06-12'],
            ['id' => 7, 'name' => 'Omar Al-Dossari', 'email' => 'omar@example.com', 'phone' => '+966557890123', 'plan' => 'Maintenance', 'status' => 'cancelled', 'orders' => 8, 'spent' => 2360, 'joined' => '2024-07-01'],
            ['id' => 8, 'name' => 'Reem Al-Mutairi', 'email' => 'reem@example.com', 'phone' => '+966558901234', 'plan' => 'Keto Premium', 'status' => 'active', 'orders' => 22, 'spent' => 11220, 'joined' => '2024-08-15'],
        ];

        $stats = [
            'total' => 1248,
            'active' => 1086,
            'paused' => 98,
            'cancelled' => 64,
            'newThisWeek' => 23,
        ];

        return view('admin.customers', compact('customers', 'stats'));
    }

    public function subscriptions()
    {
        $plans = [
            ['id' => 1, 'name' => 'Weight Loss Pro', 'price' => 420, 'duration' => '4 weeks', 'meals' => 84, 'subscribers' => 128, 'status' => 'active', 'calories' => '1500-1800', 'color' => '#259B00'],
            ['id' => 2, 'name' => 'Muscle Gain', 'price' => 380, 'duration' => '4 weeks', 'meals' => 84, 'subscribers' => 94, 'status' => 'active', 'calories' => '2500-3000', 'color' => '#033133'],
            ['id' => 3, 'name' => 'Maintenance', 'price' => 295, 'duration' => '4 weeks', 'meals' => 84, 'subscribers' => 76, 'status' => 'active', 'calories' => '2000-2200', 'color' => '#f9ac00'],
            ['id' => 4, 'name' => 'Keto Premium', 'price' => 510, 'duration' => '4 weeks', 'meals' => 84, 'subscribers' => 44, 'status' => 'active', 'calories' => '1800-2000', 'color' => '#3b82f6'],
            ['id' => 5, 'name' => 'Vegan Fit', 'price' => 340, 'duration' => '4 weeks', 'meals' => 84, 'subscribers' => 0, 'status' => 'draft', 'calories' => '1600-1900', 'color' => '#8b5cf6'],
            ['id' => 6, 'name' => 'Athlete Performance', 'price' => 580, 'duration' => '4 weeks', 'meals' => 84, 'subscribers' => 0, 'status' => 'draft', 'calories' => '3000-3500', 'color' => '#ef4444'],
        ];

        $stats = [
            'total' => 6,
            'active' => 4,
            'draft' => 2,
            'totalSubscribers' => 342,
            'avgRevenue' => 409,
            'mrr' => 139680,
            'churnRate' => 2.4,
            'growthRate' => 12.4,
        ];

        return view('admin.subscriptions', compact('plans', 'stats'));
    }

    public function meals()
    {
        $meals = [
            ['id' => 1, 'name' => 'Grilled Chicken Bowl', 'category' => 'High Protein', 'calories' => 520, 'protein' => 45, 'carbs' => 38, 'fat' => 18, 'orders' => 342, 'rating' => 4.8, 'status' => 'active', 'image' => 'grilled-chicken-breast-rice-berry-vegetables-white-background_1428-2141.jpg'],
            ['id' => 2, 'name' => 'Quinoa Buddha Bowl', 'category' => 'Vegan', 'calories' => 480, 'protein' => 22, 'carbs' => 62, 'fat' => 16, 'orders' => 287, 'rating' => 4.6, 'status' => 'active', 'image' => 'healthy-buddha-bowl-with-sliced-meat-fresh-vegetables_9975-132258.jpg'],
            ['id' => 3, 'name' => 'Protein Breakfast Plate', 'category' => 'Breakfast', 'calories' => 410, 'protein' => 35, 'carbs' => 28, 'fat' => 14, 'orders' => 256, 'rating' => 4.7, 'status' => 'active', 'image' => 'healthy-protein-bowl-with-quinoa-avocado-kale-sweet-potato-poached-egg_9975-132760.jpg'],
            ['id' => 4, 'name' => 'Keto Salmon Salad', 'category' => 'Keto', 'calories' => 380, 'protein' => 32, 'carbs' => 8, 'fat' => 24, 'orders' => 198, 'rating' => 4.9, 'status' => 'active', 'image' => 'top-view-healthy-diet-salad-with-grilled-chicken-broccoli-cauliflower-tomato-lettuce-avocado-lettuce_141793-2438.jpg'],
            ['id' => 5, 'name' => 'Beef & Rice Power Bowl', 'category' => 'High Protein', 'calories' => 610, 'protein' => 48, 'carbs' => 52, 'fat' => 22, 'orders' => 174, 'rating' => 4.5, 'status' => 'active', 'image' => 'grilled-chicken-breast-rice-berry-vegetables-white-background_1428-2141.jpg'],
            ['id' => 6, 'name' => 'Avocado Toast Deluxe', 'category' => 'Breakfast', 'calories' => 340, 'protein' => 16, 'carbs' => 42, 'fat' => 14, 'orders' => 0, 'rating' => 0, 'status' => 'draft', 'image' => ''],
        ];

        $categories = [
            ['name' => 'High Protein', 'count' => 24, 'color' => '#259B00'],
            ['name' => 'Vegan', 'count' => 18, 'color' => '#8b5cf6'],
            ['name' => 'Keto', 'count' => 12, 'color' => '#3b82f6'],
            ['name' => 'Breakfast', 'count' => 16, 'color' => '#f9ac00'],
            ['name' => 'Maintenance', 'count' => 22, 'color' => '#033133'],
        ];

        $stats = [
            'total' => 128,
            'active' => 112,
            'draft' => 16,
            'categories' => 5,
            'avgRating' => 4.7,
            'totalOrders' => 1842,
        ];

        return view('admin.meals', compact('meals', 'categories', 'stats'));
    }

    public function orders()
    {
        $orders = [
            ['id' => 'ORD-2401', 'customer' => 'Ahmed Al-Saud', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-30', 'delivery' => '09:00-10:00'],
            ['id' => 'ORD-2400', 'customer' => 'Sarah Al-Otaibi', 'plan' => 'Muscle Gain', 'amount' => 380, 'status' => 'en_route', 'date' => '2025-06-30', 'delivery' => '10:00-11:00'],
            ['id' => 'ORD-2399', 'customer' => 'Khalid Al-Ghamdi', 'plan' => 'Maintenance', 'amount' => 295, 'status' => 'preparing', 'date' => '2025-06-30', 'delivery' => '11:00-12:00'],
            ['id' => 'ORD-2398', 'customer' => 'Noura Al-Harbi', 'plan' => 'Keto Premium', 'amount' => 510, 'status' => 'delivered', 'date' => '2025-06-29', 'delivery' => '08:00-09:00'],
            ['id' => 'ORD-2397', 'customer' => 'Faisal Al-Qahtani', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'pending', 'date' => '2025-06-30', 'delivery' => '14:00-15:00'],
            ['id' => 'ORD-2396', 'customer' => 'Layla Al-Subaie', 'plan' => 'Muscle Gain', 'amount' => 380, 'status' => 'delivered', 'date' => '2025-06-29', 'delivery' => '09:00-10:00'],
            ['id' => 'ORD-2395', 'customer' => 'Omar Al-Dossari', 'plan' => 'Maintenance', 'amount' => 295, 'status' => 'cancelled', 'date' => '2025-06-28', 'delivery' => '12:00-13:00'],
            ['id' => 'ORD-2394', 'customer' => 'Reem Al-Mutairi', 'plan' => 'Keto Premium', 'amount' => 510, 'status' => 'delivered', 'date' => '2025-06-29', 'delivery' => '10:00-11:00'],
        ];

        $stats = [
            'total' => 2401,
            'today' => 87,
            'pending' => 12,
            'delivered' => 2156,
            'revenue' => 821340,
        ];

        return view('admin.orders', compact('orders', 'stats'));
    }

    public function deliveries()
    {
        $deliveries = [
            ['id' => 'DLV-501', 'order' => 'ORD-2401', 'customer' => 'Ahmed Al-Saud', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'time' => '09:15', 'eta' => 'On time'],
            ['id' => 'DLV-502', 'order' => 'ORD-2400', 'customer' => 'Sarah Al-Otaibi', 'zone' => 'Riyadh North', 'driver' => 'Hassan', 'status' => 'en_route', 'time' => '10:30', 'eta' => '5 min'],
            ['id' => 'DLV-503', 'order' => 'ORD-2399', 'customer' => 'Khalid Al-Ghamdi', 'zone' => 'Riyadh South', 'driver' => 'Ali', 'status' => 'preparing', 'time' => '11:00', 'eta' => '30 min'],
            ['id' => 'DLV-504', 'order' => 'ORD-2398', 'customer' => 'Noura Al-Harbi', 'zone' => 'Jeddah', 'driver' => 'Mahmoud', 'status' => 'delivered', 'time' => '08:20', 'eta' => 'On time'],
            ['id' => 'DLV-505', 'order' => 'ORD-2397', 'customer' => 'Faisal Al-Qahtani', 'zone' => 'Riyadh Central', 'driver' => 'Unassigned', 'status' => 'scheduled', 'time' => '14:00', 'eta' => 'Pending'],
            ['id' => 'DLV-506', 'order' => 'ORD-2396', 'customer' => 'Layla Al-Subaie', 'zone' => 'Riyadh North', 'driver' => 'Yousef', 'status' => 'delivered', 'time' => '09:45', 'eta' => 'On time'],
        ];

        $zones = [
            ['name' => 'Riyadh Central', 'orders' => 34, 'drivers' => 8, 'completed' => 28],
            ['name' => 'Riyadh North', 'orders' => 22, 'drivers' => 5, 'completed' => 18],
            ['name' => 'Riyadh South', 'orders' => 18, 'drivers' => 4, 'completed' => 12],
            ['name' => 'Jeddah', 'orders' => 13, 'drivers' => 3, 'completed' => 6],
        ];

        $stats = [
            'total' => 64,
            'delivered' => 46,
            'enRoute' => 8,
            'preparing' => 6,
            'scheduled' => 4,
            'onTimeRate' => 94.2,
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

    public function notifications()
    {
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

        $templates = [
            ['name' => 'Welcome Email', 'type' => 'email', 'trigger' => 'New registration', 'sends' => 1248],
            ['name' => 'Delivery Notification', 'type' => 'sms', 'trigger' => 'Order dispatched', 'sends' => 2156],
            ['name' => 'Payment Receipt', 'type' => 'email', 'trigger' => 'Payment completed', 'sends' => 2104],
            ['name' => 'Renewal Reminder', 'type' => 'push', 'trigger' => '3 days before renewal', 'sends' => 342],
            ['name' => 'Weekly Digest', 'type' => 'email', 'trigger' => 'Every Monday', 'sends' => 1086],
        ];

        $stats = [
            'totalSent' => 18420,
            'todaySent' => 342,
            'deliveryRate' => 98.4,
            'failed' => 12,
            'pending' => 8,
            'openRate' => 67.2,
        ];

        return view('admin.notifications', compact('notifications', 'templates', 'stats'));
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

    public function reportDashboard()
    {
        $kpis = [
            ['label' => 'Total Revenue', 'value' => 'SAR 487,320', 'delta' => '+15.4%', 'trend' => 'up', 'icon' => 'currency', 'color' => '#259B00'],
            ['label' => 'Active Subscriptions', 'value' => '342', 'delta' => '+12', 'trend' => 'up', 'icon' => 'subscription', 'color' => '#033133'],
            ['label' => 'New Subscribers', 'value' => '48', 'delta' => '+8.2%', 'trend' => 'up', 'icon' => 'user-plus', 'color' => '#025C5F'],
            ['label' => 'Churn Rate', 'value' => '2.4%', 'delta' => '-0.3%', 'trend' => 'down', 'icon' => 'trending-down', 'color' => '#173327'],
            ['label' => 'Avg Order Value', 'value' => 'SAR 342', 'delta' => '+4.1%', 'trend' => 'up', 'icon' => 'shopping-cart', 'color' => '#6E7A25'],
            ['label' => 'Delivery On-Time', 'value' => '94.2%', 'delta' => '+1.8%', 'trend' => 'up', 'icon' => 'truck', 'color' => '#949B50'],
            ['label' => 'Payment Success', 'value' => '98.6%', 'delta' => '+0.2%', 'trend' => 'up', 'icon' => 'check-circle', 'color' => '#259B00'],
            ['label' => 'Notification Delivery', 'value' => '98.4%', 'delta' => '+0.5%', 'trend' => 'up', 'icon' => 'bell', 'color' => '#033133'],
        ];

        $revenueTrend = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'current' => [285000, 312000, 358000, 389000, 421800, 487320],
            'previous' => [240000, 268000, 295000, 320000, 352000, 398000],
        ];

        $subscriptionFunnel = [
            ['stage' => 'Site Visits', 'count' => 12480, 'pct' => 100],
            ['stage' => 'Trial Signups', 'count' => 3120, 'pct' => 25],
            ['stage' => 'Subscribed', 'count' => 1248, 'pct' => 10],
            ['stage' => 'Renewed', 'count' => 1086, 'pct' => 8.7],
        ];

        $deliverySla = [
            ['zone' => 'Riyadh Central', 'onTime' => 96.2, 'total' => 412],
            ['zone' => 'Riyadh North', 'onTime' => 93.8, 'total' => 287],
            ['zone' => 'Riyadh South', 'onTime' => 91.5, 'total' => 198],
            ['zone' => 'Jeddah', 'onTime' => 88.4, 'total' => 142],
        ];

        $exceptions = [
            ['id' => 'EXC-001', 'type' => 'Delivery Delay', 'zone' => 'Jeddah', 'severity' => 'warning', 'detail' => '3 deliveries delayed > 15 min', 'time' => '2025-06-30 10:15'],
            ['id' => 'EXC-002', 'type' => 'Payment Failure', 'zone' => 'Riyadh Central', 'severity' => 'critical', 'detail' => 'Bank Transfer declined for ORD-2397', 'time' => '2025-06-30 11:20'],
            ['id' => 'EXC-003', 'type' => 'Refund Request', 'zone' => 'Riyadh North', 'severity' => 'warning', 'detail' => 'Customer requested refund for ORD-2394', 'time' => '2025-06-30 09:45'],
            ['id' => 'EXC-004', 'type' => 'Stock Shortage', 'zone' => 'Riyadh South', 'severity' => 'info', 'detail' => 'Keto Salmon Salad low stock (12 left)', 'time' => '2025-06-30 08:30'],
        ];

        $operationalMetrics = [
            ['label' => 'Pending Deliveries', 'value' => 18, 'color' => '#6E7A25'],
            ['label' => 'Failed Deliveries', 'value' => 3, 'color' => '#173327'],
            ['label' => 'Refund Requests Pending', 'value' => 5, 'color' => '#025C5F'],
            ['label' => 'Queue Lag', 'value' => '2.1s', 'color' => '#949B50'],
            ['label' => 'Campaign Throughput', 'value' => '420/min', 'color' => '#259B00'],
        ];

        $lastUpdated = '2025-06-30 14:32 UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.dashboard', compact('kpis', 'revenueTrend', 'subscriptionFunnel', 'deliverySla', 'exceptions', 'operationalMetrics', 'lastUpdated', 'timezone'));
    }

    public function reportRevenue()
    {
        $kpis = [
            ['label' => 'Total Revenue', 'value' => 'SAR 487,320', 'delta' => '+15.4%', 'trend' => 'up', 'color' => '#259B00'],
            ['label' => 'Captured Payments', 'value' => 'SAR 480,560', 'delta' => '+14.8%', 'trend' => 'up', 'color' => '#033133'],
            ['label' => 'Refund Volume', 'value' => 'SAR 6,760', 'delta' => '-2.1%', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Refund Ratio', 'value' => '1.4%', 'delta' => '-0.3%', 'trend' => 'down', 'color' => '#f9ac00'],
            ['label' => 'Payment Success Rate', 'value' => '98.6%', 'delta' => '+0.2%', 'trend' => 'up', 'color' => '#259B00'],
            ['label' => 'Payment Failure Rate', 'value' => '1.4%', 'delta' => '-0.2%', 'trend' => 'down', 'color' => '#ef4444'],
        ];

        $revenueTrend = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'current' => [285000, 312000, 358000, 389000, 421800, 487320],
            'previous' => [240000, 268000, 295000, 320000, 352000, 398000],
        ];

        $paymentTrends = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'success' => [98.1, 98.3, 98.4, 98.5, 98.5, 98.6],
            'failure' => [1.9, 1.7, 1.6, 1.5, 1.5, 1.4],
        ];

        $refundVolume = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'amount' => [4200, 3800, 5100, 4600, 5200, 6760],
            'count' => [10, 9, 12, 11, 13, 16],
        ];

        $paymentMethods = [
            ['method' => 'Credit Card', 'count' => 1248, 'volume' => 427080, 'pct' => 51.8, 'successRate' => 99.2],
            ['method' => 'Apple Pay', 'count' => 687, 'volume' => 234980, 'pct' => 28.5, 'successRate' => 99.5],
            ['method' => 'Mada', 'count' => 312, 'volume' => 106640, 'pct' => 13.0, 'successRate' => 97.8],
            ['method' => 'Bank Transfer', 'count' => 156, 'volume' => 53420, 'pct' => 6.5, 'successRate' => 96.1],
        ];

        $revenueByPlan = [
            ['plan' => 'Weight Loss Pro', 'revenue' => 161280, 'pct' => 33.1, 'color' => '#259B00'],
            ['plan' => 'Muscle Gain', 'revenue' => 118560, 'pct' => 24.3, 'color' => '#033133'],
            ['plan' => 'Keto Premium', 'revenue' => 89460, 'pct' => 18.4, 'color' => '#3b82f6'],
            ['plan' => 'Maintenance', 'revenue' => 74220, 'pct' => 15.2, 'color' => '#f9ac00'],
            ['plan' => 'Corporate', 'revenue' => 43800, 'pct' => 9.0, 'color' => '#8b5cf6'],
        ];

        $lastUpdated = '2025-06-30 14:32 UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.revenue', compact('kpis', 'revenueTrend', 'paymentTrends', 'refundVolume', 'paymentMethods', 'revenueByPlan', 'lastUpdated', 'timezone'));
    }

    public function reportDelivery()
    {
        $kpis = [
            ['label' => 'On-Time Rate', 'value' => '94.2%', 'delta' => '+1.8%', 'trend' => 'up', 'color' => '#259B00'],
            ['label' => 'Total Deliveries', 'value' => '1,039', 'delta' => '+87', 'trend' => 'up', 'color' => '#033133'],
            ['label' => 'Failed Deliveries', 'value' => '12', 'delta' => '-3', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Avg Delivery Time', 'value' => '34 min', 'delta' => '-2 min', 'trend' => 'down', 'color' => '#f9ac00'],
        ];

        $onTimeTrend = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'rate' => [89.1, 90.5, 91.2, 92.8, 93.5, 94.2],
            'target' => [92, 92, 92, 92, 92, 92],
        ];

        $zonePerformance = [
            ['zone' => 'Riyadh Central', 'onTime' => 96.2, 'total' => 412, 'avgTime' => '28 min', 'failed' => 3],
            ['zone' => 'Riyadh North', 'onTime' => 93.8, 'total' => 287, 'avgTime' => '32 min', 'failed' => 4],
            ['zone' => 'Riyadh South', 'onTime' => 91.5, 'total' => 198, 'avgTime' => '38 min', 'failed' => 2],
            ['zone' => 'Jeddah', 'onTime' => 88.4, 'total' => 142, 'avgTime' => '45 min', 'failed' => 3],
        ];

        $exceptionReasons = [
            ['reason' => 'Customer Unavailable', 'count' => 5, 'pct' => 41.7],
            ['reason' => 'Traffic Delay', 'count' => 3, 'pct' => 25.0],
            ['reason' => 'Wrong Address', 'count' => 2, 'pct' => 16.7],
            ['reason' => 'Vehicle Breakdown', 'count' => 1, 'pct' => 8.3],
            ['reason' => 'Weather', 'count' => 1, 'pct' => 8.3],
        ];

        $driverProductivity = [
            ['driver' => 'Yousef', 'deliveries' => 142, 'onTime' => 97.2, 'avgTime' => '26 min', 'rating' => 4.9],
            ['driver' => 'Hassan', 'deliveries' => 98, 'onTime' => 94.9, 'avgTime' => '31 min', 'rating' => 4.7],
            ['driver' => 'Ali', 'deliveries' => 76, 'onTime' => 92.1, 'avgTime' => '35 min', 'rating' => 4.5],
            ['driver' => 'Mahmoud', 'deliveries' => 54, 'onTime' => 89.8, 'avgTime' => '42 min', 'rating' => 4.3],
            ['driver' => 'Sami', 'deliveries' => 38, 'onTime' => 90.5, 'avgTime' => '39 min', 'rating' => 4.4],
        ];

        $deliveryHeatmap = [
            ['day' => 'Mon', 'hours' => [12, 18, 28, 42, 38, 22, 14, 8]],
            ['day' => 'Tue', 'hours' => [14, 20, 32, 45, 40, 24, 16, 10]],
            ['day' => 'Wed', 'hours' => [15, 22, 30, 48, 42, 26, 18, 12]],
            ['day' => 'Thu', 'hours' => [16, 24, 34, 52, 46, 28, 20, 14]],
            ['day' => 'Fri', 'hours' => [8, 12, 18, 28, 24, 16, 10, 6]],
            ['day' => 'Sat', 'hours' => [18, 26, 38, 56, 48, 30, 22, 16]],
            ['day' => 'Sun', 'hours' => [14, 20, 28, 44, 38, 22, 14, 8]],
        ];
        $heatmapHours = ['06-08', '08-10', '10-12', '12-14', '14-16', '16-18', '18-20', '20-22'];

        $lastUpdated = '2025-06-30 14:32 UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.delivery', compact('kpis', 'onTimeTrend', 'zonePerformance', 'exceptionReasons', 'driverProductivity', 'deliveryHeatmap', 'heatmapHours', 'lastUpdated', 'timezone'));
    }

    public function reportSubscriptions()
    {
        $kpis = [
            ['label' => 'Active Subscriptions', 'value' => '342', 'delta' => '+12', 'trend' => 'up', 'color' => '#259B00'],
            ['label' => 'New Subscribers', 'value' => '48', 'delta' => '+8.2%', 'trend' => 'up', 'color' => '#3b82f6'],
            ['label' => 'Churned', 'value' => '8', 'delta' => '-2', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Renewal Rate', 'value' => '87.1%', 'delta' => '+2.4%', 'trend' => 'up', 'color' => '#f9ac00'],
            ['label' => 'Churn Rate', 'value' => '2.4%', 'delta' => '-0.3%', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'MRR', 'value' => 'SAR 139,680', 'delta' => '+12.4%', 'trend' => 'up', 'color' => '#033133'],
        ];

        $newVsChurn = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'new' => [32, 38, 42, 45, 44, 48],
            'churn' => [12, 10, 9, 11, 10, 8],
        ];

        $renewalTrend = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'rate' => [82.1, 83.5, 84.8, 85.2, 86.0, 87.1],
        ];

        $planRanking = [
            ['plan' => 'Weight Loss Pro', 'subscribers' => 128, 'revenue' => 53760, 'retention' => 91.4, 'churn' => 1.8, 'color' => '#259B00'],
            ['plan' => 'Muscle Gain', 'subscribers' => 94, 'revenue' => 35720, 'retention' => 88.2, 'churn' => 2.5, 'color' => '#033133'],
            ['plan' => 'Maintenance', 'subscribers' => 76, 'revenue' => 22420, 'retention' => 85.5, 'churn' => 3.1, 'color' => '#f9ac00'],
            ['plan' => 'Keto Premium', 'subscribers' => 44, 'revenue' => 22440, 'retention' => 82.1, 'churn' => 3.8, 'color' => '#3b82f6'],
        ];

        $goalDistribution = [
            ['goal' => 'Weight Loss', 'count' => 128, 'pct' => 37.4, 'color' => '#259B00'],
            ['goal' => 'Muscle Gain', 'count' => 94, 'pct' => 27.5, 'color' => '#033133'],
            ['goal' => 'Maintenance', 'count' => 76, 'pct' => 22.2, 'color' => '#f9ac00'],
            ['goal' => 'Keto', 'count' => 44, 'pct' => 12.9, 'color' => '#3b82f6'],
        ];

        $corporateMetrics = [
            ['label' => 'Active Corporate Accounts', 'value' => 12, 'color' => '#033133'],
            ['label' => 'Employee Enrollments', 'value' => 186, 'color' => '#259B00'],
            ['label' => 'Corporate Utilization', 'value' => '72.4%', 'color' => '#f9ac00'],
            ['label' => 'Corporate Revenue Share', 'value' => '9.0%', 'color' => '#8b5cf6'],
        ];

        $lastUpdated = '2025-06-30 14:32 UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.subscriptions', compact('kpis', 'newVsChurn', 'renewalTrend', 'planRanking', 'goalDistribution', 'corporateMetrics', 'lastUpdated', 'timezone'));
    }

    public function reportNotifications()
    {
        $kpis = [
            ['label' => 'Total Sent', 'value' => '18,420', 'delta' => '+1,240', 'trend' => 'up', 'color' => '#033133'],
            ['label' => 'Delivery Rate', 'value' => '98.4%', 'delta' => '+0.5%', 'trend' => 'up', 'color' => '#259B00'],
            ['label' => 'Open Rate', 'value' => '67.2%', 'delta' => '+2.1%', 'trend' => 'up', 'color' => '#3b82f6'],
            ['label' => 'Failed Sends', 'value' => '12', 'delta' => '-4', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Campaign CTR', 'value' => '12.8%', 'delta' => '+1.4%', 'trend' => 'up', 'color' => '#f9ac00'],
            ['label' => 'Throughput', 'value' => '420/min', 'delta' => '+8%', 'trend' => 'up', 'color' => '#8b5cf6'],
        ];

        $sendVolumeByChannel = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'email' => [1820, 2140, 2480, 2720, 2980, 3120],
            'sms' => [980, 1120, 1340, 1480, 1620, 1780],
            'push' => [620, 780, 920, 1040, 1180, 1320],
            'whatsapp' => [280, 340, 420, 480, 540, 620],
        ];

        $channelMix = [
            ['channel' => 'Email', 'count' => 14260, 'pct' => 51.6, 'color' => '#033133'],
            ['channel' => 'SMS', 'count' => 6320, 'pct' => 22.9, 'color' => '#259B00'],
            ['channel' => 'Push', 'count' => 4280, 'pct' => 15.5, 'color' => '#f9ac00'],
            ['channel' => 'WhatsApp', 'count' => 2680, 'pct' => 9.7, 'color' => '#8b5cf6'],
        ];

        $campaignPerformance = [
            ['name' => 'Summer Promo 2025', 'channel' => 'Email', 'sent' => 1248, 'opened' => 892, 'clicked' => 312, 'ctr' => 25.0, 'converted' => 48],
            ['name' => 'Ramadan Special', 'channel' => 'SMS', 'sent' => 2156, 'opened' => 0, 'clicked' => 287, 'ctr' => 13.3, 'converted' => 32],
            ['name' => 'Renewal Reminder', 'channel' => 'Push', 'sent' => 342, 'opened' => 0, 'clicked' => 89, 'ctr' => 26.0, 'converted' => 24],
            ['name' => 'Weekly Digest', 'channel' => 'Email', 'sent' => 1086, 'opened' => 742, 'clicked' => 198, 'ctr' => 18.2, 'converted' => 0],
            ['name' => 'New Menu Launch', 'channel' => 'WhatsApp', 'sent' => 820, 'opened' => 0, 'clicked' => 156, 'ctr' => 19.0, 'converted' => 18],
        ];

        $failedDiagnostics = [
            ['id' => 'NF-001', 'channel' => 'Email', 'recipient' => 'user@mail.invalid', 'reason' => 'Bounced - Invalid Email', 'campaign' => 'Summer Promo', 'time' => '2025-06-30 09:15'],
            ['id' => 'NF-002', 'channel' => 'SMS', 'recipient' => '+966500000000', 'reason' => 'Carrier Rejected', 'campaign' => 'Ramadan Special', 'time' => '2025-06-30 08:42'],
            ['id' => 'NF-003', 'channel' => 'Push', 'recipient' => 'device_token_expired', 'reason' => 'Device Token Expired', 'campaign' => 'Renewal Reminder', 'time' => '2025-06-29 14:20'],
            ['id' => 'NF-004', 'channel' => 'Email', 'recipient' => 'bounce@mail.com', 'reason' => 'Bounced - Mailbox Full', 'campaign' => 'Weekly Digest', 'time' => '2025-06-29 10:05'],
            ['id' => 'NF-005', 'channel' => 'WhatsApp', 'recipient' => '+966511111111', 'reason' => 'Opt-Out', 'campaign' => 'New Menu Launch', 'time' => '2025-06-28 16:30'],
            ['id' => 'NF-006', 'channel' => 'SMS', 'recipient' => '+966522222222', 'reason' => 'Rate Limit Exceeded', 'campaign' => 'Ramadan Special', 'time' => '2025-06-28 11:15'],
        ];

        $lastUpdated = '2025-06-30 14:32 UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.notifications', compact('kpis', 'sendVolumeByChannel', 'channelMix', 'campaignPerformance', 'failedDiagnostics', 'lastUpdated', 'timezone'));
    }

    public function reportAudit()
    {
        $kpis = [
            ['label' => 'Privileged Actions', 'value' => '1,248', 'delta' => '+82', 'trend' => 'up', 'color' => '#033133'],
            ['label' => 'Export Requests', 'value' => '47', 'delta' => '+12', 'trend' => 'up', 'color' => '#259B00'],
            ['label' => 'Failed Access Attempts', 'value' => '3', 'delta' => '-1', 'trend' => 'down', 'color' => '#ef4444'],
            ['label' => 'Compliance Score', 'value' => '98.2%', 'delta' => '+0.4%', 'trend' => 'up', 'color' => '#f9ac00'],
        ];

        $changeHotspots = [
            ['module' => 'Subscriptions', 'changes' => 342, 'pct' => 27.4, 'color' => '#259B00'],
            ['module' => 'Orders', 'changes' => 287, 'pct' => 23.0, 'color' => '#033133'],
            ['module' => 'Customers', 'changes' => 218, 'pct' => 17.5, 'color' => '#3b82f6'],
            ['module' => 'Payments', 'changes' => 156, 'pct' => 12.5, 'color' => '#f9ac00'],
            ['module' => 'Content', 'changes' => 124, 'pct' => 9.9, 'color' => '#8b5cf6'],
            ['module' => 'Settings', 'changes' => 121, 'pct' => 9.7, 'color' => '#ef4444'],
        ];

        $auditEvents = [
            ['id' => 'AUD-001', 'actor' => 'admin@nutriomeals.com', 'action' => 'EXPORT_PDF', 'module' => 'Reports', 'detail' => 'Exported Revenue Summary report', 'ip' => '10.0.0.12', 'time' => '2025-06-30 14:20'],
            ['id' => 'AUD-002', 'actor' => 'admin@nutriomeals.com', 'action' => 'UPDATE', 'module' => 'Subscriptions', 'detail' => 'Modified Weight Loss Pro pricing 400→420', 'ip' => '10.0.0.12', 'time' => '2025-06-30 13:45'],
            ['id' => 'AUD-003', 'actor' => 'ops@nutriomeals.com', 'action' => 'DELETE', 'module' => 'Orders', 'detail' => 'Cancelled order ORD-2395', 'ip' => '10.0.0.18', 'time' => '2025-06-30 12:30'],
            ['id' => 'AUD-004', 'actor' => 'admin@nutriomeals.com', 'action' => 'EXPORT_EXCEL', 'module' => 'Reports', 'detail' => 'Exported Customer Growth report', 'ip' => '10.0.0.12', 'time' => '2025-06-30 11:15'],
            ['id' => 'AUD-005', 'actor' => 'finance@nutriomeals.com', 'action' => 'REFUND', 'module' => 'Payments', 'detail' => 'Processed refund for ORD-2394 (SAR 510)', 'ip' => '10.0.0.24', 'time' => '2025-06-30 10:00'],
            ['id' => 'AUD-006', 'actor' => 'admin@nutriomeals.com', 'action' => 'CREATE', 'module' => 'Meals', 'detail' => 'Created new meal: Avocado Toast Deluxe', 'ip' => '10.0.0.12', 'time' => '2025-06-30 09:30'],
            ['id' => 'AUD-007', 'actor' => 'marketing@nutriomeals.com', 'action' => 'SEND_CAMPAIGN', 'module' => 'Notifications', 'detail' => 'Launched Summer Promo 2025 campaign', 'ip' => '10.0.0.30', 'time' => '2025-06-30 08:45'],
            ['id' => 'AUD-008', 'actor' => 'admin@nutriomeals.com', 'action' => 'UPDATE_ROLE', 'module' => 'Settings', 'detail' => 'Changed user role: ops@nutriomeals.com → manager', 'ip' => '10.0.0.12', 'time' => '2025-06-29 16:20'],
        ];

        $exportHistory = [
            ['id' => 'EXP-047', 'type' => 'Revenue Summary', 'format' => 'PDF', 'requested_by' => 'admin@nutriomeals.com', 'status' => 'completed', 'size' => '2.4 MB', 'time' => '2025-06-30 14:20'],
            ['id' => 'EXP-046', 'type' => 'Customer Growth', 'format' => 'Excel', 'requested_by' => 'admin@nutriomeals.com', 'status' => 'completed', 'size' => '1.8 MB', 'time' => '2025-06-30 11:15'],
            ['id' => 'EXP-045', 'type' => 'Delivery Efficiency', 'format' => 'Excel', 'requested_by' => 'ops@nutriomeals.com', 'status' => 'completed', 'size' => '3.2 MB', 'time' => '2025-06-29 15:30'],
            ['id' => 'EXP-044', 'type' => 'Churn Analysis', 'format' => 'PDF', 'requested_by' => 'admin@nutriomeals.com', 'status' => 'completed', 'size' => '1.6 MB', 'time' => '2025-06-28 10:45'],
            ['id' => 'EXP-043', 'type' => 'Audit Report', 'format' => 'PDF', 'requested_by' => 'admin@nutriomeals.com', 'status' => 'completed', 'size' => '4.1 MB', 'time' => '2025-06-27 09:00'],
            ['id' => 'EXP-042', 'type' => 'Campaign Performance', 'format' => 'Excel', 'requested_by' => 'marketing@nutriomeals.com', 'status' => 'failed', 'size' => '—', 'time' => '2025-06-26 14:15'],
        ];

        $lastUpdated = '2025-06-30 14:32 UTC+3';
        $timezone = 'Asia/Riyadh (UTC+3)';

        return view('admin.reports.audit', compact('kpis', 'changeHotspots', 'auditEvents', 'exportHistory', 'lastUpdated', 'timezone'));
    }
}
