<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Api\AuthApiService;
use App\Services\Api\HasApiData;

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
        });
    }

    public function dashboard()
    {
        $authApi = app(AuthApiService::class);
        $user = $authApi->user();

        $stats = [
            'activePlan' => 'Weight Loss Pro',
            'planPrice' => 420,
            'planRenewal' => 'Jul 15, 2025',
            'mealsThisWeek' => 18,
            'mealsTotal' => 84,
            'totalOrders' => 42,
            'dailyCalories' => 1650,
            'calorieTarget' => 1800,
            'proteinTarget' => 140,
            'proteinToday' => 95,
            'carbsTarget' => 200,
            'carbsToday' => 165,
            'fatTarget' => 55,
            'fatToday' => 38,
            'streakDays' => 28,
            'nextDelivery' => 'Tomorrow, 09:00 - 10:00',
            'nextMeal' => 'Grilled Chicken Bowl',
            'weightStart' => 82.5,
            'weightCurrent' => 78.2,
            'weightGoal' => 75.0,
        ];

        $weeklyProgress = [
            ['day' => 'Mon', 'calories' => 1620, 'target' => 1800],
            ['day' => 'Tue', 'calories' => 1750, 'target' => 1800],
            ['day' => 'Wed', 'calories' => 1580, 'target' => 1800],
            ['day' => 'Thu', 'calories' => 1820, 'target' => 1800],
            ['day' => 'Fri', 'calories' => 1700, 'target' => 1800],
            ['day' => 'Sat', 'calories' => 1650, 'target' => 1800],
            ['day' => 'Sun', 'calories' => 1690, 'target' => 1800],
        ];

        $upcomingMeals = [
            ['name' => 'Grilled Chicken Bowl', 'time' => 'Today · Lunch', 'calories' => 520, 'image' => 'grilled-chicken-breast-rice-berry-vegetables-white-background_1428-2141.jpg'],
            ['name' => 'Quinoa Buddha Bowl', 'time' => 'Today · Dinner', 'calories' => 480, 'image' => 'healthy-buddha-bowl-with-sliced-meat-fresh-vegetables_9975-132258.jpg'],
            ['name' => 'Protein Breakfast Plate', 'time' => 'Tomorrow · Breakfast', 'calories' => 410, 'image' => 'healthy-protein-bowl-with-quinoa-avocado-kale-sweet-potato-poached-egg_9975-132760.jpg'],
        ];

        $recentOrders = [
            ['id' => 'ORD-2401', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-30'],
            ['id' => 'ORD-2387', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-29'],
            ['id' => 'ORD-2372', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-28'],
            ['id' => 'ORD-2358', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-27'],
        ];

        return view('user.dashboard', compact('user', 'stats', 'weeklyProgress', 'upcomingMeals', 'recentOrders'));
    }

    public function subscriptions()
    {
        $activePlan = [
            'name' => 'Weight Loss Pro',
            'price' => 420,
            'duration' => '4 weeks',
            'status' => 'active',
            'started' => '2025-06-01',
            'renewal' => '2025-07-01',
            'mealsRemaining' => 66,
            'mealsTotal' => 84,
            'calories' => '1500-1800',
            'color' => '#259B00',
        ];

        $availablePlans = [
            ['id' => 1, 'name' => 'Weight Loss Pro', 'price' => 420, 'calories' => '1500-1800', 'subscribers' => 128, 'color' => '#259B00', 'current' => true],
            ['id' => 2, 'name' => 'Muscle Gain', 'price' => 380, 'calories' => '2500-3000', 'subscribers' => 94, 'color' => '#033133', 'current' => false],
            ['id' => 3, 'name' => 'Maintenance', 'price' => 295, 'calories' => '2000-2200', 'subscribers' => 76, 'color' => '#f9ac00', 'current' => false],
            ['id' => 4, 'name' => 'Keto Premium', 'price' => 510, 'calories' => '1800-2000', 'subscribers' => 44, 'color' => '#3b82f6', 'current' => false],
        ];

        $history = [
            ['plan' => 'Weight Loss Pro', 'period' => 'Jun 2025', 'status' => 'active', 'amount' => 420],
            ['plan' => 'Weight Loss Pro', 'period' => 'May 2025', 'status' => 'completed', 'amount' => 420],
            ['plan' => 'Maintenance', 'period' => 'Apr 2025', 'status' => 'completed', 'amount' => 295],
            ['plan' => 'Maintenance', 'period' => 'Mar 2025', 'status' => 'completed', 'amount' => 295],
        ];

        return view('user.subscriptions', compact('activePlan', 'availablePlans', 'history'));
    }

    public function meals()
    {
        $todayMeals = [
            ['name' => 'Protein Breakfast Plate', 'time' => 'Breakfast · 07:30', 'calories' => 410, 'protein' => 35, 'carbs' => 28, 'fat' => 14, 'status' => 'delivered', 'image' => 'healthy-protein-bowl-with-quinoa-avocado-kale-sweet-potato-poached-egg_9975-132760.jpg'],
            ['name' => 'Grilled Chicken Bowl', 'time' => 'Lunch · 12:30', 'calories' => 520, 'protein' => 45, 'carbs' => 38, 'fat' => 18, 'status' => 'delivered', 'image' => 'grilled-chicken-breast-rice-berry-vegetables-white-background_1428-2141.jpg'],
            ['name' => 'Quinoa Buddha Bowl', 'time' => 'Dinner · 19:00', 'calories' => 480, 'protein' => 22, 'carbs' => 62, 'fat' => 16, 'status' => 'upcoming', 'image' => 'healthy-buddha-bowl-with-sliced-meat-fresh-vegetables_9975-132258.jpg'],
        ];

        $weekMeals = [
            ['day' => 'Mon', 'meals' => 3, 'calories' => 1410, 'completed' => true],
            ['day' => 'Tue', 'meals' => 3, 'calories' => 1480, 'completed' => true],
            ['day' => 'Wed', 'meals' => 3, 'calories' => 1390, 'completed' => true],
            ['day' => 'Thu', 'meals' => 3, 'calories' => 1520, 'completed' => true],
            ['day' => 'Fri', 'meals' => 3, 'calories' => 1450, 'completed' => true],
            ['day' => 'Sat', 'meals' => 3, 'calories' => 1410, 'completed' => true],
            ['day' => 'Sun', 'meals' => 2, 'calories' => 930, 'completed' => false],
        ];

        $stats = [
            'totalThisWeek' => 18,
            'totalPlan' => 84,
            'remaining' => 66,
            'avgCalories' => 1450,
            'favoriteMeal' => 'Grilled Chicken Bowl',
            'favoriteCount' => 12,
        ];

        return view('user.meals', compact('todayMeals', 'weekMeals', 'stats'));
    }

    public function nutrition()
    {
        $todayStats = [
            'calories' => 1240,
            'calorieTarget' => 1800,
            'protein' => 80,
            'proteinTarget' => 140,
            'carbs' => 130,
            'carbsTarget' => 200,
            'fat' => 32,
            'fatTarget' => 55,
            'water' => 6,
            'waterTarget' => 8,
            'steps' => 8420,
            'stepsTarget' => 10000,
        ];

        $weeklyData = [
            ['day' => 'Mon', 'calories' => 1620, 'protein' => 135, 'carbs' => 190, 'fat' => 52],
            ['day' => 'Tue', 'calories' => 1750, 'protein' => 142, 'carbs' => 205, 'fat' => 48],
            ['day' => 'Wed', 'calories' => 1580, 'protein' => 128, 'carbs' => 175, 'fat' => 44],
            ['day' => 'Thu', 'calories' => 1820, 'protein' => 145, 'carbs' => 210, 'fat' => 58],
            ['day' => 'Fri', 'calories' => 1700, 'protein' => 138, 'carbs' => 195, 'fat' => 50],
            ['day' => 'Sat', 'calories' => 1650, 'protein' => 132, 'carbs' => 188, 'fat' => 46],
            ['day' => 'Sun', 'calories' => 1240, 'protein' => 80, 'carbs' => 130, 'fat' => 32],
        ];

        $weightProgress = [
            ['week' => 'Week 1', 'weight' => 82.5],
            ['week' => 'Week 2', 'weight' => 81.8],
            ['week' => 'Week 3', 'weight' => 81.1],
            ['week' => 'Week 4', 'weight' => 80.4],
            ['week' => 'Week 5', 'weight' => 79.8],
            ['week' => 'Week 6', 'weight' => 79.2],
            ['week' => 'Week 7', 'weight' => 78.6],
            ['week' => 'Week 8', 'weight' => 78.2],
        ];

        $stats = [
            'currentWeight' => 78.2,
            'startWeight' => 82.5,
            'goalWeight' => 75.0,
            'lost' => 4.3,
            'remaining' => 3.2,
            'streakDays' => 28,
            'avgDailyCalories' => 1623,
            'adherenceRate' => 92,
        ];

        return view('user.nutrition', compact('todayStats', 'weeklyData', 'weightProgress', 'stats'));
    }

    public function orders()
    {
        $orders = [
            ['id' => 'ORD-2401', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-30', 'meals' => 3],
            ['id' => 'ORD-2387', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-29', 'meals' => 3],
            ['id' => 'ORD-2372', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-28', 'meals' => 3],
            ['id' => 'ORD-2358', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-27', 'meals' => 3],
            ['id' => 'ORD-2341', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-26', 'meals' => 3],
            ['id' => 'ORD-2329', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-25', 'meals' => 3],
            ['id' => 'ORD-2315', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'cancelled', 'date' => '2025-06-24', 'meals' => 3],
            ['id' => 'ORD-2302', 'plan' => 'Weight Loss Pro', 'amount' => 420, 'status' => 'delivered', 'date' => '2025-06-23', 'meals' => 3],
        ];

        $stats = [
            'total' => 42,
            'delivered' => 40,
            'cancelled' => 2,
            'totalSpent' => 16800,
            'avgOrder' => 400,
        ];

        return view('user.orders', compact('orders', 'stats'));
    }

    public function delivery()
    {
        $upcoming = [
            ['id' => 'DLV-501', 'order' => 'ORD-2401', 'date' => 'Tomorrow', 'time' => '09:00 - 10:00', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'scheduled', 'meals' => 3],
            ['id' => 'DLV-502', 'order' => 'ORD-2402', 'date' => 'Wed, Jul 3', 'time' => '09:00 - 10:00', 'zone' => 'Riyadh Central', 'driver' => 'Unassigned', 'status' => 'scheduled', 'meals' => 3],
        ];

        $history = [
            ['id' => 'DLV-498', 'order' => 'ORD-2387', 'date' => 'Today', 'time' => '09:15', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'eta' => 'On time'],
            ['id' => 'DLV-487', 'order' => 'ORD-2372', 'date' => 'Yesterday', 'time' => '09:10', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'eta' => 'On time'],
            ['id' => 'DLV-475', 'order' => 'ORD-2358', 'date' => 'Jun 27', 'time' => '09:20', 'zone' => 'Riyadh Central', 'driver' => 'Hassan', 'status' => 'delivered', 'eta' => '5 min late'],
            ['id' => 'DLV-462', 'order' => 'ORD-2341', 'date' => 'Jun 26', 'time' => '09:05', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'eta' => 'On time'],
            ['id' => 'DLV-451', 'order' => 'ORD-2329', 'date' => 'Jun 25', 'time' => '09:15', 'zone' => 'Riyadh Central', 'driver' => 'Yousef', 'status' => 'delivered', 'eta' => 'On time'],
        ];

        $stats = [
            'totalDeliveries' => 40,
            'onTimeRate' => 95,
            'avgDeliveryTime' => '32 min',
            'preferredSlot' => '09:00 - 10:00',
        ];

        return view('user.delivery', compact('upcoming', 'history', 'stats'));
    }

    public function notifications()
    {
        $notifications = [
            ['id' => 1, 'title' => 'Delivery Tomorrow', 'message' => 'Your meal delivery is scheduled for tomorrow 09:00 - 10:00', 'type' => 'delivery', 'time' => '1 hour ago', 'read' => false],
            ['id' => 2, 'title' => 'Meal Plan Renewal', 'message' => 'Your Weight Loss Pro plan renews on Jul 1, 2025', 'type' => 'subscription', 'time' => '5 hours ago', 'read' => false],
            ['id' => 3, 'title' => 'Nutrition Goal Achieved', 'message' => 'Congratulations! You hit your protein target 5 days in a row', 'type' => 'achievement', 'time' => 'Yesterday', 'read' => true],
            ['id' => 4, 'title' => 'New Meal Added', 'message' => 'Grilled Chicken Bowl has been added to your meal plan', 'type' => 'meal', 'time' => '2 days ago', 'read' => true],
            ['id' => 5, 'title' => 'Payment Successful', 'message' => 'Payment of SAR 420 for ORD-2387 was completed', 'type' => 'payment', 'time' => '3 days ago', 'read' => true],
            ['id' => 6, 'title' => 'Weekly Digest', 'message' => 'Your weekly nutrition summary is ready to view', 'type' => 'digest', 'time' => '4 days ago', 'read' => true],
        ];

        $preferences = [
            ['name' => 'Delivery Alerts', 'channel' => 'SMS', 'enabled' => true],
            ['name' => 'Meal Reminders', 'channel' => 'Push', 'enabled' => true],
            ['name' => 'Payment Receipts', 'channel' => 'Email', 'enabled' => true],
            ['name' => 'Weekly Digest', 'channel' => 'Email', 'enabled' => true],
            ['name' => 'Promotional Offers', 'channel' => 'Push', 'enabled' => false],
        ];

        $stats = [
            'unread' => 2,
            'total' => 48,
        ];

        return view('user.notifications', compact('notifications', 'preferences', 'stats'));
    }

    public function settings()
    {
        $authApi = app(AuthApiService::class);
        $apiUser = $authApi->user();

        $profile = [
            'name' => trim(($apiUser['first_name'] ?? 'John') . ' ' . ($apiUser['last_name'] ?? 'Doe')),
            'email' => $apiUser['email'] ?? 'john@example.com',
            'phone' => $apiUser['phone'] ?? '+966 55 123 4567',
            'dob' => '1990-05-15',
            'gender' => 'Male',
            'height' => 178,
            'weight' => 78.2,
            'goal' => 'Weight Loss',
            'activity' => 'Moderate',
            'address' => 'King Fahd Road, Riyadh, Saudi Arabia',
            'zone' => 'Riyadh Central',
        ];

        return view('user.settings', compact('profile'));
    }
}
