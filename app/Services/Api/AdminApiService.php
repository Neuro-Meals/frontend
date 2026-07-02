<?php

namespace App\Services\Api;

class AdminApiService extends BaseApiService
{
    // ─── Dashboard ───

    public function dashboardStats(): array
    {
        return $this->get('admin.dashboard.stats');
    }

    public function dashboardRevenueTrend(): array
    {
        return $this->get('admin.dashboard.revenue_trend');
    }

    public function dashboardOrdersTrend(): array
    {
        return $this->get('admin.dashboard.orders_trend');
    }

    public function dashboardPlanDistribution(): array
    {
        return $this->get('admin.dashboard.plan_distribution');
    }

    public function dashboardRecentOrders(): array
    {
        return $this->get('admin.dashboard.recent_orders');
    }

    public function dashboardTopMeals(): array
    {
        return $this->get('admin.dashboard.top_meals');
    }

    public function dashboardDeliveryZones(): array
    {
        return $this->get('admin.dashboard.delivery_zones');
    }

    public function dashboardSystemStatus(): array
    {
        return $this->get('admin.dashboard.system_status');
    }

    // ─── Customers ───

    public function customersList(array $query = []): array
    {
        return $this->get('admin.customers.list', [], $query);
    }

    public function customerShow(int $id): array
    {
        return $this->get('admin.customers.show', ['id' => $id]);
    }

    public function customerCreate(array $data): array
    {
        return $this->post('admin.customers.create', [], $data);
    }

    public function customerUpdate(int $id, array $data): array
    {
        return $this->put('admin.customers.update', ['id' => $id], $data);
    }

    public function customerDelete(int $id): array
    {
        return $this->delete('admin.customers.delete', ['id' => $id]);
    }

    public function customersStats(): array
    {
        return $this->get('admin.customers.stats');
    }

    // ─── Subscriptions ───

    public function subscriptionsList(array $query = []): array
    {
        return $this->get('admin.subscriptions.list', [], $query);
    }

    public function subscriptionShow(int $id): array
    {
        return $this->get('admin.subscriptions.show', ['id' => $id]);
    }

    public function subscriptionCreate(array $data): array
    {
        return $this->post('admin.subscriptions.create', [], $data);
    }

    public function subscriptionUpdate(int $id, array $data): array
    {
        return $this->put('admin.subscriptions.update', ['id' => $id], $data);
    }

    public function subscriptionDelete(int $id): array
    {
        return $this->delete('admin.subscriptions.delete', ['id' => $id]);
    }

    public function subscriptionsStats(): array
    {
        return $this->get('admin.subscriptions.stats');
    }

    // ─── Meals ───

    public function mealsList(array $query = []): array
    {
        return $this->get('admin.meals.list', [], $query);
    }

    public function mealShow(int $id): array
    {
        return $this->get('admin.meals.show', ['id' => $id]);
    }

    public function mealCreate(array $data): array
    {
        return $this->post('admin.meals.create', [], $data);
    }

    public function mealUpdate(int $id, array $data): array
    {
        return $this->put('admin.meals.update', ['id' => $id], $data);
    }

    public function mealDelete(int $id): array
    {
        return $this->delete('admin.meals.delete', ['id' => $id]);
    }

    public function mealCategories(): array
    {
        return $this->get('admin.meals.categories');
    }

    public function mealsStats(): array
    {
        return $this->get('admin.meals.stats');
    }

    // ─── Orders ───

    public function ordersList(array $query = []): array
    {
        return $this->get('admin.orders.list', [], $query);
    }

    public function orderShow(int $id): array
    {
        return $this->get('admin.orders.show', ['id' => $id]);
    }

    public function orderCreate(array $data): array
    {
        return $this->post('admin.orders.create', [], $data);
    }

    public function orderUpdate(int $id, array $data): array
    {
        return $this->put('admin.orders.update', ['id' => $id], $data);
    }

    public function orderCancel(int $id): array
    {
        return $this->post('admin.orders.cancel', ['id' => $id]);
    }

    public function ordersStats(): array
    {
        return $this->get('admin.orders.stats');
    }

    // ─── Deliveries ───

    public function deliveriesList(array $query = []): array
    {
        return $this->get('admin.deliveries.list', [], $query);
    }

    public function deliveryShow(int $id): array
    {
        return $this->get('admin.deliveries.show', ['id' => $id]);
    }

    public function deliveryUpdate(int $id, array $data): array
    {
        return $this->put('admin.deliveries.update', ['id' => $id], $data);
    }

    public function deliveryAssign(int $id, array $data): array
    {
        return $this->post('admin.deliveries.assign', ['id' => $id], $data);
    }

    public function deliveryZones(): array
    {
        return $this->get('admin.deliveries.zones');
    }

    public function deliveriesStats(): array
    {
        return $this->get('admin.deliveries.stats');
    }

    // ─── Payments ───

    public function paymentsList(array $query = []): array
    {
        return $this->get('admin.payments.list', [], $query);
    }

    public function paymentShow(int $id): array
    {
        return $this->get('admin.payments.show', ['id' => $id]);
    }

    public function paymentRefund(int $id, array $data = []): array
    {
        return $this->post('admin.payments.refund', ['id' => $id], $data);
    }

    public function paymentsStats(): array
    {
        return $this->get('admin.payments.stats');
    }

    // ─── Notifications ───

    public function notificationsList(array $query = []): array
    {
        return $this->get('admin.notifications.list', [], $query);
    }

    public function notificationSend(array $data): array
    {
        return $this->post('admin.notifications.send', [], $data);
    }

    public function notificationTemplates(): array
    {
        return $this->get('admin.notifications.templates');
    }

    public function notificationsStats(): array
    {
        return $this->get('admin.notifications.stats');
    }

    // ─── Analytics ───

    public function analyticsReports(array $query = []): array
    {
        return $this->get('admin.analytics.reports', [], $query);
    }

    public function analyticsChartData(array $query = []): array
    {
        return $this->get('admin.analytics.chart_data', [], $query);
    }

    public function analyticsExport(array $data): array
    {
        return $this->post('admin.analytics.export', [], $data);
    }

    public function analyticsStats(): array
    {
        return $this->get('admin.analytics.stats');
    }

    // ─── Content ───

    public function contentPagesList(array $query = []): array
    {
        return $this->get('admin.content.list', [], $query);
    }

    public function contentPageShow(int $id): array
    {
        return $this->get('admin.content.show', ['id' => $id]);
    }

    public function contentPageCreate(array $data): array
    {
        return $this->post('admin.content.create', [], $data);
    }

    public function contentPageUpdate(int $id, array $data): array
    {
        return $this->put('admin.content.update', ['id' => $id], $data);
    }

    public function contentPageDelete(int $id): array
    {
        return $this->delete('admin.content.delete', ['id' => $id]);
    }

    public function contentStats(): array
    {
        return $this->get('admin.content.stats');
    }

    // ─── Settings ───

    public function settingsGet(): array
    {
        return $this->get('admin.settings.get');
    }

    public function settingsUpdate(array $data): array
    {
        return $this->put('admin.settings.update', [], $data);
    }
}
