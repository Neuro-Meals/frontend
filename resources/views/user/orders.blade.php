@extends('layouts.user')

@section('title', __('Orders') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('My Orders'))

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm">
    {{ session('error') }}
</div>
@endif

{{-- Active Subscription Banner --}}
@if($subscriptionInfo)
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-2xl p-5 sm:p-6 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0 backdrop-blur-sm">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <p class="text-base font-bold">{{ $subscriptionInfo['plan_name'] }}</p>
                <p class="text-xs text-white/70 mt-0.5">
                    {{ $subscriptionInfo['meals_per_day'] }} {{ __('meals/day') }} ·
                    {{ $subscriptionInfo['start_date'] }} → {{ $subscriptionInfo['end_date'] }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($subscriptionInfo['status'] === 'paused')
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-yellow-400/20 text-yellow-200 text-xs font-bold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('Paused') }}
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 text-white text-xs font-bold backdrop-blur-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('Active') }}
            </span>
            @endif
        </div>
    </div>

    {{-- Meal Progress --}}
    <div class="relative z-10 mt-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-white/70">{{ __('Meal Progress') }}</span>
            <span class="text-xs font-bold">{{ $subscriptionInfo['meals_consumed'] }} / {{ $subscriptionInfo['total_meals'] }} {{ __('meals') }}</span>
        </div>
        <div class="h-2.5 bg-white/10 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $subscriptionInfo['progress'] }}%"></div>
        </div>
        <div class="flex items-center justify-between mt-2">
            <span class="text-[10px] text-white/50">{{ $subscriptionInfo['progress'] }}% {{ __('consumed') }}</span>
            <span class="text-[10px] text-white/50">{{ $subscriptionInfo['remaining'] }} {{ __('meals remaining') }}</span>
        </div>
    </div>
</div>
@else
<div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-900">{{ __('No active subscription') }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('Subscribe to a plan to get your meals delivered automatically.') }}</p>
        </div>
    </div>
    <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold hover:shadow-lg transition-all w-fit">
        {{ __('Subscribe to a Plan') }}
    </a>
</div>
@endif

