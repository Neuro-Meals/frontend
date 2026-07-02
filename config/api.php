<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Base Configuration
    |--------------------------------------------------------------------------
    | This project uses Laravel as a frontend. All data is fetched from
    | an external API. Configure the base URL and timeout here.
    */

    'base_url' => env('API_BASE_URL', 'http://localhost:8000/api'),

    'timeout' => env('API_TIMEOUT', 30),

    'retry_attempts' => env('API_RETRY_ATTEMPTS', 3),

    /*
    |--------------------------------------------------------------------------
    | API Versioning
    |--------------------------------------------------------------------------
    */
    'version' => env('API_VERSION', 'v1'),

    /*
    |--------------------------------------------------------------------------
    | Endpoints
    |--------------------------------------------------------------------------
    | All endpoint paths relative to base_url.
    */
    'endpoints' => [

        // ─── Auth ───
        'auth' => [
            'login'    => '/auth/login',
            'register' => '/auth/register',
            'logout'   => '/auth/logout',
            'profile'  => '/auth/profile',
            'refresh'  => '/auth/refresh',
        ],

        // ─── Admin: Dashboard ───
        'admin' => [
            'dashboard' => [
                'stats'           => '/admin/dashboard/stats',
                'revenue_trend'   => '/admin/dashboard/revenue-trend',
                'orders_trend'    => '/admin/dashboard/orders-trend',
                'plan_distribution' => '/admin/dashboard/plan-distribution',
                'recent_orders'   => '/admin/dashboard/recent-orders',
                'top_meals'       => '/admin/dashboard/top-meals',
                'delivery_zones'  => '/admin/dashboard/delivery-zones',
                'system_status'   => '/admin/dashboard/system-status',
            ],

            // ─── Admin: Customers ───
            'customers' => [
                'list'    => '/admin/customers',
                'show'    => '/admin/customers/{id}',
                'create'  => '/admin/customers',
                'update'  => '/admin/customers/{id}',
                'delete'  => '/admin/customers/{id}',
                'stats'   => '/admin/customers/stats',
            ],

            // ─── Admin: Subscriptions ───
            'subscriptions' => [
                'list'    => '/admin/subscriptions',
                'show'    => '/admin/subscriptions/{id}',
                'create'  => '/admin/subscriptions',
                'update'  => '/admin/subscriptions/{id}',
                'delete'  => '/admin/subscriptions/{id}',
                'stats'   => '/admin/subscriptions/stats',
            ],

            // ─── Admin: Meals ───
            'meals' => [
                'list'       => '/admin/meals',
                'show'       => '/admin/meals/{id}',
                'create'     => '/admin/meals',
                'update'     => '/admin/meals/{id}',
                'delete'     => '/admin/meals/{id}',
                'categories' => '/admin/meals/categories',
                'stats'      => '/admin/meals/stats',
            ],

            // ─── Admin: Orders ───
            'orders' => [
                'list'   => '/admin/orders',
                'show'   => '/admin/orders/{id}',
                'create' => '/admin/orders',
                'update' => '/admin/orders/{id}',
                'cancel' => '/admin/orders/{id}/cancel',
                'stats'  => '/admin/orders/stats',
            ],

            // ─── Admin: Deliveries ───
            'deliveries' => [
                'list'   => '/admin/deliveries',
                'show'   => '/admin/deliveries/{id}',
                'update' => '/admin/deliveries/{id}',
                'assign' => '/admin/deliveries/{id}/assign',
                'zones'  => '/admin/deliveries/zones',
                'stats'  => '/admin/deliveries/stats',
            ],

            // ─── Admin: Payments ───
            'payments' => [
                'list'    => '/admin/payments',
                'show'    => '/admin/payments/{id}',
                'refund'  => '/admin/payments/{id}/refund',
                'stats'   => '/admin/payments/stats',
            ],

            // ─── Admin: Notifications ───
            'notifications' => [
                'list'      => '/admin/notifications',
                'send'      => '/admin/notifications/send',
                'templates' => '/admin/notifications/templates',
                'stats'     => '/admin/notifications/stats',
            ],

            // ─── Admin: Analytics ───
            'analytics' => [
                'reports'    => '/admin/analytics/reports',
                'chart_data' => '/admin/analytics/chart-data',
                'export'     => '/admin/analytics/export',
                'stats'      => '/admin/analytics/stats',
            ],

            // ─── Admin: Content ───
            'content' => [
                'list'   => '/admin/content/pages',
                'show'   => '/admin/content/pages/{id}',
                'create' => '/admin/content/pages',
                'update' => '/admin/content/pages/{id}',
                'delete' => '/admin/content/pages/{id}',
                'stats'  => '/admin/content/stats',
            ],

            // ─── Admin: Settings ───
            'settings' => [
                'get'    => '/admin/settings',
                'update' => '/admin/settings',
            ],
        ],

        // ─── Reports ───
        'reports' => [
            'dashboard' => [
                'kpis'               => '/reports/dashboard/kpis',
                'revenue_trend'      => '/reports/dashboard/revenue-trend',
                'subscription_funnel' => '/reports/dashboard/subscription-funnel',
                'delivery_sla'       => '/reports/dashboard/delivery-sla',
                'exceptions'         => '/reports/dashboard/exceptions',
                'operational_metrics' => '/reports/dashboard/operational-metrics',
            ],
            'revenue' => [
                'kpis'            => '/reports/revenue/kpis',
                'revenue_trend'   => '/reports/revenue/trend',
                'payment_trends'  => '/reports/revenue/payment-trends',
                'refund_volume'   => '/reports/revenue/refund-volume',
                'payment_methods' => '/reports/revenue/payment-methods',
                'revenue_by_plan' => '/reports/revenue/by-plan',
            ],
            'delivery' => [
                'kpis'              => '/reports/delivery/kpis',
                'on_time_trend'     => '/reports/delivery/on-time-trend',
                'zone_performance'  => '/reports/delivery/zone-performance',
                'exception_reasons' => '/reports/delivery/exception-reasons',
                'driver_productivity' => '/reports/delivery/driver-productivity',
                'heatmap'           => '/reports/delivery/heatmap',
            ],
            'subscriptions' => [
                'kpis'             => '/reports/subscriptions/kpis',
                'new_vs_churn'     => '/reports/subscriptions/new-vs-churn',
                'renewal_trend'    => '/reports/subscriptions/renewal-trend',
                'plan_ranking'     => '/reports/subscriptions/plan-ranking',
                'goal_distribution' => '/reports/subscriptions/goal-distribution',
                'corporate_metrics' => '/reports/subscriptions/corporate-metrics',
            ],
            'notifications' => [
                'kpis'                 => '/reports/notifications/kpis',
                'send_volume'          => '/reports/notifications/send-volume',
                'channel_mix'          => '/reports/notifications/channel-mix',
                'campaign_performance' => '/reports/notifications/campaign-performance',
                'failed_diagnostics'   => '/reports/notifications/failed-diagnostics',
            ],
            'audit' => [
                'kpis'            => '/reports/audit/kpis',
                'change_hotspots' => '/reports/audit/change-hotspots',
                'events'          => '/reports/audit/events',
                'export_history'  => '/reports/audit/export-history',
            ],
        ],

        // ─── User ───
        'user' => [
            'dashboard' => [
                'stats'           => '/user/dashboard/stats',
                'weekly_progress' => '/user/dashboard/weekly-progress',
                'next_delivery'   => '/user/dashboard/next-delivery',
                'weight_tracking' => '/user/dashboard/weight-tracking',
            ],
            'subscriptions' => [
                'list'    => '/user/subscriptions',
                'active'  => '/user/subscriptions/active',
                'history' => '/user/subscriptions/history',
            ],
            'meals' => [
                'list'     => '/user/meals',
                'today'    => '/user/meals/today',
                'favorite' => '/user/meals/favorites',
            ],
            'nutrition' => [
                'summary' => '/user/nutrition/summary',
                'log'     => '/user/nutrition/log',
                'targets' => '/user/nutrition/targets',
            ],
            'orders' => [
                'list' => '/user/orders',
                'show' => '/user/orders/{id}',
            ],
            'delivery' => [
                'current' => '/user/delivery/current',
                'history' => '/user/delivery/history',
                'track'   => '/user/delivery/track/{id}',
            ],
            'notifications' => [
                'list' => '/user/notifications',
                'read' => '/user/notifications/{id}/read',
            ],
            'settings' => [
                'get'    => '/user/settings',
                'update' => '/user/settings',
            ],
        ],
    ],
];
