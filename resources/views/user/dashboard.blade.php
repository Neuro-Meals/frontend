@extends('layouts.user')

@section('title', __('Dashboard') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Dashboard'))

@section('content')

{{-- Welcome --}}
<div class="mb-6 flex flex-row items-start sm:items-center justify-between gap-3 flex-wrap animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">{{ __('Hello') }} {{ (session('api_user')['first_name'] ?? session('api_user')['name'] ?? 'User') }} 👋</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">{{ __('Here\'s your nutrition journey today.') }}</p>
    </div>
</div>

{{-- Subscription Status Banner --}}
@if(!empty($activeSubscription) && ($activeSubscription['status'] ?? 'none') !== 'none')
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-xl p-4 sm:p-5 text-white shadow-lg mb-6 animate__animated animate__fadeInUp" style="animation-delay: 0.15s;">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-sm font-bold">{{ $stats['activePlan'] }}</p>
                <p class="text-xs text-white/70">{{ $stats['remainingMeals'] }} {{ __('meals remaining') }} · {{ $stats['planRenewal'] }}</p>
            </div>
        </div>
        <a href="{{ route('user.meals') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-white/20 text-white text-xs font-bold hover:bg-white/30 transition-all w-fit">
            {{ __('View My Meals') }} →
        </a>
    </div>
</div>
@else
<div class="bg-white border border-gray-100 rounded-xl p-5 sm:p-6 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate__animated animate__fadeInUp" style="animation-delay: 0.15s;">
    <div>
        <p class="text-sm font-bold text-gray-900">{{ __('No active subscription') }}</p>
        <p class="text-xs text-gray-500 mt-0.5">{{ __('Subscribe to a plan to start your nutrition journey.') }}</p>
    </div>
    <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold hover:shadow-lg transition-all w-fit">
        {{ __('Subscribe Now') }}
    </a>
</div>
@endif

{{-- Hero Card --}}
@php $heroMeal = $upcomingMeals[0] ?? null; @endphp
@if ($heroMeal)
<div class="relative rounded-2xl overflow-hidden shadow-xl mb-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
    <img src="{{ meal_image_url($heroMeal['image'] ?? null) }}" alt="{{ $heroMeal['name'] ?? 'Meal' }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy" onerror="this.src='{{ asset('whitelogo.png') }}'">
    <div class="absolute inset-0 bg-gradient-to-r from-[#173327]/95 via-[#173327]/70 to-[#6E7A25]/40"></div>
    <div class="relative z-10 p-5 sm:p-8 lg:p-10 flex flex-col justify-center min-h-[200px] sm:min-h-[240px]">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#6E7A25]/30 backdrop-blur-sm text-[#949B50] text-[10px] font-bold uppercase tracking-wider w-fit mb-3">
            <span class="w-1.5 h-1.5 bg-[#949B50] rounded-full animate-pulse"></span>
            {{ __('Today\'s Pick') }}
        </span>
        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white tracking-tight max-w-md leading-tight">{{ $heroMeal['name'] ?? 'Meal' }}</h2>
        <p class="text-xs sm:text-sm text-white/60 mt-2 max-w-sm leading-relaxed">{{ $heroMeal['description'] ?? '' }}</p>
        <div class="flex items-center gap-4 mt-4">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#949B50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span class="text-xs font-bold text-white">{{ $heroMeal['calories'] ?? 520 }} {{ __('kcal') }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#949B50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/></svg>
                <span class="text-xs font-bold text-white">{{ $heroMeal['protein'] ?? 42 }}g {{ __('Protein') }}</span>
            </div>
            <a href="{{ route('user.meals') }}" class="ml-auto px-4 py-2 bg-[#6E7A25] hover:bg-[#949B50] text-white text-xs font-bold rounded-lg transition-all hover:shadow-lg hover:shadow-[#6E7A25]/30 active:scale-95">
                {{ __('View Meal') }} →
            </a>
        </div>
    </div>
</div>
@endif

{{-- Stats Cards --}}
<div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4 mb-6">
    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-3 sm:p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20" style="animation-delay: 0.3s;">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-white/60">{{ __('Active Plan') }}</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-base sm:text-xl font-bold tracking-tight text-white relative z-10 truncate">{{ $stats['activePlan'] }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-white/50 font-medium relative z-10">{{ __('Renews') }} {{ $stats['planRenewal'] }}</div>
    </div>

    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#033133] to-[#025C5F] rounded-xl p-3 sm:p-5 text-white relative overflow-hidden shadow-lg shadow-[#025C5F]/20" style="animation-delay: 0.4s;">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-white/60">{{ __('Meals Consumed') }}</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $stats['mealsConsumed'] }}<span class="text-sm text-white/50">/{{ $stats['mealsTotal'] > 0 ? $stats['mealsTotal'] : '?' }}</span></div>
        <div class="mt-1 text-[10px] sm:text-xs text-white/50 font-medium relative z-10">{{ $stats['remainingMeals'] }} {{ __('remaining') }}</div>
    </div>

    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#6E7A25] to-[#949B50] rounded-xl p-3 sm:p-5 text-white relative overflow-hidden shadow-lg shadow-[#949B50]/20" style="animation-delay: 0.5s;">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-white/60">{{ __('Daily Calories') }}</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($stats['dailyCalories']) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-white/50 font-medium relative z-10">{{ __('Target') }}: {{ number_format($stats['calorieTarget']) }} {{ __('kcal') }}</div>
    </div>

    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#025C5F] to-[#033133] rounded-xl p-3 sm:p-5 text-white relative overflow-hidden shadow-lg shadow-[#025C5F]/20" style="animation-delay: 0.6s;">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-white/60">{{ __('Total Orders') }}</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $stats['totalOrders'] }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-white/50 font-medium relative z-10">{{ __('Next') }}: {{ $stats['nextDelivery'] }}</div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-6">
    {{-- Calorie Line Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 lg:col-span-2 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Calorie') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Tracking') }}</span></h3>
                <p class="text-xs text-gray-400">{{ __('Last 7 days · Target') }}: {{ number_format($chartData['calorieTarget']) }} kcal</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-gray-900">{{ number_format($stats['dailyCalories']) }} {{ __('kcal') }}</div>
                <div class="text-xs text-[#6E7A25] font-medium">{{ __('Today') }}</div>
            </div>
        </div>
        <div class="relative h-56 sm:h-64">
            <canvas id="calorieChart"></canvas>
        </div>
    </div>

    {{-- Macro Donut Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Nutrition') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Breakdown') }}</span></h3>
        <p class="text-xs text-gray-400 mb-4">{{ __('Today\'s macros') }}</p>
        <div class="relative h-40 sm:h-48 mb-4">
            <canvas id="macroChart"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                <p class="text-xl font-bold text-gray-900">{{ number_format($stats['dailyCalories']) }}</p>
                <p class="text-[10px] text-gray-400">{{ __('kcal today') }}</p>
            </div>
        </div>
        <div class="space-y-2.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#6E7A25]"></span>
                    <span class="text-xs font-medium text-gray-700">{{ __('Protein') }}</span>
                </div>
                <span class="text-xs font-bold text-gray-900">{{ $stats['proteinToday'] }}g</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#949B50]"></span>
                    <span class="text-xs font-medium text-gray-700">{{ __('Carbs') }}</span>
                </div>
                <span class="text-xs font-bold text-gray-900">{{ $stats['carbsToday'] }}g</span>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#025C5F]"></span>
                    <span class="text-xs font-medium text-gray-700">{{ __('Fats') }}</span>
                </div>
                <span class="text-xs font-bold text-gray-900">{{ $stats['fatToday'] }}g</span>
            </div>
        </div>
    </div>
