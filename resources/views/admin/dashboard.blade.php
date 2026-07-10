@extends('layouts.admin')

@section('title', __('Admin Dashboard') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Dashboard Overview'))

@section('content')
<div
    x-data="dashboardApp()"
    x-init="init()"
    class="space-y-4 relative"
>

@php
    $fmt = fn($n) => $n >= 1000000 ? number_format($n/1000000, 2).'M' : ($n >= 1000 ? number_format($n/1000, 1).'K' : number_format($n));
    $revGrowth = $stats['lastMonthRevenue'] > 0
        ? round(($stats['monthlyRevenue'] - $stats['lastMonthRevenue']) / $stats['lastMonthRevenue'] * 100, 1)
        : 0;
    $statusColors = [
        'delivered' => 'bg-green-50 text-green-700 border-green-200',
        'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'out_for_delivery' => 'bg-blue-50 text-blue-700 border-blue-200',
        'en_route' => 'bg-blue-50 text-blue-700 border-blue-200',
        'ready_for_delivery' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        'preparing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
        'cancelled' => 'bg-red-50 text-red-700 border-red-200',
    ];
    $statusLabels = [
        'delivered' => __('Delivered'),
        'confirmed' => __('Confirmed'),
        'out_for_delivery' => __('Out for Delivery'),
        'en_route' => __('En Route'),
        'ready_for_delivery' => __('Ready'),
        'preparing' => __('Preparing'),
        'pending' => __('Pending'),
        'cancelled' => __('Cancelled'),
    ];
    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
@endphp

{{-- Skeleton Loading --}}
<div x-show="loading" x-cloak class="space-y-4 animate-pulse">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <template x-for="i in 4" :key="i">
            <div class="h-32 bg-gray-100 rounded-2xl"></div>
        </template>
    </div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <template x-for="i in 4" :key="i">
            <div class="h-16 bg-gray-100 rounded-xl"></div>
        </template>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 h-80 bg-gray-100 rounded-2xl"></div>
        <div class="h-80 bg-gray-100 rounded-2xl"></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 h-64 bg-gray-100 rounded-2xl"></div>
        <div class="h-64 bg-gray-100 rounded-2xl"></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 h-64 bg-gray-100 rounded-2xl"></div>
        <div class="h-64 bg-gray-100 rounded-2xl"></div>
    </div>
</div>

{{-- Real Content --}}
<div x-show="!loading" x-cloak>

{{-- KPI Cards Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Revenue --}}
    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20" style="animation-delay: 0.1s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-white/90 bg-white/15 px-2 py-1 rounded-full">{{ $revGrowth >= 0 ? '+' : '' }}{{ $revGrowth }}%</span>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Monthly Revenue') }}</p>
            <p class="text-2xl font-bold tracking-tight">SAR {{ $fmt($stats['monthlyRevenue']) }}</p>
            <p class="text-xs text-white/50 mt-1">{{ __('vs') }} SAR {{ $fmt($stats['lastMonthRevenue']) }} {{ __('last mo.') }}</p>
        </div>
    </div>

    {{-- Active Subscriptions --}}
    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20" style="animation-delay: 0.2s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <span class="text-xs font-bold text-white/90 bg-white/15 px-2 py-1 rounded-full">+12.4%</span>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Active Subscriptions') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ number_format($stats['activeSubscriptions']) }}</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['retentionRate'] }}% {{ __('retention rate') }}</p>
        </div>
    </div>

    {{-- Orders Today --}}
    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20" style="animation-delay: 0.3s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-bold text-white/90 bg-white/15 px-2 py-1 rounded-full">+8.2%</span>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Orders Today') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['ordersToday'] }}</p>
            <p class="text-xs text-white/50 mt-1">{{ __('Avg.') }} SAR {{ $stats['avgOrderValue'] }} / {{ __('order') }}</p>
        </div>
    </div>

    {{-- Payment Success --}}
    @php
        $successPct = $stats['successRate'] ?? 0;
        $claimPct = $stats['claimRate'] ?? 0;
        $srRadius = 36;
        $srCircumference = 2 * pi() * $srRadius;
        $srDash = $srCircumference * ($successPct / 100);
        $srGap = $srCircumference - $srDash;
        $srColor = $successPct >= 80 ? '#22c55e' : ($successPct >= 50 ? '#f59e0b' : '#ef4444');
    @endphp
    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20" style="animation-delay: 0.4s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-4">
                <div class="relative w-20 h-20 flex-shrink-0">
                    <svg class="w-20 h-20 transform -rotate-90">
                        <circle cx="40" cy="40" r="{{ $srRadius }}" stroke="rgba(255,255,255,0.15)" stroke-width="8" fill="none"/>
                        <circle cx="40" cy="40" r="{{ $srRadius }}" stroke="{{ $srColor }}" stroke-width="8" fill="none"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $srDash }} {{ $srGap }}"
                            class="transition-all duration-700 ease-out"/>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-lg font-bold leading-none">{{ $successPct }}%</span>
                        <span class="text-[9px] text-white/70">{{ __('success') }}</span>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-white/60 font-medium mb-0.5">{{ __('Payment Success') }}</p>
                    <p class="text-2xl font-bold tracking-tight">{{ $successPct }}%</p>
                    <div class="flex flex-wrap items-center gap-1.5 mt-2 text-[10px] text-white/80">
                        <span class="px-1.5 py-0.5 rounded bg-white/10">{{ $stats['paymentCounts']['paid'] + $stats['paymentCounts']['captured'] }} {{ __('paid') }}</span>
                        <span class="px-1.5 py-0.5 rounded bg-white/10">{{ $stats['pendingPayments'] }} {{ __('pending') }}</span>
                    </div>
                    <div class="mt-2 h-1 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-white/80" style="width: {{ $successPct }}%"></div>
                    </div>
                    <p class="text-[10px] text-white/50 mt-1">{{ $claimPct }}% {{ __('claim rate') }}</p>
                </div>
            </div>
        </div>
    </div>

    </div>

