# Nutrio Meals - Backend API Requirements

This document maps the full OpenAPI 3.1.0 specification to the customer dashboard, lists missing fields, and defines missing endpoints needed to remove all hardcoded/mock data.

---

## API Specification Summary

The backend exposes the following resource groups:

- **Auth** - Login, register, verify email, forgot/reset password
- **Users** - User profiles and role management
- **RBAC** - Roles and permissions
- **Profile** - Authenticated user profile update
- **Meal Categories** - Meal categorization
- **Meals** - Meal catalog and management
- **Meal Plans** - Subscription plans
- **Subscriptions** - Customer subscriptions
- **Orders** - Orders derived from subscriptions
- **Deliveries** - Delivery scheduling and tracking
- **Notifications** - User notifications

Authentication is **OAuth2 Password Bearer** using `/auth/login` as the token URL.

---

## Existing Endpoints (From OpenAPI Spec)

### Auth

| Method | Endpoint | Purpose | Request Schema |
|--------|----------|---------|----------------|
| POST | `/auth/register` | Register new user | `UserCreate` |
| POST | `/auth/verify-email` | Verify email with OTP | `VerifyEmailOTP` |
| POST | `/auth/resend-verification-otp` | Resend OTP | `ResendVerificationOTP` |
| POST | `/auth/login` | Login and get token | `LoginRequest` |
| GET | `/auth/me` | Get logged-in user | - |
| POST | `/auth/forgot-password` | Request password reset | `ForgotPasswordRequest` |
| POST | `/auth/reset-password` | Reset password with OTP | `ResetPasswordRequest` |
| POST | `/auth/change-password` | Change password | `ChangePasswordRequest` |

### Users

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/users/me` | My profile |
| GET | `/users/` | List users (admin) |
| PATCH | `/users/{user_id}/role` | Update user role |

### Profile

| Method | Endpoint | Purpose | Request Schema |
|--------|----------|---------|----------------|
| GET | `/profile/` | Get profile | - |
| PUT | `/profile/` | Update profile | `ProfileUpdate` |

### Meal Categories

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/meal-categories/` | List categories |
| POST | `/meal-categories/` | Create category |
| GET | `/meal-categories/{category_id}` | Get category |
| PUT | `/meal-categories/{category_id}` | Update category |
| DELETE | `/meal-categories/{category_id}` | Delete category |

### Meals

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/meals/` | List meals |
| POST | `/meals/` | Create meal |
| GET | `/meals/{meal_id}` | Get meal |
| PUT | `/meals/{meal_id}` | Update meal |
| DELETE | `/meals/{meal_id}` | Delete meal |

### Meal Plans

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/plans/` | List plans |
| POST | `/plans/` | Create plan |
| GET | `/plans/{plan_id}` | Get plan |
| PUT | `/plans/{plan_id}` | Update plan |
| DELETE | `/plans/{plan_id}` | Delete plan |

### Subscriptions

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/subscriptions/` | Create subscription |
| GET | `/subscriptions/` | List subscriptions (admin) |
| GET | `/subscriptions/my` | My subscriptions |
| GET | `/subscriptions/{subscription_id}` | Get subscription |
| PATCH | `/subscriptions/{subscription_id}` | Admin update subscription |
| POST | `/subscriptions/{subscription_id}/cancel` | Cancel subscription |

### Orders

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/orders/from-subscription` | Create order from subscription |
| GET | `/orders/my` | My orders |
| GET | `/orders/` | List orders (admin) |
| GET | `/orders/{order_id}` | Get order |
| PATCH | `/orders/{order_id}/status` | Update order status |
| POST | `/orders/{order_id}/cancel` | Cancel order |

### Deliveries

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/deliveries/` | List deliveries (admin) |
| POST | `/deliveries/` | Create delivery |
| GET | `/deliveries/my` | My deliveries |
| GET | `/deliveries/driver/my` | Driver deliveries |
| GET | `/deliveries/{delivery_id}` | Get delivery |
| PATCH | `/deliveries/{delivery_id}/assign-driver` | Assign driver |
| PATCH | `/deliveries/{delivery_id}/status` | Update delivery status |
| PATCH | `/deliveries/{delivery_id}/location` | Update driver location |

### Notifications

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/notifications/` | List notifications (admin) |
| POST | `/notifications/` | Create notification |
| GET | `/notifications/my` | My notifications |
| GET | `/notifications/{notification_id}` | Get notification |
| PATCH | `/notifications/{notification_id}/read` | Mark as read |
| PATCH | `/notifications/my/read-all` | Mark all as read |