{{-- Info Banner --}}
@if($subscriptionInfo)
<div class="bg-[#6E7A25]/5 border border-[#6E7A25]/10 rounded-xl p-4 mb-6 flex items-start gap-3">
    <div class="w-8 h-8 rounded-lg bg-[#6E7A25]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
        <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
        <p class="text-xs text-gray-700 leading-relaxed">{{ __('Your meals are automatically prepared and delivered based on your subscription plan. No need to create orders manually — just enjoy your food!') }}</p>
    </div>
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <span class="text-[10px] font-medium text-white/60">{{ __('Total Orders') }}</span>
            <div class="text-2xl font-bold mt-1">{{ $stats['total'] }}</div>
            <div class="text-[10px] text-white/50 mt-1">{{ $stats['upcoming'] }} {{ __('upcoming') }}</div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Delivered') }}</span>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $stats['delivered'] }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ $stats['inProgress'] }} {{ __('in progress') }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Total Calories') }}</span>
        <div class="text-2xl font-bold text-[#6E7A25] mt-1">{{ number_format($stats['totalCalories']) }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ __('avg') }} {{ number_format($stats['avgCalories']) }} {{ __('per order') }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Total Spent') }}</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">SAR {{ number_format($stats['totalSpent']) }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ __('avg') }} SAR {{ number_format($stats['avgOrder']) }} {{ __('per order') }}</div>
    </div>
</div>

{{-- Orders List --}}
<div class="space-y-4">
    @if(!empty($orders))
        @foreach($orders as $order)
        <div class="order-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
            {{-- Order Header (clickable to expand) --}}
            <div class="px-4 sm:px-5 py-4 cursor-pointer flex items-center justify-between gap-3" onclick="this.closest('.order-card').classList.toggle('order-collapsed')">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white flex-shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-bold text-gray-900">{{ $order['id'] }}</p>
                            @if($order['status'] === 'delivered')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-green-50 text-green-700">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Delivered') }}
                            </span>
                            @elseif($order['status'] === 'cancelled')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-red-50 text-red-700">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ __('Cancelled') }}
                            </span>
                            @elseif($order['status'] === 'preparing')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-blue-50 text-blue-700">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Preparing') }}
                            </span>
                            @elseif($order['status'] === 'out_for_delivery')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-orange-50 text-orange-700">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Out for Delivery') }}
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-yellow-50 text-yellow-700">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ ucfirst($order['status']) }}
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 mt-1 text-[10px] text-gray-500 flex-wrap">
                            <span class="font-medium">{{ date('M d, Y', strtotime($order['date'])) }}</span>
                            <span class="text-gray-300">·</span>
                            <span>{{ $order['meals'] }} {{ __('meals') }}</span>
                            <span class="text-gray-300">·</span>
                            <span class="font-bold text-[#6E7A25]">{{ number_format($order['total_calories']) }} kcal</span>
                            @if($order['status'] === 'delivered')
                            <span class="text-gray-300">·</span>
                            <span class="inline-flex items-center gap-1 text-green-600 font-bold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $order['meals'] }}/{{ $order['meals'] }} {{ __('delivered') }}
                            </span>
                            @elseif($order['status'] !== 'cancelled')
                            <span class="text-gray-300">·</span>
                            <span class="inline-flex items-center gap-1 text-orange-500 font-bold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                0/{{ $order['meals'] }} {{ __('delivered') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300 transition-transform order-chevron flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>

            {{-- Order Content (expandable) --}}
            <div class="order-content transition-all duration-300 border-t border-gray-50">
                <div class="p-4 sm:p-5">
                    @if(!empty($order['categories']))
                    <div class="space-y-4">
                        @foreach($order['categories'] as $catGroup)
                        <div class="bg-gray-50/50 rounded-xl border border-gray-100 overflow-hidden">
                            {{-- Category header --}}
                            <div class="px-3 py-2.5 border-b border-gray-100 flex items-center gap-2 bg-gradient-to-r from-[#173327]/3 to-[#6E7A25]/3">
                                <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white flex-shrink-0">
                                    @if($catGroup['icon'] === 'sunrise')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m-4.5 3.5L6 6m9 0l1.5-1.5M4 12H2m20 0h-2M6.343 17.657L4.929 19.071M19.071 19.071l-1.414-1.414M12 18a6 6 0 00-6-6 6 6 0 006 6 6 6 0 006-6 6 6 0 00-6 6z"/></svg>
                                    @elseif($catGroup['icon'] === 'sun')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    @elseif($catGroup['icon'] === 'moon')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                    @elseif($catGroup['icon'] === 'cookie')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15h18v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3zM3 15l2.5-7.5A2 2 0 017.4 6h9.2a2 2 0 011.9 1.5L21 15M9 15V11M15 15V11"/></svg>
                                    @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg>
                                    @endif
                                </div>
                                <span class="text-xs font-bold text-gray-700">{{ $catGroup['name'] }}</span>
                                <span class="text-[10px] text-gray-400">{{ count($catGroup['meals']) }} {{ __('meal(s)') }}</span>
                            </div>

                            {{-- Meal cards --}}
                            <div class="p-3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($catGroup['meals'] as $meal)
                                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group">
                                        <div class="h-32 sm:h-36 bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 relative overflow-hidden">
                                            <img src="{{ meal_image_url($meal['image'] ?? null) }}"
                                                 srcset="{{ meal_image_srcset($meal['image'] ?? null) }}"
                                                 sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
                                                 alt="{{ $meal['name'] ?? __('Meal') }}"
                                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                                 loading="lazy"
                                                 onerror="this.onerror=null;this.src='{{ asset('images/meal-placeholder.svg') }}';">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                                            @if($meal['quantity'] > 1)
                                            <span class="absolute top-2 left-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-[#173327] text-white shadow-md">x{{ $meal['quantity'] }}</span>
                                            @endif
                                            {{-- Per-meal delivery status badge --}}
                                            @if($order['status'] === 'delivered')
                                            <span class="absolute top-2 right-2 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-green-500/90 text-white backdrop-blur-sm shadow-md">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                {{ __('Delivered') }}
                                            </span>
                                            @elseif($order['status'] === 'out_for_delivery')
                                            <span class="absolute top-2 right-2 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-orange-500/90 text-white backdrop-blur-sm shadow-md">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ __('On the way') }}
                                            </span>
                                            @elseif($order['status'] === 'preparing')
                                            <span class="absolute top-2 right-2 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-blue-500/90 text-white backdrop-blur-sm shadow-md">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ __('Preparing') }}
                                            </span>
                                            @elseif($order['status'] === 'cancelled')
                                            <span class="absolute top-2 right-2 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-red-500/90 text-white backdrop-blur-sm shadow-md">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                {{ __('Cancelled') }}
                                            </span>
                                            @else
                                            <span class="absolute top-2 right-2 inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-yellow-500/90 text-white backdrop-blur-sm shadow-md">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ __('Pending') }}
                                            </span>
                                            @endif
                                            <span class="absolute bottom-2 left-2 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-white/95 text-[#173327] backdrop-blur-sm shadow-sm">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                                {{ number_format($meal['calories']) }} kcal
                                            </span>
                                        </div>
                                        <div class="p-3">
                                            <h4 class="text-xs font-bold text-gray-900 truncate">{{ $meal['name'] }}</h4>
                                            <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-semibold bg-[#6E7A25]/10 text-[#6E7A25]">P {{ $meal['protein'] }}g</span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-semibold bg-[#025C5F]/10 text-[#025C5F]">C {{ $meal['carbs'] }}g</span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-semibold bg-[#949B50]/10 text-[#949B50]">F {{ $meal['fat'] }}g</span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Order Summary --}}
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        {{-- Delivery progress bar --}}
                        @if($order['status'] !== 'cancelled')
                        <div class="mb-3">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-[10px] font-medium text-gray-500">{{ __('Delivery Progress') }}</span>
                                <span class="text-[10px] font-bold {{ $order['status'] === 'delivered' ? 'text-green-600' : 'text-orange-500' }}">
                                    @if($order['status'] === 'delivered')
                                        {{ $order['meals'] }}/{{ $order['meals'] }}
                                    @else
                                        0/{{ $order['meals'] }}
                                    @endif
                                    {{ __('meals delivered') }}
                                </span>
                            </div>
                            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-1000 {{ $order['status'] === 'delivered' ? 'bg-green-500' : 'bg-orange-400' }}" style="width: {{ $order['status'] === 'delivered' ? '100' : '0' }}%"></div>
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <div class="flex items-center gap-4 text-[10px] text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    <span class="font-bold text-gray-900">{{ number_format($order['total_calories']) }}</span> kcal
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="font-bold text-gray-900">P {{ $order['total_protein'] }}g</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="font-bold text-gray-900">C {{ $order['total_carbs'] }}g</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="font-bold text-gray-900">F {{ $order['total_fat'] }}g</span>
                                </span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-gray-400">{{ __('Total') }}</span>
                                <span class="text-sm font-bold text-gray-900 ml-1">SAR {{ number_format($order['amount']) }}</span>
                            </div>
                        </div>
                    </div>
                    @else
                    <p class="text-xs text-gray-400 text-center py-4">{{ __('No meal details available for this order.') }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">{{ __('No orders yet') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $subscriptionInfo ? __('Your meal deliveries will appear here automatically.') : __('Subscribe to a plan to get started.') }}</p>
        @if(!$subscriptionInfo)
        <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center mt-4 px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold hover:shadow-lg transition-all">
            {{ __('Subscribe to a Plan') }}
        </a>
        @endif
    </div>
    @endif
</div>

<style>
.order-card .order-content { max-height: 2000px; overflow: hidden; transition: max-height 0.4s ease; }
.order-card.order-collapsed .order-content { max-height: 0; border-top: 0; }
.order-card.order-collapsed .order-chevron { transform: rotate(-90deg); }
</style>

@endsection