</div>

{{-- Upcoming Meals & Recent Orders --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
        <div class="px-4 sm:px-5 py-4 border-b border-gray-50">
            <h3 class="text-sm font-bold text-gray-900">{{ __('Upcoming') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Meals') }}</span></h3>
            <p class="text-xs text-gray-400">{{ __('Your next scheduled meals') }}</p>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($upcomingMeals as $meal)
            <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50/30 transition-colors">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#6E7A25]/20 to-[#173327]/20 flex items-center justify-center flex-shrink-0 overflow-hidden relative">
                    <img src="{{ meal_image_url($meal['image'] ?? null) }}" alt="{{ $meal['name'] ?? 'Meal' }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'">
                    <svg class="w-5 h-5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $meal['name'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $meal['time'] }} · {{ $meal['calories'] }} {{ __('kcal') }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ ($meal['status'] ?? 'upcoming') === 'delivered' ? 'bg-green-50 text-green-700' : 'bg-[#949B50]/10 text-[#949B50]' }}">
                    {{ ucfirst($meal['status'] ?? 'upcoming') }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 1.0s;">
        <div class="px-4 sm:px-5 py-4 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Recent') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Orders') }}</span></h3>
                <p class="text-xs text-gray-400">{{ __('Your latest meal orders') }}</p>
            </div>
            <a href="{{ route('user.orders') }}" class="text-xs font-bold text-[#6E7A25] hover:text-[#173327] transition-colors">{{ __('View all') }} →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @if(!empty($recentOrders))
            @foreach($recentOrders as $order)
            <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50/30 transition-colors">
                <div>
                    <p class="text-xs font-semibold text-gray-900">{{ $order['id'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $order['plan'] }} · {{ date('M d', strtotime($order['date'])) }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-900">SAR {{ number_format($order['amount'], 2) }}</span>
                    @if($order['status'] === 'delivered')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-green-700">{{ __('Delivered') }}</span>
                    @elseif($order['status'] === 'cancelled')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-50 text-red-700">{{ __('Cancelled') }}</span>
                    @elseif(in_array($order['status'], ['pending', 'scheduled', 'confirmed', 'preparing', 'ready_for_delivery', 'out_for_delivery']))
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-50 text-yellow-700">{{ __('Active') }}</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600">{{ ucfirst($order['status']) }}</span>
                    @endif
                </div>
            </div>
            @endforeach
            @else
            <div class="px-5 py-8 text-center">
                <p class="text-xs font-medium text-gray-400">{{ __('No orders yet') }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ __('Your recent orders will appear here.') }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Weight Progress & Next Delivery --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm lg:col-span-2 animate__animated animate__fadeInUp" style="animation-delay: 1.1s;">
        <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Weight') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Progress') }}</span></h3>
                <p class="text-xs text-gray-400">{{ __('From') }} {{ $stats['weightStart'] }}kg {{ __('to goal') }} {{ $stats['weightGoal'] }}kg</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-[#6E7A25]">{{ $stats['weightStart'] > $stats['weightCurrent'] ? '-' : '+' }}{{ number_format(abs($stats['weightStart'] - $stats['weightCurrent']), 1) }}kg</div>
                <div class="text-xs text-gray-400">{{ __('Current') }}: {{ $stats['weightCurrent'] }}kg</div>
            </div>
        </div>
        <div class="relative h-48 sm:h-56">
            <canvas id="weightChart"></canvas>
        </div>
    </div>

    <div class="bg-gradient-to-br from-[#173327] to-[#122620] rounded-xl p-4 sm:p-5 text-white shadow-lg relative overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 1.2s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#6E7A25]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-xl bg-[#6E7A25]/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                </div>
                <span class="text-xs font-bold">{{ __('Next Delivery') }}</span>
            </div>
            <p class="text-sm font-bold">{{ $stats['nextDelivery'] }}</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['nextMeal'] }}</p>
            <a href="{{ route('user.delivery') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-[#6E7A25] hover:text-white transition-colors">
                {{ __('Track delivery') }} →
            </a>
        </div>
    </div>
</div>

{{-- Health Stats --}}
@php
    $weight = (float) ($user['weight_kg'] ?? 0);
    $height = (float) ($user['height_cm'] ?? 0);
    $bmi = 0;
    if ($weight > 0 && $height > 0) {
        $bmi = $weight / (($height / 100) ** 2);
    }
    $age = $user['age'] ?? null;
    $goal = ucfirst(str_replace('_', ' ', $user['fitness_goal'] ?? 'maintenance'));
@endphp
<div class="bg-white rounded-xl border border-gray-100 p-5 sm:p-6 shadow-sm mb-6 animate__animated animate__fadeInUp" style="animation-delay: 1.3s;">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-sm font-bold text-gray-900">{{ __('My') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Health Stats') }}</span></h3>
            <p class="text-xs text-gray-400">{{ __('Personal data from your profile') }}</p>
        </div>
        <a href="{{ route('user.settings') }}" class="text-xs font-bold text-[#6E7A25] hover:text-[#173327] transition-colors">{{ __('Update') }} →</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div class="bg-gray-50 rounded-xl p-4 text-center">
            <p class="text-[10px] text-gray-400 uppercase tracking-wide">{{ __('Weight') }}</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $weight > 0 ? number_format($weight, 1) . ' kg' : '-' }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 text-center">
            <p class="text-[10px] text-gray-400 uppercase tracking-wide">{{ __('Height') }}</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $height > 0 ? number_format($height, 0) . ' cm' : '-' }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 text-center">
            <p class="text-[10px] text-gray-400 uppercase tracking-wide">{{ __('BMI') }}</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $bmi > 0 ? number_format($bmi, 1) : '-' }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 text-center">
            <p class="text-[10px] text-gray-400 uppercase tracking-wide">{{ __('Age') }}</p>
            <p class="text-lg font-bold text-gray-900 mt-1">{{ $age ? $age . ' yrs' : '-' }}</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 text-center col-span-2 sm:col-span-1">
            <p class="text-[10px] text-gray-400 uppercase tracking-wide">{{ __('Goal') }}</p>
            <p class="text-lg font-bold text-gray-900 mt-1 truncate">{{ $goal }}</p>
        </div>
    </div>