---

## Key Schemas Reference

### UserCreate (Register)

```json
{
  "first_name": "string",
  "last_name": "string",
  "email": "string",
  "phone": "string",
  "password": "string",
  "location": "string | null",
  "address": "string | null",
  "gender": "male | female | other | null",
  "age": "integer | null",
  "height_cm": "number | null",
  "weight_kg": "number | null",
  "fitness_goal": "weight_loss | muscle_gain | maintenance | healthy_lifestyle | null",
  "dietary_preference": "string | null",
  "allergies": ["string"] | null
}
```

### LoginRequest

```json
{
  "email": "string",
  "password": "string"
}
```

### TokenResponse

```json
{
  "access_token": "string",
  "token_type": "bearer",
  "user": "LoggedInUser"
}
```

### LoggedInUser

```json
{
  "id": "integer",
  "first_name": "string",
  "last_name": "string",
  "email": "string",
  "phone": "string",
  "role": "string",
  "permissions": ["string"],
  "gender": "string | null",
  "age": "integer | null",
  "height_cm": "number | null",
  "weight_kg": "number | null",
  "fitness_goal": "string | null",
  "dietary_preference": "string | null",
  "allergies": ["string"] | null,
  "subscription": "CurrentSubscriptionResponse | null"
}
```

### CurrentSubscriptionResponse (Inside LoggedInUser)

```json
{
  "id": "integer",
  "plan_id": "integer",
  "plan_name": "string | null",
  "status": "string",
  "payment_status": "string",
  "amount": "number",
  "start_date": "datetime | null",
  "end_date": "datetime | null"
}
```

### UserResponse (Profile)

```json
{
  "id": "integer",
  "first_name": "string",
  "last_name": "string",
  "email": "string",
  "phone": "string",
  "location": "string | null",
  "address": "string | null",
  "gender": "male | female | other | null",
  "age": "integer | null",
  "height_cm": "number | null",
  "weight_kg": "number | null",
  "fitness_goal": "weight_loss | muscle_gain | maintenance | healthy_lifestyle | null",
  "dietary_preference": "string | null",
  "allergies": ["string"] | null,
  "role": "UserRole",
  "is_active": "boolean",
  "is_verified": "boolean",
  "created_at": "datetime"
}
```

### ProfileUpdate

```json
{
  "first_name": "string | null",
  "last_name": "string | null",
  "phone": "string | null",
  "location": "string | null",
  "address": "string | null",
  "gender": "male | female | other | null",
  "age": "integer | null",
  "height_cm": "number | null",
  "weight_kg": "number | null",
  "fitness_goal": "weight_loss | muscle_gain | maintenance | healthy_lifestyle | null",
  "dietary_preference": "string | null",
  "allergies": ["string"] | null
}
```

### MealResponse

```json
{
  "id": "integer",
  "category_id": "integer",
  "name_en": "string",
  "name_ar": "string | null",
  "description_en": "string | null",
  "description_ar": "string | null",
  "calories": "number",
  "protein_g": "number",
  "carbs_g": "number",
  "fat_g": "number",
  "fiber_g": "number | null",
  "sugar_g": "number | null",
  "sodium_mg": "number | null",
  "price": "number",
  "image_url": "string | null",
  "ingredients": ["string"] | null,
  "allergens": ["string"] | null,
  "diet_tags": ["string"] | null,
  "is_available": "boolean",
  "created_at": "datetime"
}
```

### MealPlanResponse

```json
{
  "id": "integer",
  "name_en": "string",
  "name_ar": "string | null",
  "description_en": "string | null",
  "description_ar": "string | null",
  "plan_type": "weekly | monthly | custom | family | corporate",
  "goal": "weight_loss | muscle_gain | maintenance | healthy_lifestyle | null",
  "price": "number",
  "duration_days": "integer",
  "meals_per_day": "integer",
  "total_meals": "integer",
  "image_url": "string | null",
  "is_active": "boolean",
  "created_at": "datetime"
}
```

### SubscriptionCreate

```json
{
  "plan_id": "integer",
  "notes": "string | null"
}
```

### SubscriptionResponse

```json
{
  "id": "integer",
  "user_id": "integer",
  "plan_id": "integer",
  "status": "pending_payment | active | paused | cancelled | expired",
  "payment_status": "unpaid | pending | paid | failed | refunded",
  "amount": "number",
  "start_date": "datetime | null",
  "end_date": "datetime | null",
  "notes": "string | null",
  "created_at": "datetime"
}
```

