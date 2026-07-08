@extends('layouts.admin')

@section('title', __('Admin Dashboard') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Dashboard Overview'))

@section('content')
<div x-data="liveApp()" x-init="init()" class="space-y-4">

@php
    $fmt = fn($n) => $n >= 1000000 ? number_format($n/1000000, 2).'M' : ($n >= 1000 ? number_format($n/1000, 1).'K' : number_format($n));
    $revGrowth = $stats['lastMonthRevenue'] > 0
        ? round(($stats['monthlyRevenue'] - $stats['lastMonthRevenue']) / $stats['lastMonthRevenue'] * 100, 1)
        : 0;
    $statusColors = [
        'delivered' => 'bg-green-50 text-green-700 border-green-200',
        'en_route' => 'bg-blue-50 text-blue-700 border-blue-200',
        'preparing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
    ];
    $statusLabels = [
        'delivered' => __('Delivered'),
        'en_route' => __('En Route'),
        'preparing' => __('Preparing'),
        'pending' => __('Pending'),
    ];
    $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
@endphp

{{-- KPI Cards Row --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
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
    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20" style="animation-delay: 0.4s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <span class="text-xs font-bold text-white/90 bg-white/15 px-2 py-1 rounded-full">{{ $stats['successRate'] }}%</span>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Payment Success') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['successRate'] }}%</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['pendingPayments'] }} {{ __('pending payments') }}</p>
        </div>
    </div>

    {{-- Live Delivery Blinking Card --}}
    <div @click="openLiveModal()" class="kpi-card animate__animated animate__fadeInUp cursor-pointer bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-amber-500/30 group" style="animation-delay: 0.45s;">
        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-3">
                <div class="w-11 h-11 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center relative">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                    <span x-show="counts.unassigned > 0" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center text-[8px] font-bold text-white animate-ping"></span>
                    <span x-show="counts.unassigned > 0" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center text-[8px] font-bold text-white" x-text="counts.unassigned"></span>
                </div>
                <span class="text-[10px] font-bold text-white bg-white/20 px-2 py-1 rounded-full animate-pulse" x-text="`${counts.pending_deliveries} {{ __('active') }}`"></span>
            </div>
            <p class="text-xs text-white/70 font-medium mb-1">{{ __('Live Deliveries') }}</p>
            <p class="text-2xl font-bold tracking-tight" x-text="counts.pending_deliveries"></p>
            <p class="text-xs text-white/60 mt-1 flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse inline-block"></span>
                <span x-text="`${counts.unassigned} {{ __('unassigned') }}`"></span>
            </p>
        </div>
        <div class="absolute bottom-2 right-3 text-white/30 text-[9px] font-bold tracking-wider uppercase opacity-0 group-hover:opacity-100 transition-opacity">{{ __('Click to view') }} →</div>
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
    <div class="kpi-card animate__animated animate__fadeInUp bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center gap-3" style="animation-delay: 0.8s;">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
            <p class="text-[10px] text-gray-400 font-medium">{{ __('Churn Rate') }}</p>
            <p class="text-base font-bold tracking-tight text-[#173327]">{{ $stats['churnRate'] }}%</p>
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
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 1.0s;">
        <div class="mb-6">
            <h3 class="text-base font-bold text-gray-900">{{ __('Plan') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Distribution') }}</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('Active subscriptions by plan') }}</p>
        </div>
        @php $totalPlans = array_sum(array_column($planDistribution, 'count')); @endphp
        <div class="space-y-4">
            @foreach($planDistribution as $plan)
                @php $pct = $totalPlans > 0 ? round($plan['count'] / $totalPlans * 100) : 0; @endphp
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xs font-medium text-gray-700">{{ $plan['name'] }}</span>
                        <span class="text-xs font-bold text-gray-900">{{ $plan['count'] }}</span>
                    </div>
                    <div class="h-2.5 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500 hover:opacity-80" style="width: {{ $pct }}%; background: {{ $plan['color'] }}; box-shadow: 0 0 8px {{ $plan['color'] }}40;"></div>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $pct }}% {{ __('of total') }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-5 pt-4 border-t border-gray-50">
            <p class="text-xs text-gray-400">{{ __('Total Active') }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalPlans }}</p>
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
        @php $ordMax = !empty($ordersTrend) ? (max($ordersTrend) ?: 1) : 1; @endphp
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
                    <div class="w-9 h-9 rounded-xl {{ $i === 0 ? 'bg-gradient-to-br from-amber-300 to-amber-500 text-white' : ($i === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-500 text-white' : ($i === 2 ? 'bg-gradient-to-br from-orange-400 to-orange-600 text-white' : 'bg-gray-50 text-gray-400')) }} flex items-center justify-center text-sm font-bold flex-shrink-0 shadow-sm">
                        {{ $i + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-900 truncate">{{ $meal['name'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $meal['orders'] }} {{ __('orders') }} · SAR {{ number_format($meal['revenue']) }}</p>
                    </div>
                </div>
            @endforeach
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

{{-- ═══════════════════════════════════════════════════════════════ --}}
{{── LIVE DELIVERY BIG POPUP ──}}
{{-- ═══════════════════════════════════════════════════════════════ --}}
<div x-show="showLiveModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none" x-cloak>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showLiveModal = false"></div>
    <div class="relative w-full max-w-5xl max-h-[90vh] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col" @click.outside="showLiveModal = false">

        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                <h3 class="text-white text-lg font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                    {{ __('Live Operations') }}
                </h3>
                <p class="text-xs text-white/60 mt-0.5" x-text="`${new Date().toLocaleDateString()} · {{ __('Last updated') }}: ` + lastUpdated"></p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="fetchLiveData()" class="px-3 py-1.5 text-xs font-bold bg-white/15 text-white rounded-lg hover:bg-white/25 transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" :class="{'animate-spin': refreshing}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('Refresh') }}
                </button>
                <button @click="showLiveModal = false" class="text-white/60 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Modal Tabs --}}
        <div class="flex border-b border-gray-100 bg-gray-50/50">
            <button @click="tab = 'deliveries'" class="flex-1 px-4 py-3 text-xs font-bold flex items-center justify-center gap-2 transition-colors" :class="tab === 'deliveries' ? 'text-[#6E7A25] border-b-2 border-[#6E7A25] bg-white' : 'text-gray-400 hover:text-gray-600'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                {{ __('Deliveries') }}
                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-[#6E7A25]/10 text-[#6E7A25]" x-text="counts.pending_deliveries"></span>
            </button>
            <button @click="tab = 'orders'" class="flex-1 px-4 py-3 text-xs font-bold flex items-center justify-center gap-2 transition-colors" :class="tab === 'orders' ? 'text-[#6E7A25] border-b-2 border-[#6E7A25] bg-white' : 'text-gray-400 hover:text-gray-600'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ __('Orders') }}
                <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-[#6E7A25]/10 text-[#6E7A25]" x-text="counts.today_orders"></span>
            </button>
        </div>

        {{-- Modal Body (scrollable) --}}
        <div class="flex-1 overflow-y-auto p-4">

            {{-- ═══ DELIVERIES TAB ═══ --}}
            <div x-show="tab === 'deliveries'">
                <div class="space-y-2">
                    <template x-for="d in deliveries" :key="d.id">
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:shadow-sm hover:border-gray-200 transition-all bg-white">
                            {{-- Status Indicator --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" :class="{
                                'bg-green-50 text-green-600': d.status === 'delivered',
                                'bg-blue-50 text-blue-600': ['en_route','out_for_delivery'].includes(d.status),
                                'bg-purple-50 text-purple-600': d.status === 'assigned',
                                'bg-amber-50 text-amber-600': ['pending','preparing','scheduled'].includes(d.status),
                                'bg-red-50 text-red-600': ['failed','cancelled'].includes(d.status)
                            }">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                            </div>
                            {{-- Info --}}
                            <div class="flex-1 min-w-0 grid grid-cols-4 gap-2 text-xs">
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ __('Delivery') }}</p>
                                    <p class="font-semibold text-gray-900 truncate" x-text="d.label"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ __('Order') }}</p>
                                    <p class="font-semibold text-gray-900 truncate" x-text="d.order"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ __('Customer') }}</p>
                                    <p class="font-medium text-gray-700 truncate" x-text="d.customer"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ __('Zone') }}</p>
                                    <p class="font-medium text-gray-700 truncate" x-text="d.zone"></p>
                                </div>
                            </div>
                            {{-- Driver Assign --}}
                            <div class="flex-shrink-0 w-44">
                                <template x-if="d.status !== 'delivered' && d.status !== 'cancelled'">
                                    <div class="flex items-center gap-1.5">
                                        <select x-model="d.driver_id" @change="assignDriver(d)" class="text-[10px] border border-gray-100 rounded-lg px-2 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer w-full">
                                            <option value="">{{ __('Assign driver...') }}</option>
                                            <template x-for="dr in drivers" :key="dr.id">
                                                <option :value="dr.id" x-text="dr.name"></option>
                                            </template>
                                        </select>
                                        <span x-show="d.assigned" class="text-green-500 flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                    </div>
                                </template>
                                <template x-if="d.status === 'delivered'">
                                    <span class="text-[10px] font-bold text-green-600 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ __('Delivered') }}
                                    </span>
                                </template>
                            </div>
                            {{-- Status Badge --}}
                            <div class="flex-shrink-0 w-20 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold border" :class="{
                                    'bg-green-50 text-green-700 border-green-200': d.status === 'delivered',
                                    'bg-blue-50 text-blue-700 border-blue-200': ['en_route','out_for_delivery'].includes(d.status),
                                    'bg-purple-50 text-purple-700 border-purple-200': d.status === 'assigned',
                                    'bg-amber-50 text-amber-700 border-amber-200': ['pending','preparing','scheduled'].includes(d.status),
                                    'bg-red-50 text-red-600 border-red-200': ['failed','cancelled'].includes(d.status)
                                }" x-text="d.status.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                                <p class="text-[9px] text-gray-400 mt-0.5" x-text="d.time"></p>
                            </div>
                        </div>
                    </template>
                    <template x-if="deliveries.length === 0">
                        <div class="text-center py-12 text-xs text-gray-400">{{ __('No deliveries found for today.') }}</div>
                    </template>
                </div>
            </div>

            {{-- ═══ ORDERS TAB ═══ --}}
            <div x-show="tab === 'orders'">
                <div class="space-y-2">
                    <template x-for="o in orders" :key="o.id">
                        <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:shadow-sm hover:border-gray-200 transition-all bg-white">
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="flex-1 min-w-0 grid grid-cols-4 gap-2 text-xs">
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ __('Order') }}</p>
                                    <p class="font-semibold text-gray-900 truncate" x-text="o.id"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ __('Customer') }}</p>
                                    <p class="font-medium text-gray-700 truncate" x-text="o.customer"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ __('Plan') }}</p>
                                    <p class="font-medium text-gray-700 truncate" x-text="o.plan"></p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400">{{ __('Amount') }}</p>
                                    <p class="font-bold text-gray-900" x-text="'SAR ' + o.amount.toLocaleString()"></p>
                                </div>
                            </div>
                            <div class="flex-shrink-0 flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold border" :class="{
                                    'bg-green-50 text-green-700 border-green-200': o.status === 'delivered',
                                    'bg-blue-50 text-blue-700 border-blue-200': o.status === 'en_route',
                                    'bg-amber-50 text-amber-700 border-amber-200': ['pending','preparing'].includes(o.status),
                                    'bg-red-50 text-red-600 border-red-200': o.status === 'cancelled'
                                }" x-text="o.status.charAt(0).toUpperCase() + o.status.slice(1)"></span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold border" :class="{
                                    'bg-green-50 text-green-700 border-green-200': o.payment_status === 'paid',
                                    'bg-amber-50 text-amber-700 border-amber-200': o.payment_status === 'unpaid' || o.payment_status === 'pending',
                                    'bg-red-50 text-red-600 border-red-200': o.payment_status === 'failed'
                                }" x-text="o.payment_status.charAt(0).toUpperCase() + o.payment_status.slice(1)"></span>
                            </div>
                        </div>
                    </template>
                    <template x-if="orders.length === 0">
                        <div class="text-center py-12 text-xs text-gray-400">{{ __('No orders for today yet.') }}</div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4 text-[10px] text-gray-400">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span> {{ __('Live') }}</span>
                <span x-text="`${deliveries.length} {{ __('deliveries') }}`"></span>
                <span x-text="`${orders.length} {{ __('orders') }}`"></span>
                <span x-text="`${drivers.length} {{ __('drivers') }}`"></span>
            </div>
            <span class="text-[10px] text-gray-400" x-text="`{{ __('Updated') }}: ${lastUpdated}`"></span>
        </div>
    </div>
