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
    | Role Map
    |--------------------------------------------------------------------------
    | Maps role_id values returned by the backend API to role names used by
    | the application middleware and login redirects.
    */
    'role_map' => [
        1 => 'customer',
        2 => 'admin',
        3 => 'super_admin',
        4 => 'driver',
        5 => 'chef',
    ],

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
            'me'          => '/users/me',
            'list'        => '/users/',
            'show'        => '/users/{user_id}',
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
            'list'           => '/plans/',
            'create'         => '/plans/',
            'show'           => '/plans/{plan_id}',
            'update'         => '/plans/{plan_id}',
            'delete'         => '/plans/{plan_id}',
            'items_add'      => '/plans/{plan_id}/items',
            'items_list'     => '/plans/{plan_id}/items',
            'items_remove'   => '/plans/{plan_id}/items/{item_id}',
        ],

        // ─── Subscriptions ───
        'subscriptions' => [
            'list'         => '/subscriptions/',
            'create'       => '/subscriptions/',
            'admin_create' => '/subscriptions/admin',
            'my'           => '/subscriptions/my',
            'show'         => '/subscriptions/{subscription_id}',
            'update'       => '/subscriptions/{subscription_id}',
            'cancel'       => '/subscriptions/{subscription_id}/cancel',
            'pause'        => '/subscriptions/{subscription_id}/pause',
            'resume'       => '/subscriptions/{subscription_id}/resume',
            'pauses'       => '/subscriptions/{subscription_id}/pauses',
            'change_plan'  => '/subscriptions/{subscription_id}/change-plan',
            'plan_changes' => '/subscriptions/{subscription_id}/plan-changes',
            'cancel_plan_change' => '/subscriptions/{subscription_id}/plan-changes/{change_id}/cancel',
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

        // ─── Locations ───
        'locations' => [
            'list'              => '/locations/',
            'regions'           => '/locations/regions',
            'region'            => '/locations/regions/{region_code}',
            'region_cities'     => '/locations/regions/{region_code}/cities',
            'validate'          => '/locations/validate',
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

        // ─── Payments / Checkout ───
        'payments' => [
            'create_checkout'              => '/payments/create-checkout',
            'create_plan_change_checkout' => '/payments/create-plan-change-checkout',
            'my'                           => '/payments/my',
            'list'                         => '/payments/',
            'verify_session'               => '/payments/verify-session/{session_id}',
            'verify_charge'                => '/payments/verify-charge/{charge_id}',
        ],

        // ─── Drivers ───
        'drivers' => [
            'list'   => '/driver/admin',
            'create' => '/driver/admin',
            'show'   => '/driver/admin/{driver_id}',
            'update' => '/driver/admin/{driver_id}',
            'delete' => '/driver/admin/{driver_id}',
        ],

        // ─── Admin Chef Management ───
        'admin_chefs' => [
            'list'               => '/admin/chefs/',
            'create'             => '/admin/chefs/',
            'show'               => '/admin/chefs/{chef_id}',
            'update'             => '/admin/chefs/{chef_id}',
            'activate'           => '/admin/chefs/{chef_id}/activate',
            'deactivate'         => '/admin/chefs/{chef_id}/deactivate',
            'assign_existing'    => '/admin/chefs/assign-existing-user',
            'remove_role'        => '/admin/chefs/{chef_id}/remove-role',
        ],

        // ─── Driver App ───
        'driver' => [
            'my_deliveries'      => '/driver/deliveries',
            'show_delivery'      => '/driver/deliveries/{delivery_id}',
            'pickup'             => '/driver/deliveries/{delivery_id}/pickup',
            'out_for_delivery'   => '/driver/deliveries/{delivery_id}/out-for-delivery',
            'complete'           => '/driver/deliveries/{delivery_id}/complete',
            'fail'               => '/driver/deliveries/{delivery_id}/fail',
            'update_location'    => '/driver/deliveries/{delivery_id}/location',
        ],

        // ─── Chef App ───
        'chef' => [
            'dashboard'          => '/chef/dashboard',
            'orders'             => '/chef/orders',
            'show_order'         => '/chef/orders/{order_id}',
            'start_preparing'    => '/chef/orders/{order_id}/start-preparing',
            'mark_ready'         => '/chef/orders/{order_id}/ready',
            'drivers'            => '/chef/drivers',
            'assign_driver'      => '/chef/orders/{order_id}/assign-driver',
        ],

        // ─── Meal Schedule (not in OpenAPI spec — customer dashboard requirement) ───
        'meal_schedule' => [
            'my'       => '/meal-schedule/my',
            'my_today' => '/meal-schedule/my/today',
        ],

        // ─── Meal Selections ───
        'meal_selections' => [
            'my'     => '/meal-selections/my',
            'create' => '/meal-selections/',
            'update' => '/meal-selections/{selection_id}',
            'delete' => '/meal-selections/{selection_id}',
        ],

        // ─── Nutrition / Tracking (not in OpenAPI spec — customer dashboard requirement) ───
        'nutrition' => [
            'today'         => '/nutrition/today',
            'weekly'        => '/nutrition/weekly',
            'weight_history' => '/weight-history',
            'activity_today' => '/activity/today',
        ],

        // ─── Reports (backend provides 5 basic endpoints; view data derived from these) ───
        'reports' => [
            'summary'       => '/reports/summary',
            'orders'        => '/reports/orders',
            'subscriptions' => '/reports/subscriptions',
            'deliveries'    => '/reports/deliveries',
            'revenue'       => '/reports/revenue',
        ],

        // ─── File Uploads ───
        'uploads' => [
            'images' => '/uploads/images',
        ],

        // ─── AI Chatbot ───
        'chatbot' => [
            'ask' => '/chatbot/ask',
        ],
    ],
];