### OrderFromSubscriptionCreate

```json
{
  "subscription_id": "integer",
  "delivery_address": "string | null",
  "delivery_notes": "string | null"
}
```

### OrderResponse

```json
{
  "id": "integer",
  "user_id": "integer",
  "subscription_id": "integer | null",
  "plan_id": "integer | null",
  "order_number": "string",
  "status": "pending | confirmed | preparing | ready_for_delivery | out_for_delivery | delivered | cancelled",
  "total_amount": "number",
  "delivery_date": "datetime | null",
  "delivery_address": "string | null",
  "delivery_notes": "string | null",
  "items": ["object"] | null,
  "created_at": "datetime"
}
```

### DeliveryResponse

```json
{
  "id": "integer",
  "order_id": "integer",
  "user_id": "integer",
  "driver_id": "integer | null",
  "status": "pending | assigned | picked_up | out_for_delivery | delivered | failed | cancelled",
  "delivery_address": "string",
  "delivery_notes": "string | null",
  "scheduled_at": "datetime | null",
  "picked_up_at": "datetime | null",
  "delivered_at": "datetime | null",
  "current_latitude": "number | null",
  "current_longitude": "number | null",
  "failure_reason": "string | null",
  "created_at": "datetime"
}
```

### NotificationResponse

```json
{
  "id": "integer",
  "user_id": "integer",
  "title": "string",
  "message": "string",
  "notification_type": "general | order | delivery | subscription | payment | promotion",
  "channel": "in_app | email | sms | whatsapp",
  "is_read": "boolean",
  "created_at": "datetime"
}
```

---

## Missing Endpoints Required

These endpoints are **not present** in the OpenAPI spec but are required for the customer dashboard.

### 1. Meal Schedule API

```http
GET /meal-schedule/my
GET /meal-schedule/my/today
```

**Purpose:** Provide today's scheduled meals and weekly schedule for the active subscription.

**Expected response:**

```json
{
  "today": [
    {
      "meal_id": 1,
      "name": "Grilled Chicken Bowl",
      "meal_time": "Lunch · 12:30",
      "status": "upcoming",
      "calories": 520,
      "protein_g": 45,
      "carbs_g": 55,
      "fat_g": 12,
      "image_url": "https://cdn.example.com/meals/1.jpg"
    }
  ],
  "weekly": [
    {
      "day": "Mon",
      "date": "2025-07-07",
      "calories": 1680,
      "meals": [
        { "meal_id": 1, "name": "Grilled Chicken Bowl", "meal_time": "Lunch", "status": "delivered" }
      ]
    }
  ]
}
```

### 2. Nutrition / Tracking API

```http
GET /nutrition/today
GET /nutrition/weekly
GET /weight-history
GET /activity/today
```

**Expected response for `/nutrition/today`:**

```json
{
  "calories": 1240,
  "calorie_target": 1800,
  "protein": 80,
  "protein_target": 140,
  "carbs": 130,
  "carbs_target": 200,
  "fat": 32,
  "fat_target": 55,
  "water": 6,
  "water_target": 8,
  "steps": 8420,
  "steps_target": 10000
}
```

**Expected response for `/weight-history`:**

```json
{
  "current_weight": 78.2,
  "start_weight": 82.5,
  "goal_weight": 75.0,
  "history": [
    { "week": "Week 1", "weight": 82.5 },
    { "week": "Week 8", "weight": 78.2 }
  ],
  "stats": {
    "lost": 4.3,
    "remaining": 3.2,
    "streak_days": 28,
    "adherence_rate": 92,
    "avg_daily_calories": 1623
  }
}
```

---

## Missing Fields in Existing Schemas

### SubscriptionResponse

Add these fields so the frontend can calculate meals remaining, progress, and display plan name without extra API calls:

```json
{
  "plan_name": "string",
  "meals_consumed": "integer",
  "meals_total": "integer",
  "meals_per_day": "integer",
  "plan_duration_days": "integer",
  "plan_calories": "integer"
}
```

### MealResponse

Add these fields for the dashboard and meals page:

```json
{
  "meal_time": "Breakfast | Lunch | Dinner | Snack",
  "status": "delivered | upcoming | completed",
  "date": "date | null",
  "order_count": "integer",
  "favorite_count": "integer",
  "category_name": "string"
}
```

