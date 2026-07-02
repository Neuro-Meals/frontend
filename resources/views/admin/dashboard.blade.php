@extends('layouts.admin')

@section('title', 'Admin Dashboard - Nutrio Meals')
@section('page_title', 'Dashboard Overview')

@section('content')
@php
    $fmt = fn($n) => $n >= 1000000 ? number_format($n/1000000, 2).'M' : ($n >= 1000 ? number_format($n/1000, 1).'K' : number_format($n));
    $revGrowth = round(($stats['monthlyRevenue'] - $stats['lastMonthRevenue']) / $stats['lastMonthRevenue'] * 100, 1);
    $statusColors = [
        'delivered' => 'bg-green-50 text-green-700 border-green-200',
        'en_route' => 'bg-blue-50 text-blue-700 border-blue-200',
        'preparing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
    ];
    $statusLabels = [
        'delivered' => 'Delivered',
        'en_route' => 'En Route',
        'preparing' => 'Preparing',
        'pending' => 'Pending',
    ];
    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
@endphp

{{-- KPI Cards Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Revenue --}}
    <div class="kpi-card bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-white/90 bg-white/15 px-2 py-1 rounded-full">{{ $revGrowth >= 0 ? '+' : '' }}{{ $revGrowth }}%</span>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">Monthly Revenue</p>
            <p class="text-2xl font-bold tracking-tight">SAR {{ $fmt($stats['monthlyRevenue']) }}</p>
            <p class="text-xs text-white/50 mt-1">vs SAR {{ $fmt($stats['lastMonthRevenue']) }} last mo.</p>
        </div>
    </div>

    {{-- Active Subscriptions --}}
    <div class="kpi-card bg-gradient-to-br from-[#025C5F] to-[#033133] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#025C5F]/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <span class="text-xs font-bold text-white/90 bg-white/15 px-2 py-1 rounded-full">+12.4%</span>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">Active Subscriptions</p>
            <p class="text-2xl font-bold tracking-tight">{{ number_format($stats['activeSubscriptions']) }}</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['retentionRate'] }}% retention rate</p>
        </div>
    </div>

    {{-- Orders Today --}}
    <div class="kpi-card bg-gradient-to-br from-[#6E7A25] to-[#949B50] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-bold text-white/90 bg-white/15 px-2 py-1 rounded-full">+8.2%</span>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">Orders Today</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['ordersToday'] }}</p>
            <p class="text-xs text-white/50 mt-1">Avg. SAR {{ $stats['avgOrderValue'] }} / order</p>
        </div>
    </div>

    {{-- Payment Success --}}
    <div class="kpi-card bg-gradient-to-br from-[#1E8A00] to-[#259B00] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#1E8A00]/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <span class="text-xs font-bold text-white/90 bg-white/15 px-2 py-1 rounded-full">{{ $stats['successRate'] }}%</span>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">Payment Success</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['successRate'] }}%</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['pendingPayments'] }} pending payments</p>
        </div>
    </div>
</div>

