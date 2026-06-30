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
}
