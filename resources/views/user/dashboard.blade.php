@extends('layouts.user')

@section('title', __('Dashboard') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Dashboard'))

@section('content')

{{-- Welcome --}}
<div class="mb-6 flex flex-row items-start sm:items-center justify-between gap-3 flex-wrap animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">{{ __('Hello') }} {{ Auth::user()->name }} 👋</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">{{ __('Here\'s your nutrition journey today.') }}</p>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <a href="{{ route('user.orders') }}" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span class="hidden sm:inline">{{ __('Export') }}</span>
        </a>
        <a href="{{ route('user.subscriptions') }}" class="px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-brand-600 to-brand-500 rounded-lg hover:shadow-md transition-all inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">{{ __('New Order') }}</span><span class="sm:hidden">{{ __('Order') }}</span>
        </a>
    </div>
</div>

{{-- Hero Card --}}
<div class="relative rounded-2xl overflow-hidden shadow-xl mb-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
    <img src="{{ asset('images/meals/top-view-healthy-diet-salad-with-grilled-chicken-broccoli-cauliflower-tomato-lettuce-avocado-lettuce_141793-2438.jpg') }}" alt="Healthy Meal" class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-[#173327]/95 via-[#173327]/70 to-[#6E7A25]/40"></div>
    <div class="relative z-10 p-5 sm:p-8 lg:p-10 flex flex-col justify-center min-h-[200px] sm:min-h-[240px]">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#6E7A25]/30 backdrop-blur-sm text-[#949B50] text-[10px] font-bold uppercase tracking-wider w-fit mb-3">
            <span class="w-1.5 h-1.5 bg-[#949B50] rounded-full animate-pulse"></span>
            {{ __('Today\'s Pick') }}
        </span>
        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white tracking-tight max-w-md leading-tight">{{ __('Grilled Chicken Power Bowl') }}</h2>
        <p class="text-xs sm:text-sm text-white/60 mt-2 max-w-sm leading-relaxed">{{ __('Fresh greens, grilled chicken, avocado & broccoli — packed with protein to fuel your day.') }}</p>
        <div class="flex items-center gap-4 mt-4">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#949B50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span class="text-xs font-bold text-white">520 {{ __('kcal') }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#949B50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/></svg>
                <span class="text-xs font-bold text-white">42g {{ __('Protein') }}</span>
            </div>
            <a href="{{ route('user.meals') }}" class="ml-auto px-4 py-2 bg-[#6E7A25] hover:bg-[#949B50] text-white text-xs font-bold rounded-lg transition-all hover:shadow-lg hover:shadow-[#6E7A25]/30 active:scale-95">
                {{ __('View Meal') }} →
            </a>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4 mb-6">
    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-3 sm:p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20" style="animation-delay: 0.3s;">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-white/60">{{ __('Active Plan') }}</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-base sm:text-xl font-bold tracking-tight text-white relative z-10">{{ $stats['activePlan'] }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-white/50 font-medium relative z-10">{{ __('Renews') }} {{ $stats['planRenewal'] }}</div>
    </div>

    <div class="kpi-card animate__animated animate__fadeInUp bg-gradient-to-br from-[#033133] to-[#025C5F] rounded-xl p-3 sm:p-5 text-white relative overflow-hidden shadow-lg shadow-[#025C5F]/20" style="animation-delay: 0.4s;">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-white/60">{{ __('Meals This Week') }}</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $stats['mealsThisWeek'] }}<span class="text-sm text-white/50">/{{ $stats['mealsTotal'] }}</span></div>
        <div class="mt-1 text-[10px] sm:text-xs text-white/50 font-medium relative z-10">{{ $stats['mealsTotal'] - $stats['mealsThisWeek'] }} {{ __('remaining') }}</div>
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
            <span class="text-[10px] sm:text-xs font-medium text-white/60">{{ __('Streak Days') }}</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.24 17 7c.586 1.172 1.5 2 3 2.5a8 8 0 01-2.343 9.157z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $stats['streakDays'] }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-white/50 font-medium relative z-10">{{ __('Keep it up!') }} 🔥</div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 sm:p-5 lg:col-span-2 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Calorie') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Tracking') }}</span></h3>
                <p class="text-xs text-gray-400">{{ __('Last 7 days') }}</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-gray-900">{{ number_format($stats['dailyCalories']) }} {{ __('kcal') }}</div>
                <div class="text-xs text-[#6E7A25] font-medium">{{ __('On track') }}</div>
            </div>
        </div>
        @php $calMax = max(array_column($weeklyProgress, 'calories')) ?: 2000; @endphp
        <div class="flex items-end gap-2 h-48">
            @foreach($weeklyProgress as $day)
                @php $pct = ($day['calories'] / $calMax) * 100; $isOver = $day['calories'] > $day['target']; @endphp
                <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 {{ $isOver ? 'bg-gradient-to-t from-[#949B50] to-[#6E7A25]' : 'bg-gradient-to-t from-[#6E7A25] to-[#6E7A25]/70' }} group-hover:opacity-80" style="height: {{ max($pct, 4) }}%"></div>
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap">
                            {{ number_format($day['calories']) }} {{ __('kcal') }}
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $day['day'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-4 sm:p-5 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
        <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('Nutrition') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Breakdown') }}</span></h3>
        <div class="space-y-4">
            @php
                $proteinPct = round($stats['proteinToday'] / $stats['proteinTarget'] * 100);
                $carbsPct = round($stats['carbsToday'] / $stats['carbsTarget'] * 100);
                $fatPct = round($stats['fatToday'] / $stats['fatTarget'] * 100);
            @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-700">{{ __('Protein') }}</span>
                    <span class="text-xs font-semibold text-gray-900">{{ $stats['proteinToday'] }}g / {{ $stats['proteinTarget'] }}g</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-[#6E7A25] h-2.5 rounded-full transition-all duration-500" style="width: {{ min($proteinPct, 100) }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-700">{{ __('Carbs') }}</span>
                    <span class="text-xs font-semibold text-gray-900">{{ $stats['carbsToday'] }}g / {{ $stats['carbsTarget'] }}g</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-[#949B50] h-2.5 rounded-full transition-all duration-500" style="width: {{ min($carbsPct, 100) }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-700">{{ __('Fats') }}</span>
                    <span class="text-xs font-semibold text-gray-900">{{ $stats['fatToday'] }}g / {{ $stats['fatTarget'] }}g</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-[#025C5F] h-2.5 rounded-full transition-all duration-500" style="width: {{ min($fatPct, 100) }}%"></div>
                </div>
            </div>
        </div>
        <div class="mt-5 pt-4 border-t border-gray-50">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-[#6E7A25] rounded-full animate-pulse"></span>
                <p class="text-[11px] text-gray-400">{{ __('Tracking in real-time') }}</p>
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
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25]/20 to-[#173327]/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-gray-900 truncate">{{ $meal['name'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $meal['time'] }} · {{ $meal['calories'] }} {{ __('kcal') }}</p>
                </div>
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
            @foreach($recentOrders as $order)
            <div class="px-5 py-3 flex items-center justify-between hover:bg-gray-50/30 transition-colors">
                <div>
                    <p class="text-xs font-semibold text-gray-900">{{ $order['id'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $order['plan'] }} · {{ date('M d', strtotime($order['date'])) }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-gray-900">SAR {{ $order['amount'] }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-green-700">{{ ucfirst($order['status']) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Weight Progress & Next Delivery --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 sm:p-5 shadow-sm lg:col-span-2 animate__animated animate__fadeInUp" style="animation-delay: 1.1s;">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Weight') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Progress') }}</span></h3>
                <p class="text-xs text-gray-400">{{ __('From') }} {{ $stats['weightStart'] }}kg {{ __('to goal') }} {{ $stats['weightGoal'] }}kg</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold text-[#6E7A25]">-{{ number_format($stats['weightStart'] - $stats['weightCurrent'], 1) }}kg</div>
                <div class="text-xs text-gray-400">{{ __('Current') }}: {{ $stats['weightCurrent'] }}kg</div>
            </div>
        </div>
        @php $weightRange = $stats['weightStart'] - $stats['weightGoal']; $currentProgress = ($stats['weightStart'] - $stats['weightCurrent']) / $weightRange * 100; @endphp
        <div class="relative h-4 bg-gray-100 rounded-full overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-full transition-all duration-1000" style="width: {{ min($currentProgress, 100) }}%"></div>
        </div>
        <div class="flex items-center justify-between mt-2">
            <span class="text-[10px] text-gray-400 font-medium">{{ __('Start') }}: {{ $stats['weightStart'] }}kg</span>
            <span class="text-[10px] text-gray-400 font-medium">{{ __('Goal') }}: {{ $stats['weightGoal'] }}kg</span>
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

@endsection