{{-- Secondary KPI Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="kpi-card animate__animated animate__fadeInUp bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center gap-3" style="animation-delay: 0.5s;">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 font-medium">{{ __('Total Customers') }}</p>
            <p class="text-base font-bold tracking-tight text-[#173327]">{{ number_format($stats['totalCustomers']) }}</p>
        </div>
    </div>
    <div class="kpi-card animate__animated animate__fadeInUp bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center gap-3" style="animation-delay: 0.6s;">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 font-medium">{{ __('Deliveries Today') }}</p>
            <p class="text-base font-bold tracking-tight text-[#173327]">{{ $stats['deliveriesToday'] }}</p>
        </div>
    </div>
    <div class="kpi-card animate__animated animate__fadeInUp bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center gap-3" style="animation-delay: 0.7s;">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 font-medium">{{ __('Total Meals') }}</p>
            <p class="text-base font-bold tracking-tight text-[#173327]">{{ $stats['totalMeals'] }}</p>
        </div>
    </div>
    {{-- Retention & Churn Mini Card --}}
    @php
        $churnPct = $stats['churnRate'] ?? 0;
        $retPct = $stats['retentionRate'] ?? 0;
        $churnColor = $churnPct > 20 ? 'text-red-600' : ($churnPct > 10 ? 'text-amber-600' : 'text-green-600');
    @endphp
    <div class="kpi-card animate__animated animate__fadeInUp bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex flex-col justify-center gap-1" style="animation-delay: 0.8s;">
        <div class="flex items-center justify-between">
            <p class="text-[10px] text-gray-400 font-medium">{{ __('Retention') }}</p>
            <span class="text-[10px] font-bold {{ $churnColor }}">{{ $churnPct }}% {{ __('churn') }}</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden flex-1">
                <div class="h-full rounded-full bg-gradient-to-r from-[#173327] to-[#6E7A25]" style="width: {{ $retPct }}%"></div>
            </div>
            <p class="text-base font-bold tracking-tight text-[#173327]">{{ $retPct }}%</p>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900">{{ __('Revenue') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Trend') }}</span></h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('Last 14 days performance') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#6E7A25]"></span>
                <span class="text-xs text-gray-500">{{ __('Daily Revenue') }}</span>
            </div>
        </div>
        @php
            $revMax = !empty($revenueTrend) ? (max($revenueTrend) ?: 1) : 1;
            $revTotal = !empty($revenueTrend) ? array_sum($revenueTrend) : 0;
            $revenueLabels = [];
            for ($i = 0; $i < count($revenueTrend); $i++) {
                $revenueLabels[] = \Carbon\Carbon::parse('now')->subDays(count($revenueTrend) - 1 - $i)->format('d/m');
            }
        @endphp
        @if(!empty($revenueTrend))
        <div class="relative h-56 mb-4">
            <canvas id="dashboardRevenueChart"></canvas>
        </div>
        @else
        <div class="h-56 mb-4 flex flex-col items-center justify-center text-center rounded-xl bg-gray-50/50 border border-dashed border-gray-200">
            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
            <p class="text-xs text-gray-400">{{ __('No revenue data available') }}</p>
        </div>
        @endif
        <div class="mt-5 pt-4 border-t border-gray-50 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">{{ __('Total (14 days)') }}</p>
                <p class="text-lg font-bold text-gray-900">SAR {{ number_format($revTotal) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">{{ __('Avg / Day') }}</p>
                <p class="text-lg font-bold text-gray-900">SAR {{ number_format($revTotal / 14, 0) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400">{{ __('Best Day') }}</p>
                <p class="text-lg font-bold text-green-600">SAR {{ number_format($revMax) }}</p>
            </div>
        </div>
    </div>

    {{-- Plan Distribution --}}
    @php $totalPlans = array_sum(array_column($planDistribution, 'count')); @endphp
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 1.0s;">
        <div class="mb-4">
            <h3 class="text-base font-bold text-gray-900">{{ __('Plan') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Distribution') }}</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('Active subscriptions by plan') }}</p>
        </div>

        @if(!empty($planDistribution))
        <div class="grid grid-cols-2 gap-4 mb-4 max-h-[14.5rem] overflow-y-auto pr-1">
            @foreach($planDistribution as $plan)
                @php
                    $pct = $totalPlans > 0 ? round($plan['count'] / $totalPlans * 100) : 0;
                    $radius = 32;
                    $circumference = 2 * pi() * $radius;
                    $dash = $circumference * ($pct / 100);
                    $gap = $circumference - $dash;
                @endphp
                <div class="flex flex-col items-center text-center p-3 rounded-xl bg-gray-50/50 border border-gray-100 hover:shadow-sm transition-all">
                    <div class="relative w-20 h-20 mb-2">
                        <svg class="w-20 h-20 transform -rotate-90">
                            <circle cx="40" cy="40" r="{{ $radius }}" stroke="#f3f4f6" stroke-width="8" fill="none"/>
                            <circle cx="40" cy="40" r="{{ $radius }}" stroke="{{ $plan['color'] }}" stroke-width="8" fill="none"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $dash }} {{ $gap }}"
                                class="transition-all duration-700 ease-out"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm font-bold text-gray-900">{{ $pct }}%</span>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-gray-800 truncate w-full">{{ $plan['name'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ number_format($plan['count']) }} {{ __('subs') }}</p>
                </div>
            @endforeach
        </div>
        @else
        <div class="h-52 mb-4 flex flex-col items-center justify-center text-center rounded-xl bg-gray-50/50 border border-dashed border-gray-200">
            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
            <p class="text-xs text-gray-400">{{ __('No plan data available') }}</p>
        </div>
        @endif

        <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400">{{ __('Total Active') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalPlans }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400">{{ __('Plans') }}</p>
                <p class="text-lg font-bold text-[#6E7A25]">{{ count($planDistribution) }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Orders Trend + Delivery Zones --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Orders Trend --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 1.1s;">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-gray-900">{{ __('Orders') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('This Week') }}</span></h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('Daily order volume') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#173327]"></span>
                <span class="text-xs text-gray-500">{{ __('Orders') }}</span>
            </div>
        </div>
        @if(!empty($ordersTrend))
        <div class="relative h-56">
            <canvas id="dashboardOrdersChart"></canvas>
        </div>
        @else
        <div class="h-56 flex flex-col items-center justify-center text-center rounded-xl bg-gray-50/50 border border-dashed border-gray-200">
            <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6M19 19v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6M9 19h6M7 11V7a2 2 0 012-2h6a2 2 0 012 2v4"/></svg>
            <p class="text-xs text-gray-400">{{ __('No orders data available') }}</p>
        </div>
        @endif
    </div>

    {{-- Delivery Zones --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 1.2s;">
        <div class="mb-5">
            <h3 class="text-base font-bold text-gray-900">{{ __('Delivery') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Zones') }}</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ __("Today's distribution") }}</p>
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
                            <p class="text-[10px] text-gray-400 mt-0.5">{{ $zone['drivers'] }} {{ __('drivers active') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">{{ $zone['orders'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ __('orders') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Recent Orders + Top Meals --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 1.3s;">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">{{ __('Recent') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Orders') }}</span></h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('Latest customer transactions') }}</p>
            </div>
            <a href="{{ route('admin.orders') }}" class="text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-3 py-1.5 rounded-lg hover:shadow-md hover:shadow-[#6E7A25]/20 transition-all">{{ __('View All') }} →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-3 font-medium">{{ __('Order ID') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Customer') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Plan') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Amount') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Status') }}</th>
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
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 1.4s;">
        <div class="mb-5">
            <h3 class="text-base font-bold text-gray-900">{{ __('Top') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Meals') }}</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('Best performing this month') }}</p>
        </div>
        <div class="space-y-3">
            @foreach($topMeals as $i => $meal)
                <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-gray-50 transition-colors">
                    @if($meal['image'])
                    <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200">
                        <img src="{{ $meal['image'] }}" alt="{{ $meal['name'] }}" class="w-full h-full object-cover">
                    </div>
                    @else
                    <div class="w-12 h-12 rounded-xl {{ $i === 0 ? 'bg-gradient-to-br from-amber-300 to-amber-500 text-white' : ($i === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-500 text-white' : ($i === 2 ? 'bg-gradient-to-br from-orange-400 to-orange-600 text-white' : 'bg-gray-50 text-gray-400')) }} flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                        {{ $i + 1 }}
                    </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-900 truncate">{{ $meal['name'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $meal['orders'] }} {{ __('orders') }} · SAR {{ number_format($meal['revenue']) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@php
    $paymentStatusColors = [
        'paid' => 'bg-green-50 text-green-700 border-green-200',
        'captured' => 'bg-green-50 text-green-700 border-green-200',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
        'unpaid' => 'bg-gray-50 text-gray-600 border-gray-200',
        'failed' => 'bg-red-50 text-red-600 border-red-200',
        'refunded' => 'bg-orange-50 text-orange-600 border-orange-200',
        'disputed' => 'bg-purple-50 text-purple-700 border-purple-200',
        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
    ];
    $paymentStatusLabels = [
        'paid' => __('Paid'), 'captured' => __('Captured'), 'pending' => __('Pending'),
        'unpaid' => __('Unpaid'), 'failed' => __('Failed'), 'refunded' => __('Refunded'),
        'disputed' => __('Disputed'), 'cancelled' => __('Cancelled'),
    ];
    $fmtDate = fn($d) => !empty($d) ? date('M d, H:i', strtotime($d)) : '—';
@endphp

{{-- Payment Status Summary Cards --}}
@php
    $pc = $stats['paymentCounts'] ?? [];
    $paymentStatusCards = [
        ['key' => 'paid', 'label' => __('Paid'), 'icon' => 'M9 12l2 2 4-4', 'color' => 'from-green-500 to-emerald-600', 'light' => 'bg-green-50'],
        ['key' => 'pending', 'label' => __('Pending'), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'from-amber-500 to-orange-500', 'light' => 'bg-amber-50'],
        ['key' => 'failed', 'label' => __('Failed'), 'icon' => 'M6 18L18 6M6 6l12 12', 'color' => 'from-red-500 to-red-600', 'light' => 'bg-red-50'],
        ['key' => 'refunded', 'label' => __('Refunded'), 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'color' => 'from-orange-500 to-orange-600', 'light' => 'bg-orange-50'],
        ['key' => 'disputed', 'label' => __('Disputed'), 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'from-purple-500 to-purple-600', 'light' => 'bg-purple-50'],
        ['key' => 'cancelled', 'label' => __('Cancelled'), 'icon' => 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636', 'color' => 'from-gray-500 to-gray-600', 'light' => 'bg-gray-50'],
    ];
@endphp
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    @foreach($paymentStatusCards as $card)
        @php
            $count = $pc[$card['key']] ?? 0;
            $total = max(1, array_sum($pc) - ($pc['other'] ?? 0));
            $pct = round(($count / $total) * 100, 1);
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm animate__animated animate__fadeInUp hover:shadow-md transition-all" style="animation-delay: {{ 0.9 + $loop->index * 0.05 }}s;">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $card['color'] }} flex items-center justify-center text-white shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                </div>
                <span class="text-xs font-bold text-gray-700 bg-gray-50 px-2 py-1 rounded-full">{{ $pct }}%</span>
            </div>
            <p class="text-2xl font-bold text-gray-900">{{ number_format($count) }}</p>
            <p class="text-[11px] text-gray-400 font-medium">{{ $card['label'] }}</p>
            <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r {{ $card['color'] }}" style="width: {{ $pct }}%"></div>
            </div>
        </div>
    @endforeach
</div>

{{-- Recent Payments --}}
<div class="grid grid-cols-1 gap-6 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 1.5s;">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">{{ __('Recent') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Payments') }}</span></h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('Latest payments from payment API') }}</p>
            </div>
            <a href="{{ route('admin.payments') }}" class="text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-3 py-1.5 rounded-lg hover:shadow-md hover:shadow-[#6E7A25]/20 transition-all">{{ __('View All') }} →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-50">
                        <th class="px-6 py-3 font-medium">{{ __('Payment ID') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Customer') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Plan') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Amount') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Provider') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $payment)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                        <td class="px-6 py-3">
                            <span class="text-xs font-bold text-gray-900">#{{ $payment['id'] }}</span>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">
                                    {{ strtoupper(substr($payment['customer'], 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-700">{{ $payment['customer'] }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $payment['customer_email'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-xs text-gray-500">{{ $payment['plan'] }}</td>
                        <td class="px-6 py-3">
                            <span class="text-xs font-bold text-gray-900">{{ $payment['currency'] }} {{ number_format($payment['amount'], 2) }}</span>
                        </td>
                        <td class="px-6 py-3 text-xs text-gray-500 capitalize">{{ $payment['provider'] }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $paymentStatusColors[$payment['status']] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ $paymentStatusLabels[$payment['status']] ?? ucfirst($payment['status']) }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-xs text-gray-500">{{ $fmtDate($payment['paid_at']) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-xs text-gray-400">{{ __('No recent payments found.') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- System Status Bar --}}
<div class="bg-gradient-to-r from-[#173327] via-[#122620] to-[#173327] rounded-2xl p-5 text-white relative overflow-hidden shadow-xl animate__animated animate__fadeInUp" style="animation-delay: 1.5s;">
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
                    <p class="text-sm font-bold">{{ __('All Systems Operational') }}</p>
                </div>
                <p class="text-xs text-white/50 mt-0.5">{{ __('Last updated') }} {{ now()->format('M d, Y H:i') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p class="text-[10px] text-white/40 uppercase tracking-wider">{{ __('Uptime') }}</p>
                <p class="text-sm font-bold">99.98%</p>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div class="text-right">
                <p class="text-[10px] text-white/40 uppercase tracking-wider">{{ __('Response') }}</p>
                <p class="text-sm font-bold">124ms</p>
            </div>
            <div class="w-px h-8 bg-white/10"></div>
            <div class="text-right">
                <p class="text-[10px] text-white/40 uppercase tracking-wider">{{ __('API Status') }}</p>
                <p class="text-sm font-bold text-green-400">{{ __('Healthy') }}</p>
            </div>
        </div>
    </div>
</div>

</div>

{{-- ═══════════════════════════════════════════════════════════════ --}}

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = "'Nunito', sans-serif";
    Chart.defaults.color = '#9ca3af';

    const commonChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#173327',
                titleColor: '#fff',
                bodyColor: '#fff',
                padding: 10,
                cornerRadius: 8,
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { font: { size: 10 } } }
        }
    };

    // Revenue Trend - gradient area chart
    @if(!empty($revenueTrend))
    new Chart(document.getElementById('dashboardRevenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenueLabels ?? []),
            datasets: [{
                label: '{{ __('Daily Revenue') }}',
                data: @json($revenueTrend ?? []),
                borderColor: '#6E7A25',
                backgroundColor: (ctx) => {
                    const canvas = ctx.chart.ctx;
                    const gradient = canvas.createLinearGradient(0, 0, 0, 220);
                    gradient.addColorStop(0, 'rgba(110, 122, 37, 0.25)');
                    gradient.addColorStop(1, 'rgba(110, 122, 37, 0.0)');
                    return gradient;
                },
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6E7A25',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            ...commonChartOptions,
            plugins: {
                ...commonChartOptions.plugins,
                tooltip: {
                    ...commonChartOptions.plugins.tooltip,
                    callbacks: {
                        label: (ctx) => 'SAR ' + Number(ctx.raw).toLocaleString()
                    }
                }
            }
        }
    });
    @endif

    // Orders This Week - rounded bar chart
    @if(!empty($ordersTrend))
    new Chart(document.getElementById('dashboardOrdersChart'), {
        type: 'bar',
        data: {
            labels: @json($days ?? []),
            datasets: [{
                label: '{{ __('Orders') }}',
                data: @json($ordersTrend ?? []),
                backgroundColor: '#173327',
                hoverBackgroundColor: '#6E7A25',
                borderRadius: 6,
            }]
        },
        options: commonChartOptions
    });
    @endif

    function dashboardApp() {
        return {
            loading: true,
            init() {
                // Small delay to show skeleton state even when server-rendered quickly
                setTimeout(() => { this.loading = false; }, 400);
            }
        };
    }
</script>
@endpush

</div>

@endsection
