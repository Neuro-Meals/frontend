<?php

namespace App\Http\Controllers;

use App\Services\Api\AuthApiService;
use App\Services\Api\DriverApiService;
use App\Services\Api\HasApiData;
use App\Services\Api\NotificationApiService;
use App\Services\Api\OrderApiService;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    use HasApiData;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $authApi = app(AuthApiService::class);
            if (!$authApi->check() || !$authApi->hasRole('driver')) {
                abort(403, 'Access denied. Drivers only.');
            }
            return $next($request);
        });
    }

    public function dashboard(DriverApiService $driverApi, NotificationApiService $notificationApi, OrderApiService $orderApi)
    {
        $deliveriesData = $this->apiData($driverApi->myDeliveries(), fn () => []);

        $currentDeliveries = [];
        $history = [];
        $stats = [
            'assigned' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'failed' => 0,
            'today' => 0,
            'rating' => 4.8,
        ];

        $today = date('Y-m-d');

        foreach ($deliveriesData as $delivery) {
            $orderData = $this->enrichOrderData($delivery, $orderApi);
            $item = $this->formatDelivery($delivery, $orderData);

            $status = $item['status'];
            if (in_array($status, ['assigned', 'pending'])) {
                $stats['assigned']++;
            }
            if (in_array($status, ['picked_up', 'out_for_delivery'])) {
                $stats['in_progress']++;
            }
            if ($status === 'delivered') {
                $stats['completed']++;
            }
            if ($status === 'failed') {
                $stats['failed']++;
            }
            if (($item['date'] ?? '') === $today) {
                $stats['today']++;
            }

            if (in_array($status, ['delivered', 'failed', 'cancelled'])) {
                $history[] = $item;
            } else {
                $currentDeliveries[] = $item;
            }
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

        $user = app(AuthApiService::class)->user() ?? [];
        $driverCode = 'D-' . ($user['id'] ?? '0');

        // Primary zone: the zone shared by the most current deliveries, falling back to the first available.
        $zoneCounts = array_count_values(array_filter(array_column($currentDeliveries, 'zone'), fn ($z) => $z && $z !== 'N/A'));
        arsort($zoneCounts);
        $primaryZone = array_key_first($zoneCounts) ?: ($currentDeliveries[0]['zone'] ?? __('Unassigned'));

        $readyForPickup = $stats['assigned'] > 0 && $stats['in_progress'] === 0;
        $onDelivery = $stats['in_progress'] > 0;

        return view('driver.dashboard', compact('currentDeliveries', 'history', 'stats', 'notifications', 'driverCode', 'primaryZone', 'readyForPickup', 'onDelivery'));
    }

    public function notifications(NotificationApiService $notificationApi)
    {
        $notificationsData = $this->apiData($notificationApi->my(['limit' => 50]), fn () => []);
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

        return view('driver.notifications', compact('notifications'));
    }

    public function markNotificationRead(Request $request, int $id, NotificationApiService $notificationApi)
    {
        $response = $this->apiData($notificationApi->markAsRead($id), fn () => []);
        $success = is_array($response);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success]);
        }

        return redirect()->route('driver.notifications');
    }

    public function profile(DriverApiService $driverApi, OrderApiService $orderApi)
    {
        $user = app(AuthApiService::class)->user() ?? [];
        $driverCode = 'D-' . ($user['id'] ?? '0');

        $deliveriesData = $this->apiData($driverApi->myDeliveries(), fn () => []);
        $totalDelivered = 0;
        $totalFailed = 0;
        $zoneCounts = [];
        foreach ($deliveriesData as $delivery) {
            $status = $delivery['status'] ?? 'pending';
            if ($status === 'delivered') $totalDelivered++;
            if ($status === 'failed') $totalFailed++;
            $zone = $delivery['zone'] ?? null;
            if ($zone) {
                $zoneCounts[$zone] = ($zoneCounts[$zone] ?? 0) + 1;
            }
        }
        arsort($zoneCounts);
        $primaryZone = array_key_first($zoneCounts) ?: __('Unassigned');

        return view('driver.profile', compact('user', 'driverCode', 'primaryZone', 'totalDelivered', 'totalFailed'));
    }

    /**
     * Browse ready-for-delivery orders that have no driver yet, so a
     * driver can pick their own load ("kuchagua mzigo") instead of
     * only waiting to be assigned.
     */
    public function availableLoads(DriverApiService $driverApi)
    {
        $response = $this->apiData($driverApi->availableLoads(), fn () => ['data' => []]);
        $loads = $response['data'] ?? [];

        return view('driver.available-loads', compact('loads'));
    }

    public function claimLoad(Request $request, int $orderId, DriverApiService $driverApi)
    {
        $response = $driverApi->claimLoad($orderId);
        $success = isset($response['id']) || (isset($response['success']) && $response['success'] === true);
        $message = $response['message'] ?? ($response['detail'] ?? ($success ? __('Load claimed. It is now in your deliveries.') : __('Failed to claim this load — it may already be taken.')));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        return redirect()->route('driver.available-loads')->with($success ? 'status' : 'error', $message);
    }

    public function deliveries(DriverApiService $driverApi, OrderApiService $orderApi)
    {
        $deliveriesData = $this->apiData($driverApi->myDeliveries(), fn () => []);
        $deliveries = [];
        foreach ($deliveriesData as $delivery) {
            $orderData = $this->enrichOrderData($delivery, $orderApi);
            $deliveries[] = $this->formatDelivery($delivery, $orderData);
        }

        $user = app(AuthApiService::class)->user() ?? [];
        $driverCode = 'D-' . ($user['id'] ?? '0');

        $zoneCounts = array_count_values(array_filter(array_column($deliveries, 'zone'), fn ($z) => $z && $z !== 'N/A'));
        arsort($zoneCounts);
        $primaryZone = array_key_first($zoneCounts) ?: __('Unassigned');

        return view('driver.deliveries', compact('deliveries', 'driverCode', 'primaryZone'));
    }

    public function showDelivery(int $id, DriverApiService $driverApi)
    {
        $delivery = $this->apiData($driverApi->showDelivery($id), fn () => []);
        if (empty($delivery)) {
            return response()->json(['success' => false, 'message' => __('Delivery not found.')], 404);
        }
        return response()->json(['success' => true, 'delivery' => $this->formatDelivery($delivery)]);
    }

    public function mapView(int $id, DriverApiService $driverApi, OrderApiService $orderApi)
    {
        $deliveryData = $this->apiData($driverApi->showDelivery($id), fn () => []);

        if (empty($deliveryData)) {
            abort(404, __('Delivery not found.'));
        }

        $orderData = $this->enrichOrderData($deliveryData, $orderApi);
        $delivery = $this->formatDelivery($deliveryData, $orderData);
        $delivery['whatsapp_phone'] = $this->cleanPhoneForWhatsApp($delivery['customer_phone']);

        return view('driver.map', compact('delivery'));
    }

    public function detailView(int $id, DriverApiService $driverApi, OrderApiService $orderApi)
    {
        $deliveryData = $this->apiData($driverApi->showDelivery($id), fn () => []);

        if (empty($deliveryData)) {
            abort(404, __('Delivery not found.'));
        }

        $orderData = $this->enrichOrderData($deliveryData, $orderApi);
        $delivery = $this->formatDelivery($deliveryData, $orderData);
        $delivery['whatsapp_phone'] = $this->cleanPhoneForWhatsApp($delivery['customer_phone']);
        $delivery['meal_id'] = $delivery['order_number'];

        // Build the route stepper (Delivery X of Y, Previous/Next Stop) from today's active stops.
        $allDeliveriesData = $this->apiData($driverApi->myDeliveries(), fn () => []);
        $activeStops = [];
        foreach ($allDeliveriesData as $d) {
            $status = $d['status'] ?? 'pending';
            if (!in_array($status, ['delivered', 'failed', 'cancelled'])) {
                $activeStops[] = (int) ($d['id'] ?? 0);
            }
        }

        $position = array_search($id, $activeStops, true);
        $stepper = [
            'total' => count($activeStops),
            'position' => $position !== false ? $position + 1 : 1,
            'remaining' => $position !== false ? count($activeStops) - $position - 1 : 0,
            'prev_id' => ($position !== false && $position > 0) ? $activeStops[$position - 1] : null,
            'next_id' => ($position !== false && $position < count($activeStops) - 1) ? $activeStops[$position + 1] : null,
        ];

        return view('driver.delivery-detail', compact('delivery', 'stepper'));
    }

    private function enrichOrderData(array $deliveryData, OrderApiService $orderApi): array
    {
        // The delivery response sometimes omits the customer/order details.
        // In that case, fetch the order directly and use it as the source of truth.
        $orderId = $deliveryData['order_id'] ?? ($deliveryData['order']['id'] ?? null);
        if (!$orderId) {
            return $deliveryData['order'] ?? [];
        }

        $hasOrder = !empty($deliveryData['order']) && is_array($deliveryData['order']);
        $hasCustomer = !empty($deliveryData['customer']) || !empty($deliveryData['order']['customer'] ?? $deliveryData['order']['user'] ?? []);
        if ($hasOrder && $hasCustomer) {
            return $deliveryData['order'];
        }

        $order = $this->apiData($orderApi->show((int) $orderId), fn () => []);
        if (!empty($order)) {
            return $order;
        }

        return $deliveryData['order'] ?? [];
    }

    private function cleanPhoneForWhatsApp(?string $phone): string
    {
        if (empty($phone)) return '';
        $digits = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($digits) > 0 && $digits[0] !== '+') {
            $digits = ltrim($digits, '0');
            $digits = '966' . $digits;
        }
        return $digits;
    }

    public function updateStatus(Request $request, int $id, DriverApiService $driverApi)
    {
        $status = $request->input('status');
        $reason = $request->input('reason', '');

        $response = match ($status) {
            'picked_up' => $this->apiData($driverApi->pickupDelivery($id), fn () => []),
            'out_for_delivery' => $this->apiData($driverApi->outForDelivery($id), fn () => []),
            'delivered' => $this->apiData($driverApi->completeDelivery($id), fn () => []),
            'failed' => $this->apiData($driverApi->failDelivery($id, $reason), fn () => []),
            default => [],
        };

        $success = is_array($response) && !empty($response['id']);
        $message = $response['message'] ?? ($response['detail'] ?? ($success ? __('Status updated.') : __('Failed to update status.')));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => $success, 'message' => $message], $success ? 200 : 422);
        }

        return redirect()->route('driver.dashboard')->with($success ? 'status' : 'error', $message);
    }

    public function updateLocation(Request $request, int $id, DriverApiService $driverApi)
    {
        $lat = (float) $request->input('latitude');
        $lng = (float) $request->input('longitude');

        $response = $this->apiData($driverApi->updateLocation($id, $lat, $lng), fn () => []);
        $success = is_array($response) && !empty($response['id']);

        return response()->json([
            'success' => $success,
            'message' => $success ? __('Location updated.') : __('Failed to update location.'),
        ], $success ? 200 : 422);
    }

    private function formatDelivery(array $delivery, array $orderData = []): array
    {
        $statusLabels = [
            'pending' => __('Pending'),
            'assigned' => __('Assigned'),
            'picked_up' => __('Picked Up'),
            'out_for_delivery' => __('Out for Delivery'),
            'delivered' => __('Delivered'),
            'failed' => __('Failed'),
            'cancelled' => __('Cancelled'),
        ];

        $status = $delivery['status'] ?? 'pending';
        $order = $orderData ?: ($delivery['order'] ?? []);
        $customer = $delivery['customer'] ?? ($order['customer'] ?? ($order['user'] ?? []));
        $address = $delivery['delivery_address'] ?? ($order['delivery_address'] ?? ($customer['address'] ?? ''));

        // Enrich items and compute aggregates
        $items = $order['items'] ?? [];
        $mealNames = [];
        $totalCalories = 0;
        $totalProtein = 0;
        $totalCarbs = 0;
        $totalFat = 0;
        $totalQty = 0;

        if (is_array($items)) {
            foreach ($items as $item) {
                $name = $item['meal_name'] ?? ($item['name'] ?? ($item['name_en'] ?? ($item['title'] ?? '')));
                $qty = (int) ($item['quantity'] ?? 1);
                $totalQty += $qty;
                if ($name) {
                    $mealNames[] = $qty > 1 ? "{$name} x{$qty}" : $name;
                }
                $cal = (int) ($item['calories'] ?? 0);
                $totalCalories += $cal * $qty;
                $totalProtein += (float) ($item['protein_g'] ?? 0) * $qty;
                $totalCarbs += (float) ($item['carbs_g'] ?? 0) * $qty;
                $totalFat += (float) ($item['fat_g'] ?? 0) * $qty;
            }
        }

        return [
            'id' => $delivery['id'] ?? 0,
            'order_id' => $delivery['order_id'] ?? ($order['id'] ?? 0),
            'order_number' => $order['order_number'] ?? ($delivery['order_number'] ?? ('ORD-' . ($order['id'] ?? ($delivery['order_id'] ?? ($delivery['id'] ?? 0))))),
            'customer' => trim($customer['full_name'] ?? (($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''))) ?: 'Customer',
            'customer_id' => $customer['id'] ?? null,
            'customer_email' => $customer['email'] ?? '',
            'customer_phone' => $customer['phone'] ?? ($customer['mobile'] ?? ''),
            'whatsapp_phone' => $this->cleanPhoneForWhatsApp($customer['phone'] ?? ($customer['mobile'] ?? '')),
            'customer_location' => $customer['location'] ?? '',
            'customer_address' => $customer['address'] ?? '',
            'address' => $address,
            'notes' => $delivery['delivery_notes'] ?? ($order['delivery_notes'] ?? ''),
            'zone' => $delivery['zone'] ?? ($customer['location'] ?? 'N/A'),
            'status' => $status,
            'status_label' => $statusLabels[$status] ?? __(ucfirst($status)),
            'eta' => $delivery['eta'] ?? 'On time',
            'scheduled_at' => $delivery['scheduled_at'] ?? null,
            'date' => !empty($delivery['scheduled_at']) ? date('Y-m-d', strtotime($delivery['scheduled_at'])) : date('Y-m-d'),
            'time' => !empty($delivery['scheduled_at']) ? date('H:i', strtotime($delivery['scheduled_at'])) : '--:--',
            'delivered_at' => $delivery['delivered_at'] ?? null,
            'failure_reason' => $delivery['failure_reason'] ?? '',
            'items' => $items,
            'meal_summary' => implode(', ', $mealNames) ?: __('No items'),
            'meal_count' => is_array($items) ? count($items) : 0,
            'total_quantity' => $totalQty,
            'total_calories' => $totalCalories,
            'total_protein_g' => round($totalProtein, 1),
            'total_carbs_g' => round($totalCarbs, 1),
            'total_fat_g' => round($totalFat, 1),
            'amount' => $order['total_amount'] ?? 0,
            'lat' => $delivery['latitude'] ?? ($delivery['current_latitude'] ?? null),
            'lng' => $delivery['longitude'] ?? ($delivery['current_longitude'] ?? null),
        ];
    }
}
