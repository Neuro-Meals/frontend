# Nutrio Meals - Backend API Requirements

This document lists all API endpoints currently used by the customer dashboard, plus the missing endpoints and fields required to eliminate hardcoded/mock data.

---

## Existing API Endpoints Used

| Section | Method | Endpoint | Service | Purpose |
|---------|--------|----------|---------|---------|
| Auth | POST | `/auth/login` | AuthApiService | Login + get token |
| Auth | POST | `/auth/register` | AuthApiService | Register new user |
| Auth | POST | `/auth/verify-email` | AuthApiService | Verify email with OTP |
| Auth | POST | `/auth/resend-verification-otp` | AuthApiService | Resend OTP |
| Auth | GET | `/auth/me` | AuthApiService | Get logged-in user |
| Profile | GET | `/profile/` | ProfileApiService | Get full profile |
| Profile | PUT | `/profile/` | ProfileApiService | Update profile |
| Meals | GET | `/meals/` | MealApiService | List meals |
| Plans | GET | `/plans/` | PlanApiService | List plans |
| Plans | GET | `/plans/{plan_id}` | PlanApiService | Plan details |
| Subscriptions | GET | `/subscriptions/my` | SubscriptionApiService | User subscriptions |
| Subscriptions | POST | `/subscriptions/` | SubscriptionApiService | Create subscription |

---

## Missing Endpoints / Fields Required

### 1. Orders API

Currently orders are derived from subscriptions. A dedicated orders endpoint is needed.

**Required endpoint:**

```http
GET /orders/my
```

**Expected JSON response:**

```json
{
  "data": [
    {
      "id": "ORD-2401",
      "subscription_id": 12,
      "plan_name": "Weight Loss Pro",
      "meals": 3,
      "amount": 420,
      "status": "delivered",
      "date": "2025-06-30T00:00:00Z"
    }
  ],
  "stats": {
    "total": 42,
    "delivered": 40,
    "cancelled": 2,
    "total_spent": 16800,
    "average_order": 400
  }
}
```

### 2. Deliveries API

Currently fully mocked. Required endpoints:

```http
GET /deliveries/my
GET /deliveries/{delivery_id}
```

**Expected JSON response:**

```json
{
  "upcoming": [
    {
      "id": "DLV-501",
      "order_id": "ORD-2401",
      "date": "2025-07-08",
      "time_slot": "09:00 - 10:00",
      "zone": "Riyadh Central",
      "driver": "Yousef",
      "status": "scheduled",
      "meals": 3
    }
  ],
  "history": [
    {
      "id": "DLV-498",
      "order_id": "ORD-2387",
      "date": "2025-07-07",
      "time": "09:15",
      "zone": "Riyadh Central",
      "driver": "Yousef",
      "status": "delivered",
      "eta": "On time"
    }
  ],
  "stats": {
    "total_deliveries": 40,
    "on_time_rate": 95,
    "avg_delivery_time": "32 min",
    "preferred_slot": "09:00 - 10:00"
  }
}
```

### 3. Notifications API

Currently fully mocked. Required endpoints:

```http
GET /notifications
PATCH /notifications/{id}/read
DELETE /notifications/{id}
GET /notification-preferences
PUT /notification-preferences
```

**Expected JSON response:**

```json
{
  "data": [
    {
      "id": 1,
      "title": "Delivery Tomorrow",
      "message": "Your meal delivery is scheduled for tomorrow 09:00 - 10:00",
      "type": "delivery",
      "read": false,
      "created_at": "2025-07-07T18:00:00Z"
    }
  ],
  "stats": {
    "unread": 2,
    "total": 48
  }
}
```

### 4. Meal Schedule API

Required for the meals page weekly schedule and today's meals.

```http
GET /meal-schedule/my
GET /meal-schedule/my/today
```

**Expected JSON response:**

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

### 5. Nutrition / Tracking API

Required for the nutrition tracker page.

```http
GET /nutrition/today
GET /nutrition/weekly
GET /weight-history
GET /activity/today
```

**Expected JSON response for `/nutrition/today`:**

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

**Expected JSON response for `/weight-history`:**

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

### 6. Missing Fields in Existing Endpoints

#### `/auth/me` and `/profile/` (UserResponse)

Add the following fields:

- `date_of_birth` (string, ISO date)
- `activity_level` (string, e.g. "low", "moderate", "high")
- `delivery_zone` (string)
- `is_verified` (boolean) - already exists in UserResponse, ensure it is returned

#### `/subscriptions/my` (SubscriptionResponse)

Add the following fields:

- `plan_name` (string)
- `meals_consumed` (integer)
- `meals_total` (integer)
- `meals_per_day` (integer)
- `plan_duration_days` (integer)
- `plan_calories` (integer)

#### `/meals/` (MealResponse)

Add the following fields:

- `meal_time` (string, e.g. "Breakfast", "Lunch", "Dinner")
- `status` (string, e.g. "delivered", "upcoming", "completed")
- `date` or `day_index` (for weekly schedule)
- `order_count` (integer)
- `favorite_count` (integer)
- `category_name` (string)

---

## Customer Dashboard Sections Summary

| Page | Status | API Used | Missing API |
|------|--------|----------|-------------|
| Dashboard | Uses API | `/auth/me`, `/subscriptions/my`, `/meals/`, `/plans/` | Daily stats, recent orders |
| Subscriptions | Uses API | `/subscriptions/my`, `/plans/` | `plan_name`, `meals_consumed` |
| Meals | Uses API | `/meals/`, `/subscriptions/my`, `/plans/{id}` | Meal schedule, meal_time, status |
| Nutrition | Uses API partially | `/meals/`, `/auth/me` | Nutrition tracking, weight history, activity |
| Orders | Uses API partially | `/subscriptions/my`, `/plans/` | Dedicated orders endpoint |
| Delivery | Mock only | `/subscriptions/my`, `/auth/me` (fallback) | Deliveries endpoint |
| Notifications | Mock only | `/auth/me` (fallback) | Notifications endpoint |
| Settings | Uses API | `/profile/` | `date_of_birth`, `activity_level`, `delivery_zone` |

---

## Notes for Backend Team

1. Return consistent `snake_case` field names.
2. All date/datetime fields should use ISO 8601 format.
3. Image URLs should be absolute and publicly accessible.
4. Include pagination metadata where lists can be large.
5. Use standard HTTP status codes (200, 201, 400, 401, 422, 500).
6. For missing fields, send `null` rather than omitting the key to keep frontend stable.
