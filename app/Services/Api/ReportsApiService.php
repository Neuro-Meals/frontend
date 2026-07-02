<?php

namespace App\Services\Api;

class ReportsApiService extends BaseApiService
{
    // ─── Report Dashboard ───

    public function dashboardKpis(): array
    {
        return $this->get('reports.dashboard.kpis');
    }

    public function dashboardRevenueTrend(): array
    {
        return $this->get('reports.dashboard.revenue_trend');
    }

    public function dashboardSubscriptionFunnel(): array
    {
        return $this->get('reports.dashboard.subscription_funnel');
    }

    public function dashboardDeliverySla(): array
    {
        return $this->get('reports.dashboard.delivery_sla');
    }

    public function dashboardExceptions(): array
    {
        return $this->get('reports.dashboard.exceptions');
    }

    public function dashboardOperationalMetrics(): array
    {
        return $this->get('reports.dashboard.operational_metrics');
    }

    // ─── Revenue Reports ───

    public function revenueKpis(): array
    {
        return $this->get('reports.revenue.kpis');
    }

    public function revenueTrend(): array
    {
        return $this->get('reports.revenue.revenue_trend');
    }

    public function revenuePaymentTrends(): array
    {
        return $this->get('reports.revenue.payment_trends');
    }

    public function revenueRefundVolume(): array
    {
        return $this->get('reports.revenue.refund_volume');
    }

    public function revenuePaymentMethods(): array
    {
        return $this->get('reports.revenue.payment_methods');
    }

    public function revenueByPlan(): array
    {
        return $this->get('reports.revenue.revenue_by_plan');
    }

    // ─── Delivery Reports ───

    public function deliveryKpis(): array
    {
        return $this->get('reports.delivery.kpis');
    }

    public function deliveryOnTimeTrend(): array
    {
        return $this->get('reports.delivery.on_time_trend');
    }

    public function deliveryZonePerformance(): array
    {
        return $this->get('reports.delivery.zone_performance');
    }

    public function deliveryExceptionReasons(): array
    {
        return $this->get('reports.delivery.exception_reasons');
    }

    public function deliveryDriverProductivity(): array
    {
        return $this->get('reports.delivery.driver_productivity');
    }

    public function deliveryHeatmap(): array
    {
        return $this->get('reports.delivery.heatmap');
    }

    // ─── Subscription Reports ───

    public function subscriptionsKpis(): array
    {
        return $this->get('reports.subscriptions.kpis');
    }

    public function subscriptionsNewVsChurn(): array
    {
        return $this->get('reports.subscriptions.new_vs_churn');
    }

    public function subscriptionsRenewalTrend(): array
    {
        return $this->get('reports.subscriptions.renewal_trend');
    }

    public function subscriptionsPlanRanking(): array
    {
        return $this->get('reports.subscriptions.plan_ranking');
    }

    public function subscriptionsGoalDistribution(): array
    {
        return $this->get('reports.subscriptions.goal_distribution');
    }

    public function subscriptionsCorporateMetrics(): array
    {
        return $this->get('reports.subscriptions.corporate_metrics');
    }

    // ─── Notification Reports ───

    public function notificationsKpis(): array
    {
        return $this->get('reports.notifications.kpis');
    }

    public function notificationsSendVolume(): array
    {
        return $this->get('reports.notifications.send_volume');
    }

    public function notificationsChannelMix(): array
    {
        return $this->get('reports.notifications.channel_mix');
    }

    public function notificationsCampaignPerformance(): array
    {
        return $this->get('reports.notifications.campaign_performance');
    }

    public function notificationsFailedDiagnostics(): array
    {
        return $this->get('reports.notifications.failed_diagnostics');
    }

    // ─── Audit Reports ───

    public function auditKpis(): array
    {
        return $this->get('reports.audit.kpis');
    }

    public function auditChangeHotspots(): array
    {
        return $this->get('reports.audit.change_hotspots');
    }

    public function auditEvents(array $query = []): array
    {
        return $this->get('reports.audit.events', [], $query);
    }

    public function auditExportHistory(): array
    {
        return $this->get('reports.audit.export_history');
    }
}