{{-- Secondary KPI Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="kpi-card bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-400">Total Customers</p>
            <p class="text-lg font-bold text-gray-900">{{ number_format($stats['totalCustomers']) }}</p>
        </div>
    </div>
    <div class="kpi-card bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#025C5F] to-[#033133] flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-400">Deliveries Today</p>
            <p class="text-lg font-bold text-gray-900">{{ $stats['deliveriesToday'] }}</p>
        </div>
    </div>
    <div class="kpi-card bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#949B50] flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-400">Total Meals</p>
            <p class="text-lg font-bold text-gray-900">{{ $stats['totalMeals'] }}</p>
        </div>
    </div>
    <div class="kpi-card bg-white rounded-2xl border border-gray-100 p-4 flex items-center gap-3 shadow-sm">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#173327] to-[#033133] flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <p class="text-xs text-gray-400">Churn Rate</p>
            <p class="text-lg font-bold text-gray-900">{{ $stats['churnRate'] }}%</p>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900">Revenue <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Trend</span></h3>
                <p class="text-xs text-gray-400 mt-0.5">Last 14 days performance</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#6E7A25]"></span>
                <span class="text-xs text-gray-500">Daily Revenue</span>
            </div>
        </div>
        @php
            $revMax = max($revenueTrend) ?: 1;
            $revTotal = array_sum($revenueTrend);
        @endphp
        <div class="flex items-end gap-2 h-48">
            @foreach($revenueTrend as $i => $rev)
                @php $pct = min(100, ($rev / $revMax) * 100); $isToday = $i === count($revenueTrend)-1; @endphp
                <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                    <div class="w-full relative h-40 flex items-end">
                        <div class="w-full rounded-t-lg transition-all duration-300 group-hover:opacity-80 {{ $isToday ? 'bg-gradient-to-t from-[#6E7A25] to-[#6E7A25]/70' : 'bg-gradient-to-t from-[#6E7A25]/60 to-[#6E7A25]/30' }}" style="height: {{ max($pct, 4) }}%"></div>
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap pointer-events-none">
                            SAR {{ number_format($rev) }}
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse('now')->subDays(13-$i)->format('d/m') }}</span>
                </div>
            @endforeach
        </div>
        <div class="mt-5 pt-4 border-t border-gray-50 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">Total (14 days)</p>
                <p class="text-lg font-bold text-gray-900">SAR {{ number_format($revTotal) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Avg / Day</p>
                <p class="text-lg font-bold text-gray-900">SAR {{ number_format($revTotal / 14, 0) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">Best Day</p>
                <p class="text-lg font-bold text-green-600">SAR {{ number_format($revMax) }}</p>
            </div>
        </div>
    </div>

    {{-- Plan Distribution --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="mb-6">
            <h3 class="text-base font-bold text-gray-900">Plan <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Distribution</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">Active subscriptions by plan</p>
        </div>
        @php $totalPlans = array_sum(array_column($planDistribution, 'count')); @endphp
        <div class="space-y-4">
            @foreach($planDistribution as $plan)
                @php $pct = round($plan['count'] / $totalPlans * 100); @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-medium text-gray-700">{{ $plan['name'] }}</span>
                        <span class="text-xs font-bold text-gray-900">{{ $plan['count'] }}</span>
                    </div>
                    <div class="h-2.5 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 hover:opacity-80" style="width: {{ $pct }}%; background: {{ $plan['color'] }}; box-shadow: 0 0 8px {{ $plan['color'] }}40;"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $pct }}% of total</p>
                </div>
            @endforeach
        </div>
        <div class="mt-5 pt-4 border-t border-gray-50">
            <p class="text-xs text-gray-400">Total Active</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalPlans }}</p>
        </div>
    </div>
</div>

{{-- Orders Trend + Delivery Zones --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Orders Trend --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900">Orders <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">This Week</span></h3>
                <p class="text-xs text-gray-400 mt-0.5">Daily order volume</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#173327]"></span>
                <span class="text-xs text-gray-500">Orders</span>
            </div>
        </div>
        @php $ordMax = max($ordersTrend) ?: 1; @endphp
        <div class="flex items-end gap-3 h-40">
            @foreach($ordersTrend as $i => $ord)
                @php $pct = min(100, ($ord / $ordMax) * 100); @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                    <div class="w-full relative h-32 flex items-end">
                        <div class="w-full rounded-t-lg bg-gradient-to-t from-[#173327] to-[#173327]/70 transition-all duration-300 group-hover:opacity-80" style="height: {{ max($pct, 5) }}%"></div>
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md">
                            {{ $ord }}
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $days[$i] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Delivery Zones --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="mb-5">
            <h3 class="text-base font-bold text-gray-900">Delivery <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Zones</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">Today's distribution</p>
        </div>
        <div class="space-y-3">
            @foreach($deliveryZones as $zone)
                <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-gray-50 to-white border border-gray-100 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-900">{{ $zone['zone'] }}</p>
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $zone['drivers'] }} drivers active</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">{{ $zone['orders'] }}</p>
                        <p class="text-[10px] text-gray-400">orders</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent Orders + Top Meals --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Recent Orders --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Recent <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Orders</span></h3>
                <p class="text-xs text-gray-400 mt-0.5">Latest customer transactions</p>
            </div>
            <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-3 py-1.5 rounded-lg hover:shadow-md hover:shadow-[#6E7A25]/20 transition-all">View All →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-3 font-medium">Order ID</th>
                        <th class="px-6 py-3 font-medium">Customer</th>
                        <th class="px-6 py-3 font-medium">Plan</th>
                        <th class="px-6 py-3 font-medium">Amount</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                        <td class="px-6 py-3">
                            <span class="text-xs font-bold text-gray-900">{{ $order['id'] }}</span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">
                                    {{ strtoupper(substr($order['customer'], 0, 1)) }}
                                </div>
                                <span class="text-xs font-medium text-gray-700">{{ $order['customer'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-xs text-gray-500">{{ $order['plan'] }}</td>
                        <td class="px-6 py-3">
                            <span class="text-xs font-bold text-gray-900">SAR {{ $order['amount'] }}</span>
                        </td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$order['status']] }}">
                                {{ $statusLabels[$order['status']] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Meals --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="mb-5">
            <h3 class="text-base font-bold text-gray-900">Top <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Meals</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">Best performing this month</p>
        </div>
        <div class="space-y-3">
            @foreach($topMeals as $i => $meal)
                <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-9 h-9 rounded-xl {{ $i === 0 ? 'bg-gradient-to-br from-amber-300 to-amber-500 text-white' : ($i === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-500 text-white' : ($i === 2 ? 'bg-gradient-to-br from-orange-400 to-orange-600 text-white' : 'bg-gray-50 text-gray-400')) }} flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-900 truncate">{{ $meal['name'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $meal['orders'] }} orders · SAR {{ number_format($meal['revenue']) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- System Status Bar --}}
<div class="bg-gradient-to-r from-[#173327] via-[#122620] to-[#173327] rounded-2xl p-5 text-white relative overflow-hidden shadow-xl">
    <div class="absolute top-0 right-0 w-40 h-40 bg-[#6E7A25]/10 rounded-full -mr-20 -mt-20 blur-2xl"></div>
    <div class="absolute bottom-0 left-1/3 w-32 h-32 bg-[#6E7A25]/5 rounded-full blur-2xl"></div>
    <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center shadow-lg shadow-[#6E7A25]/30">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    <p class="text-sm font-bold">All Systems Operational</p>
                </div>
                <p class="text-xs text-white/50 mt-0.5">Last updated {{ now()->format('M d, Y H:i') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-[10px] text-white/40 uppercase tracking-wider">Uptime</p>
                <p class="text-sm font-bold">99.98%</p>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div class="text-right">
                <p class="text-[10px] text-white/40 uppercase tracking-wider">Response</p>
                <p class="text-sm font-bold">124ms</p>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div class="text-right">
                <p class="text-[10px] text-white/40 uppercase tracking-wider">API Status</p>
                <p class="text-sm font-bold text-green-400">Healthy</p>
            </div>
        </div>
    </div>
</div>
@endsection