</div>

<x-ai-chat-widget context="customer" position="bottom-right" />

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = "'Nunito', sans-serif";
    Chart.defaults.color = '#9ca3af';

    const commonOptions = {
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

    // Calorie Line Chart
    new Chart(document.getElementById('calorieChart'), {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: '{{ __("Calories") }}',
                data: @json($chartData['calories']),
                borderColor: '#6E7A25',
                backgroundColor: (ctx) => {
                    const canvas = ctx.chart.ctx;
                    const gradient = canvas.createLinearGradient(0, 0, 0, 250);
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
                pointHoverRadius: 7,
                borderWidth: 2.5,
            }, {
                label: '{{ __("Target") }}',
                data: Array(@json($chartData['calories']).length).fill(@json($chartData['calorieTarget'])),
                borderColor: 'rgba(2, 92, 95, 0.4)',
                borderDash: [6, 4],
                borderWidth: 1.5,
                pointRadius: 0,
                fill: false,
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: (ctx) => ctx.dataset.label + ': ' + Number(ctx.raw).toLocaleString() + ' kcal'
                    }
                }
            }
        }
    });

    // Macro Donut Chart
    new Chart(document.getElementById('macroChart'), {
        type: 'doughnut',
        data: {
            labels: ['{{ __("Protein") }}', '{{ __("Carbs") }}', '{{ __("Fats") }}'],
            datasets: [{
                data: [{{ $stats['proteinToday'] }}, {{ $stats['carbsToday'] }}, {{ $stats['fatToday'] }}],
                backgroundColor: ['#6E7A25', '#949B50', '#025C5F'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#173327',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: (ctx) => ctx.label + ': ' + ctx.raw + 'g'
                    }
                }
            }
        }
    });

    // Weight Line Chart
    new Chart(document.getElementById('weightChart'), {
        type: 'line',
        data: {
            labels: @json($weightHistory['labels']),
            datasets: [{
                label: '{{ __("Weight") }}',
                data: @json($weightHistory['data']),
                borderColor: '#025C5F',
                backgroundColor: (ctx) => {
                    const canvas = ctx.chart.ctx;
                    const gradient = canvas.createLinearGradient(0, 0, 0, 220);
                    gradient.addColorStop(0, 'rgba(2, 92, 95, 0.2)');
                    gradient.addColorStop(1, 'rgba(2, 92, 95, 0.0)');
                    return gradient;
                },
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#025C5F',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                borderWidth: 2.5,
            }, {
                label: '{{ __("Goal") }}',
                data: Array(@json($weightHistory['data']).length).fill(@json($weightHistory['goal'])),
                borderColor: 'rgba(110, 122, 37, 0.4)',
                borderDash: [6, 4],
                borderWidth: 1.5,
                pointRadius: 0,
                fill: false,
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                ...commonOptions.plugins,
                tooltip: {
                    ...commonOptions.plugins.tooltip,
                    callbacks: {
                        label: (ctx) => ctx.dataset.label + ': ' + ctx.raw + ' kg'
                    }
                }
            }
        }
    });
</script>
@endpush

@endsection
