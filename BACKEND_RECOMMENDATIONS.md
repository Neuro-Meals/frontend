# Backend Implementation Recommendations

Frontend (Laravel/Nitromeals) is now fully wired to call the backend (FastAPI/Backend) with graceful mock fallback. The following backend endpoints are **missing** and need implementation for full end-to-end data flow. Until they exist, the frontend automatically falls back to mock data — no crashes.

## Priority 1 — Payments Module (`app/modules/payments/`)
`router.py` is currently **empty**. Needed endpoints:

| Method | Path | Purpose | Roles |
|---|---|---|---|
| GET | `/payments/` | List all payments (paginated, filter by status/method) | admin, super_admin |
| GET | `/payments/{payment_id}` | Payment detail | admin, owner |
| POST | `/payments/` | Record/initiate payment for an order | customer |
| POST | `/payments/{payment_id}/refund` | Process refund | admin, super_admin |
| GET | `/payments/my` | Current user's payment history | authenticated |

Frontend impact: `AdminController::payments()` currently uses mock only. Once done, add `payments` keys to `config/api.php` and create `PaymentApiService`.

## Priority 2 — Reports Module (`/reports/*`)
Frontend `ReportsApiService` + `config/api.php` already define **31 report endpoints** (dashboard, revenue, delivery, subscriptions, notifications, audit). All admin report pages now call them with mock fallback. Backend needs a `reports` module with routers matching these paths, e.g.:

- `/reports/dashboard/kpis`, `/reports/dashboard/revenue-trend`, `/reports/dashboard/subscription-funnel`, `/reports/dashboard/delivery-sla`, `/reports/dashboard/exceptions`, `/reports/dashboard/operational-metrics`
- `/reports/revenue/kpis`, `/trend`, `/payment-trends`, `/refund-volume`, `/payment-methods`, `/by-plan`
- `/reports/delivery/kpis`, `/on-time-trend`, `/zone-performance`, `/exception-reasons`, `/driver-productivity`, `/heatmap`
- `/reports/subscriptions/kpis`, `/new-vs-churn`, `/renewal-trend`, `/plan-ranking`, `/goal-distribution`, `/corporate-metrics`
- `/reports/notifications/kpis`, `/send-volume`, `/channel-mix`, `/campaign-performance`, `/failed-diagnostics`
- `/reports/audit/kpis`, `/change-hotspots`, `/events`, `/export-history`

All restricted to admin/super_admin. Response shapes should match the mock structures in `AdminController` (see fallback arrays for exact keys).

## Priority 3 — Nutrition Tracking (`/nutrition/*`, `/weight/*`, `/activity/*`)
Frontend `NutritionApiService` calls these (used by user dashboard/nutrition pages):

| Method | Path | Purpose |
|---|---|---|
| GET | `/nutrition/today` | Today's macro/calorie totals for current user |
| GET | `/nutrition/weekly` | 7-day nutrition summary |
| GET | `/weight/history` | Weight log history |
| POST | `/weight/` | Log weight entry |
| GET | `/activity/today` | Today's activity/steps |

## Priority 4 — Meal Schedule (`/meal-schedule/*`)
Frontend `MealScheduleApiService` calls:

| Method | Path | Purpose |
|---|---|---|
| GET | `/meal-schedule/my` | Current user's full meal schedule |
| GET | `/meal-schedule/today` | Today's meals for current user |

Could be derived from subscription + plan items + delivery dates.

## Priority 5 — Backend Data Enrichment (existing endpoints)
The frontend admin pages expect these fields that the current backend responses may not include. Add via joins/computed fields in response schemas:

- **`GET /users/`**: `orders_count`, `total_spent`, nested `subscription {plan_name, status}` per user (customers page)
- **`GET /orders/`**: nested `user {first_name, last_name}`, `plan_name`, `order_number` (orders/dashboard pages)
- **`GET /deliveries/`**: `driver_name`, `zone`, nested `user`, `eta` (deliveries page)
- **`GET /meals/`**: `orders_count`, `rating`, nested `category {name_en}` (meals page)
- **`GET /meals/categories`**: `meals_count` per category
- **`GET /plans/`**: `subscribers_count`, `total_meals` per plan (subscriptions page)
- **`GET /subscriptions/`**: support `status` query filter (used by dashboard: `?status=active`)

## Priority 6 — Content/CMS + Settings (optional)
`AdminController::content()` and `::settings()` are mock-only. If needed:
- `/content/pages` CRUD for CMS pages
- `/settings` GET/PUT for company/delivery/payment configuration

## Already Complete (no action needed)
- Auth (8 endpoints), Users (4: me/list/show/update_role), RBAC (6), Profile (2)
- Meal Categories (5), Meals (5), Plans (5 + 3 plan-items), Subscriptions (6)
- Orders (6), Deliveries (8), Notifications (6)

All above are correctly mapped 1:1 between backend routers and `Nitromeals/config/api.php`.
