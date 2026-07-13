<?php

namespace App\Http\Controllers;

use App\Services\Api\AuthApiService;
use App\Services\Api\DriverApiService;
use App\Services\Api\HasApiData;
use App\Services\Api\NotificationApiService;
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

    public function dashboard(DriverApiService $driverApi, NotificationApiService $notificationApi)
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
            $item = $this->formatDelivery($delivery);

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

        return view('driver.dashboard', compact('currentDeliveries', 'history', 'stats', 'notifications'));
    }

    public function deliveries(DriverApiService $driverApi)
    {
        $deliveriesData = $this->apiData($driverApi->myDeliveries(), fn () => []);
        $deliveries = [];
        foreach ($deliveriesData as $delivery) {
            $deliveries[] = $this->formatDelivery($delivery);
        }
        return view('driver.deliveries', compact('deliveries'));
    }

    public function showDelivery(int $id, DriverApiService $driverApi)
    {
        $delivery = $this->apiData($driverApi->showDelivery($id), fn () => []);
        if (empty($delivery)) {
            return response()->json(['success' => false, 'message' => __('Delivery not found.')], 404);
        }
        return response()->json(['success' => true, 'delivery' => $this->formatDelivery($delivery)]);
    }

    public function mapView(int $id, DriverApiService $driverApi)
    {
        $deliveryData = $this->apiData($driverApi->showDelivery($id), fn () => []);

        if (empty($deliveryData)) {
            abort(404, __('Delivery not found.'));
        }

        $delivery = $this->formatDelivery($deliveryData);

        return view('driver.map', compact('delivery'));
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

    private function formatDelivery(array $delivery): array
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
        $customer = $delivery['customer'] ?? [];
        $order = $delivery['order'] ?? [];
        $address = $delivery['delivery_address'] ?? ($order['delivery_address'] ?? ($customer['address'] ?? ''));

        return [
            'id' => $delivery['id'] ?? 0,
            'order_id' => $delivery['order_id'] ?? ($order['id'] ?? 0),
            'order_number' => $order['order_number'] ?? ('ORD-' . ($order['id'] ?? 0)),
            'customer' => trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '')) ?: 'Customer',
            'customer_phone' => $customer['phone'] ?? '',
            'address' => $address,
            'zone' => $delivery['zone'] ?? 'N/A',
            'status' => $status,
            'status_label' => $statusLabels[$status] ?? __(ucfirst($status)),
            'eta' => $delivery['eta'] ?? 'On time',
            'scheduled_at' => $delivery['scheduled_at'] ?? null,
            'date' => !empty($delivery['scheduled_at']) ? date('Y-m-d', strtotime($delivery['scheduled_at'])) : date('Y-m-d'),
            'time' => !empty($delivery['scheduled_at']) ? date('H:i', strtotime($delivery['scheduled_at'])) : '--:--',
            'delivered_at' => $delivery['delivered_at'] ?? null,
            'failure_reason' => $delivery['failure_reason'] ?? '',
            'items' => $order['items'] ?? [],
            'lat' => $delivery['latitude'] ?? ($delivery['current_latitude'] ?? null),
            'lng' => $delivery['longitude'] ?? ($delivery['current_longitude'] ?? null),
        ];
    }
}
