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

    public function plans()
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
        ];

        return view('admin.plans', compact('plans', 'stats'));
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

    public function reports()
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

        return view('admin.reports', compact('reports', 'chartData', 'stats'));
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
}