</div>

</div>

@push('scripts')
<script>
function liveApp() {
    return {
        showLiveModal: false,
        tab: 'deliveries',
        deliveries: [],
        orders: [],
        drivers: [],
        counts: { pending_deliveries: 0, unassigned: 0, today_orders: 0 },
        lastUpdated: '—',
        refreshing: false,

        init() {
            this.fetchLiveData();
        },

        async fetchLiveData() {
            this.refreshing = true;
            try {
                const r = await fetch('{{ route('admin.dashboard.live') }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const d = await r.json();
                this.deliveries = (d.deliveries || []).map(dl => ({ ...dl, assigned: false }));
                this.orders = d.orders || [];
                this.drivers = d.drivers || [];
                this.counts = d.counts || { pending_deliveries: 0, unassigned: 0, today_orders: 0 };
                this.lastUpdated = new Date().toLocaleTimeString();
            } catch(e) { console.error('Failed to fetch live data', e); }
            finally { this.refreshing = false; }
        },

        openLiveModal() {
            this.fetchLiveData();
            this.showLiveModal = true;
            this.tab = 'deliveries';
        },

        async assignDriver(d) {
            if (!d.driver_id) return;
            d.assigned = false;
            try {
                const r = await fetch('{{ url('admin/deliveries') }}/' + d.id + '/assign-driver', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ driver_id: d.driver_id })
                });
                const result = await r.json();
                if (result.success || r.ok) {
                    d.assigned = true;
                    d.driver = (this.drivers.find(dr => dr.id == d.driver_id) || {}).name || 'Assigned';
                    setTimeout(() => { d.assigned = false; }, 2000);
                }
            } catch(e) { console.error('Failed to assign driver', e); }
        }
    }
}
</script>
@endpush
@endsection