### UserResponse / ProfileUpdate

Add these fields for the settings page and nutrition calculations:

```json
{
  "date_of_birth": "date | null",
  "activity_level": "low | moderate | high | null",
  "delivery_zone": "string | null"
}
```

### OrderResponse

Add these fields for a richer orders page:

```json
{
  "plan_name": "string | null",
  "meals": "integer | null",
  "eta": "string | null",
  "driver_name": "string | null"
}
```

### DeliveryResponse

Add these fields for the customer delivery page:

```json
{
  "driver_name": "string | null",
  "time_slot": "string | null",
  "zone": "string | null",
  "meals": "integer | null"
}
```

---

## Customer Dashboard Mapping

| Page | Current Status | APIs to Use | Missing Pieces |
|------|---------------|-------------|----------------|
| Dashboard | Partially API-driven | `/auth/me`, `/subscriptions/my`, `/meals/`, `/plans/` | Daily stats, recent orders |
| Subscriptions | API-driven | `/subscriptions/my`, `/plans/`, `POST /subscriptions/` | `plan_name`, `meals_consumed`, `meals_total` in subscription |
| Meals | API-driven | `/meals/`, `/subscriptions/my`, `/plans/{id}` | Meal schedule endpoint, `meal_time`, `status` on meals |
| Orders | Mock data | `GET /orders/my`, `POST /orders/from-subscription` | Integrate with real endpoint |
| Delivery | Mock data | `GET /deliveries/my` | Integrate with real endpoint, add driver/time fields |
| Nutrition | Mock data | `GET /nutrition/today`, `GET /weight-history` | Missing endpoints |
| Notifications | Mock data | `GET /notifications/my`, `PATCH /notifications/{id}/read` | Missing endpoint integration |
| Settings | API-driven | `GET /profile/`, `PUT /profile/` | Add missing profile fields |

---

## Frontend Service Classes

Each customer feature has a dedicated service class under `app/Services/Api/`:

| Service | Status | Endpoints Used |
|---------|--------|---------------|
| `AuthApiService` | Created | `/auth/*` |
| `ProfileApiService` | Created | `/profile/`, `/users/me` |
| `MealApiService` | Created | `/meals/*`, `/meal-categories/*` |
| `MealScheduleApiService` | Created | `/meal-schedule/*` |
| `PlanApiService` | Created | `/plans/*` |
| `SubscriptionApiService` | Created | `/subscriptions/*` |
| `OrderApiService` | Created | `/orders/*` |
| `DeliveryApiService` | Created | `/deliveries/*` |
| `NotificationApiService` | Created | `/notifications/*` |
| `NutritionApiService` | Created | `/nutrition/*`, `/weight-history`, `/activity/*` |

---

## Backend Implementation Checklist

### High Priority

- [ ] Return `plan_name`, `meals_consumed`, `meals_total`, `meals_per_day` in `SubscriptionResponse`
- [ ] Return `category_name`, `meal_time`, `status`, `date` in `MealResponse`
- [ ] Add `date_of_birth`, `activity_level`, `delivery_zone` to `UserResponse` and `ProfileUpdate`
- [ ] Implement `GET /orders/my` and ensure `OrderResponse` returns `plan_name` and `meals`
- [ ] Implement `GET /deliveries/my` with customer-friendly fields (`driver_name`, `time_slot`, `zone`)
- [ ] Implement `GET /notifications/my` and `PATCH /notifications/{id}/read`

### Medium Priority

- [ ] Implement `GET /meal-schedule/my` and `GET /meal-schedule/my/today`
- [ ] Implement `GET /nutrition/today` and `GET /weight-history`
- [ ] Implement `POST /orders/from-subscription` for order creation from active subscription

### Low Priority

- [ ] Add `order_count` and `favorite_count` to `MealResponse`
- [ ] Add `eta` and `driver_name` to `OrderResponse`
- [ ] Add notification preferences endpoints

---

## Notes for Backend Team

1. Use consistent `snake_case` field names across all responses.
2. Return `null` for missing fields instead of omitting keys.
3. Use ISO 8601 for all date and datetime fields.
4. Return absolute URLs for all image assets.
5. Paginated list endpoints should return `data`, `total`, `page`, `limit`, and `pages`.
6. Use standard HTTP status codes: `200`, `201`, `400`, `401`, `403`, `422`, `500`.
7. Ensure authenticated endpoints validate the `Authorization: Bearer <token>` header.
