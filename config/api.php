<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Base Configuration
    |--------------------------------------------------------------------------
    | This project uses Laravel as a frontend. All data is fetched from
    | an external API. Configure the base URL and timeout here.
    */

    'base_url' => env('API_BASE_URL', 'http://185.237.97.69:8080'),

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
            'login'                   => '/auth/login',
            'register'                => '/auth/register',
            'verify_email'            => '/auth/verify-email',
            'resend_verification_otp' => '/auth/resend-verification-otp',
            'me'                      => '/auth/me',
            'forgot_password'         => '/auth/forgot-password',
            'reset_password'          => '/auth/reset-password',
            'change_password'         => '/auth/change-password',
        ],

        // ─── Users ───
        'users' => [
            'me'         => '/users/me',
            'list'       => '/users/',
            'update_role' => '/users/{user_id}/role',
        ],

        // ─── RBAC ───
        'rbac' => [
            'roles'              => '/rbac/roles',
            'permissions'        => '/rbac/permissions',
            'assign_role'        => '/rbac/assign-role',
            'assign_permission'  => '/rbac/assign-permission',
        ],

        // ─── Profile ───
        'profile' => [
            'get'    => '/profile/',
            'update' => '/profile/',
        ],

        // ─── Meal Categories ───
        'meal_categories' => [
            'list'   => '/meal-categories/',
            'create' => '/meal-categories/',
            'show'   => '/meal-categories/{category_id}',
            'update' => '/meal-categories/{category_id}',
            'delete' => '/meal-categories/{category_id}',
        ],

        // ─── Meals ───
        'meals' => [
            'list'   => '/meals/',
            'create' => '/meals/',
            'show'   => '/meals/{meal_id}',
            'update' => '/meals/{meal_id}',
            'delete' => '/meals/{meal_id}',
        ],

        // ─── Meal Plans ───
        'plans' => [
            'list'   => '/plans/',
            'create' => '/plans/',
            'show'   => '/plans/{plan_id}',
            'update' => '/plans/{plan_id}',
            'delete' => '/plans/{plan_id}',
        ],

        // ─── Subscriptions ───
        'subscriptions' => [
            'list'   => '/subscriptions/',
            'create' => '/subscriptions/',
            'my'     => '/subscriptions/my',
            'show'   => '/subscriptions/{subscription_id}',
            'update' => '/subscriptions/{subscription_id}',
            'cancel' => '/subscriptions/{subscription_id}/cancel',
        ],

        // ─── Orders ───
        'orders' => [
            'list'          => '/orders/',
            'my'            => '/orders/my',
            'show'          => '/orders/{order_id}',
            'from_subscription' => '/orders/from-subscription',
            'update_status' => '/orders/{order_id}/status',
            'cancel'        => '/orders/{order_id}/cancel',
        ],

        // ─── Deliveries ───
        'deliveries' => [
            'list'           => '/deliveries/',
            'create'         => '/deliveries/',
            'my'             => '/deliveries/my',
            'driver_my'      => '/deliveries/driver/my',
            'show'           => '/deliveries/{delivery_id}',
            'assign_driver'  => '/deliveries/{delivery_id}/assign-driver',
            'update_status'  => '/deliveries/{delivery_id}/status',
            'update_location' => '/deliveries/{delivery_id}/location',
        ],

        // ─── Notifications ───
        'notifications' => [
            'list'     => '/notifications/',
            'create'   => '/notifications/',
            'my'       => '/notifications/my',
            'show'     => '/notifications/{notification_id}',
            'read'     => '/notifications/{notification_id}/read',
            'read_all' => '/notifications/my/read-all',
        ],

        // ─── Meal Schedule (not in OpenAPI spec — customer dashboard requirement) ───
        'meal_schedule' => [
            'my'       => '/meal-schedule/my',
            'my_today' => '/meal-schedule/my/today',
        ],

        // ─── Nutrition / Tracking (not in OpenAPI spec — customer dashboard requirement) ───
        'nutrition' => [
            'today'         => '/nutrition/today',
            'weekly'        => '/nutrition/weekly',
            'weight_history' => '/weight-history',
            'activity_today' => '/activity/today',
        ],

        // ─── Reports (not in OpenAPI spec — used with mock fallback) ───
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
    ],
];
