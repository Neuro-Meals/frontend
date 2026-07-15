<?php

namespace App\Http\Controllers;

use App\Services\Api\AuthApiService;
use App\Services\Api\ChefApiService;
use App\Services\Api\HasApiData;
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

    public function dashboard(ChefApiService $chefApi, NotificationApiService $notificationApi)
    {
        $today = date('Y-m-d');

        $dashboardData = $this->apiData($chefApi->dashboard(), fn () => $this->mockDashboardStats());

        $ordersResponse = $chefApi->orders([
            'delivery_date' => $today,
            'limit' => 100,
        ]);

        $ordersData = $ordersResponse['data'] ?? ($this->apiEnabled() ? [] : $this->mockOrders());

        $morningOrders = [];
        $noonOrders = [];
        $eveningOrders = [];

        $stats = [
            'total_today' => 0,
            'morning' => 0,
            'noon' => 0,
            'evening' => 0,
            'pending' => 0,
            'preparing' => 0,
            'ready' => 0,
            'completed' => 0,
        ];

        foreach ($ordersData as $order) {
            $item = $this->formatOrder($order);
            $timeframe = $item['timeframe'];

            $stats['total_today']++;
            $stats[$timeframe]++;

            if (in_array($item['status'], ['pending', 'confirmed'])) {
                $stats['pending']++;
            } elseif ($item['status'] === 'preparing') {
                $stats['preparing']++;
            } elseif ($item['status'] === 'ready_for_delivery') {
                $stats['ready']++;
            } elseif (in_array($item['status'], ['out_for_delivery', 'delivered'])) {
                $stats['completed']++;
            }

            match ($timeframe) {
                'morning' => $morningOrders[] = $item,
                'noon' => $noonOrders[] = $item,
                'evening' => $eveningOrders[] = $item,
                default => null,
            };
        }

        $notificationsData = $this->apiData($notificationApi->my(['limit' => 5, 'is_read' => false]), fn () => []);
        $notifications = [];
        foreach ($notificationsData as $n) {
            $notifications[] = [
                'id' => $n['id'] ?? 0,
                'title' => $n['title'] ?? '',
                'message' => $n['message'] ?? '',
                'created_at' => $n['created_at'] ?? '',
                'is_read' => $n['is_read'] ?? false,
            ];
        }

        return view('chef.dashboard', compact('morningOrders', 'noonOrders', 'eveningOrders', 'stats', 'notifications'));
    }

    public function startPreparing(Request $request, int $orderId, ChefApiService $chefApi)
    {
        $response = $this->apiData($chefApi->startPreparing($orderId), fn () => []);
        $success = is_array($response) && !empty($response['order']);
        $message = $response['message'] ?? ($response['detail'] ?? ($success ? __('Preparation started.') : __('Failed to start preparation.')));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        return redirect()->route('chef.dashboard')->with($success ? 'status' : 'error', $message);
    }

    public function markReady(Request $request, int $orderId, ChefApiService $chefApi)
    {
        $response = $this->apiData($chefApi->markReady($orderId), fn () => []);
        $success = is_array($response) && !empty($response['order']);
        $message = $response['message'] ?? ($response['detail'] ?? ($success ? __('Order marked as ready.') : __('Failed to mark as ready.')));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        return redirect()->route('chef.dashboard')->with($success ? 'status' : 'error', $message);
    }

    private function formatOrder(array $order): array
    {
        $statusLabels = [
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
        $hour = null;
        if ($deliveryDate) {
            $hour = (int) date('H', strtotime($deliveryDate));
        }

        $timeframe = match (true) {
            $hour !== null && $hour < 11 => 'morning',
            $hour !== null && $hour < 16 => 'noon',
            $hour !== null && $hour >= 16 => 'evening',
            default => 'morning',
        };

        $timeframeLabels = [
            'morning' => __('Morning'),
            'noon' => __('Noon'),
            'evening' => __('Evening'),
        ];

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
            }
        }

        $customerName = trim($customer['full_name'] ?? (($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''))) ?: __('Customer');

        return [
            'id' => $order['id'] ?? 0,
            'order_number' => $order['order_number'] ?? ('ORD-' . ($order['id'] ?? 0)),
            'status' => $status,
            'status_label' => $statusLabels[$status] ?? __(ucfirst(str_replace('_', ' ', $status))),
            'timeframe' => $timeframe,
            'timeframe_label' => $timeframeLabels[$timeframe] ?? __(ucfirst($timeframe)),
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
                    ['meal_name' => 'Oatmeal with Berries', 'quantity' => 1, 'calories' => 350],
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
                    ['meal_name' => 'Veggie Omelette', 'quantity' => 2, 'calories' => 420],
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
                    ['meal_name' => 'Grilled Chicken Salad', 'quantity' => 1, 'calories' => 550],
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
                    ['meal_name' => 'Quinoa Buddha Bowl', 'quantity' => 1, 'calories' => 480],
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
                    ['meal_name' => 'Salmon with Roasted Vegetables', 'quantity' => 1, 'calories' => 620],
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
                    ['meal_name' => 'Beef Stir Fry with Rice', 'quantity' => 2, 'calories' => 580],
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
