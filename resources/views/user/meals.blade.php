@extends('layouts.user')

@section('title', __('My Meals') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('My Meals'))

@section('content')

{{-- Subscription Banner --}}
@if($hasActiveSubscription)
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-xl p-4 sm:p-5 text-white shadow-lg mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold">{{ $activeSubscription['plan_name'] ?? __('Active Plan') }}</p>
            <p class="text-xs text-white/70">{{ $stats['remaining'] }} {{ __('meals remaining') }} · {{ $stats['totalPlan'] }} {{ __('total') }}</p>
        </div>
    </div>
    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-white/20 text-xs font-semibold w-fit">
        {{ __('Subscribed') }}
    </span>
</div>
@else
<div class="bg-white border border-gray-100 rounded-xl p-5 sm:p-6 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-sm font-bold text-gray-900">{{ __('No active subscription') }}</p>
        <p class="text-xs text-gray-500 mt-0.5">{{ __('Subscribe to a plan to start scheduling your meals.') }}</p>
    </div>
    <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold hover:shadow-lg transition-all w-fit">
        {{ __('Subscribe to a Plan') }}
    </a>
</div>
@endif

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4 mb-6">
    {{-- Meals Consumed --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-medium text-white/60">{{ __('Meals Consumed') }}</span>
                <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="text-2xl font-bold">{{ $stats['mealsConsumed'] }}<span class="text-sm text-white/50">/{{ $stats['totalPlan'] }}</span></div>
            <div class="mt-2 h-1.5 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $stats['planProgress'] }}%"></div>
            </div>
            <div class="mt-1 text-[10px] text-white/50">{{ $stats['planProgress'] }}% {{ __('complete') }}</div>
        </div>
    </div>

    {{-- Today's Calories --}}
    <div class="bg-gradient-to-br from-[#033133] to-[#025C5F] rounded-xl p-4 text-white shadow-lg shadow-[#025C5F]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-medium text-white/60">{{ __('Today Calories') }}</span>
                <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="text-2xl font-bold">{{ number_format($stats['todayCalories']) }}</div>
            <div class="mt-1 text-[10px] text-white/50">{{ __('Target') }}: {{ number_format($stats['calorieTarget']) }} kcal</div>
            @php $calPct = $stats['calorieTarget'] > 0 ? min(100, round($stats['todayCalories'] / $stats['calorieTarget'] * 100)) : 0; @endphp
            <div class="mt-2 h-1.5 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $calPct }}%"></div>
            </div>
        </div>
    </div>

    {{-- Today's Protein --}}
    <div class="bg-gradient-to-br from-[#6E7A25] to-[#949B50] rounded-xl p-4 text-white shadow-lg shadow-[#949B50]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-medium text-white/60">{{ __('Today Protein') }}</span>
                <svg class="w-3.5 h-3.5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/></svg>
            </div>
            <div class="text-2xl font-bold">{{ $stats['todayProtein'] }}<span class="text-sm text-white/50">g</span></div>
            <div class="mt-1 text-[10px] text-white/50">{{ __('Target') }}: {{ $stats['proteinTarget'] }}g</div>
            @php $protPct = $stats['proteinTarget'] > 0 ? min(100, round($stats['todayProtein'] / $stats['proteinTarget'] * 100)) : 0; @endphp
            <div class="mt-2 h-1.5 bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $protPct }}%"></div>
            </div>
        </div>
    </div>

    {{-- Meals Remaining --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Meals Remaining') }}</span>
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['remaining'] }}</div>
            <div class="mt-1 text-[10px] text-gray-400">{{ __('of') }} {{ $stats['totalPlan'] }} {{ __('total meals') }}</div>
        </div>
    </div>

    {{-- Days Remaining --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Days Remaining') }}</span>
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#033133] to-[#025C5F] flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['daysRemaining'] }}</div>
            <div class="mt-1 text-[10px] text-gray-400">{{ __('until') }} {{ $stats['planRenewal'] }}</div>
        </div>
    </div>

    {{-- Avg Calories --}}
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Avg Calories') }}</span>
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#6E7A25] to-[#949B50] flex items-center justify-center flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
            </div>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['avgCalories']) }}</div>
            <div class="mt-1 text-[10px] text-gray-400">{{ __('kcal per day') }}</div>
        </div>
    </div>
</div>

{{-- Today's Nutrition Summary --}}
@if($hasActiveSubscription && $stats['todayCalories'] > 0)
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-bold text-gray-900">{{ __('Today\'s Nutrition') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Summary') }}</span></h3>
        <span class="text-[10px] text-gray-400">{{ count($todayMeals) }} {{ __('meal(s) scheduled') }}</span>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        {{-- Calories --}}
        <div class="text-center">
            <div class="w-16 h-16 mx-auto relative">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="28" fill="none" stroke="#f3f4f6" stroke-width="6"/>
                    <circle cx="32" cy="32" r="28" fill="none" stroke="#173327" stroke-width="6" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * pi() * 28 }}"
                        stroke-dashoffset="{{ 2 * pi() * 28 * (1 - ($calPct ?? 0) / 100) }}"
                        style="transition: stroke-dashoffset 1s ease;"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-gray-900">{{ $calPct ?? 0 }}%</span>
                </div>
            </div>
            <div class="mt-2 text-xs font-bold text-gray-900">{{ number_format($stats['todayCalories']) }} kcal</div>
            <div class="text-[10px] text-gray-400">{{ __('of') }} {{ number_format($stats['calorieTarget']) }}</div>
        </div>
        {{-- Protein --}}
        <div class="text-center">
            <div class="w-16 h-16 mx-auto relative">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="28" fill="none" stroke="#f3f4f6" stroke-width="6"/>
                    <circle cx="32" cy="32" r="28" fill="none" stroke="#6E7A25" stroke-width="6" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * pi() * 28 }}"
                        stroke-dashoffset="{{ 2 * pi() * 28 * (1 - ($protPct ?? 0) / 100) }}"
                        style="transition: stroke-dashoffset 1s ease;"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-gray-900">{{ $protPct ?? 0 }}%</span>
                </div>
            </div>
            <div class="mt-2 text-xs font-bold text-gray-900">{{ $stats['todayProtein'] }}g</div>
            <div class="text-[10px] text-gray-400">{{ __('of') }} {{ $stats['proteinTarget'] }}g</div>
        </div>
        {{-- Carbs --}}
        <div class="text-center">
            @php $carbPct = $stats['carbsTarget'] > 0 ? min(100, round($stats['todayCarbs'] / $stats['carbsTarget'] * 100)) : 0; @endphp
            <div class="w-16 h-16 mx-auto relative">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="28" fill="none" stroke="#f3f4f6" stroke-width="6"/>
                    <circle cx="32" cy="32" r="28" fill="none" stroke="#025C5F" stroke-width="6" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * pi() * 28 }}"
                        stroke-dashoffset="{{ 2 * pi() * 28 * (1 - $carbPct / 100) }}"
                        style="transition: stroke-dashoffset 1s ease;"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-gray-900">{{ $carbPct }}%</span>
                </div>
            </div>
            <div class="mt-2 text-xs font-bold text-gray-900">{{ $stats['todayCarbs'] }}g</div>
            <div class="text-[10px] text-gray-400">{{ __('of') }} {{ $stats['carbsTarget'] }}g</div>
        </div>
        {{-- Fat --}}
        <div class="text-center">
            @php $fatPct = $stats['fatTarget'] > 0 ? min(100, round($stats['todayFat'] / $stats['fatTarget'] * 100)) : 0; @endphp
            <div class="w-16 h-16 mx-auto relative">
                <svg class="w-16 h-16 -rotate-90" viewBox="0 0 64 64">
                    <circle cx="32" cy="32" r="28" fill="none" stroke="#f3f4f6" stroke-width="6"/>
                    <circle cx="32" cy="32" r="28" fill="none" stroke="#949B50" stroke-width="6" stroke-linecap="round"
                        stroke-dasharray="{{ 2 * pi() * 28 }}"
                        stroke-dashoffset="{{ 2 * pi() * 28 * (1 - $fatPct / 100) }}"
                        style="transition: stroke-dashoffset 1s ease;"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold text-gray-900">{{ $fatPct }}%</span>
                </div>
            </div>
            <div class="mt-2 text-xs font-bold text-gray-900">{{ $stats['todayFat'] }}g</div>
            <div class="text-[10px] text-gray-400">{{ __('of') }} {{ $stats['fatTarget'] }}g</div>
        </div>
    </div>
</div>
@endif

{{-- Today's Meals grouped by Category --}}
<div class="mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
            <span class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </span>
            {{ __("Today's") }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Meals') }}</span>
        </h3>
        @if(!empty($todayMeals))
        <span class="text-[10px] text-gray-400 font-medium">{{ count($todayMeals) }} {{ __('meal(s)') }} · {{ number_format(array_sum(array_map(fn($m) => $m['calories'] ?? 0, $todayMeals))) }} kcal</span>
        @endif
    </div>

    @if(!empty($todayMealsByCategory))
    <div class="space-y-4">
        @foreach($todayMealsByCategory as $catGroup)
        @php $catCalories = array_sum(array_map(fn($m) => $m['calories'] ?? 0, $catGroup['meals'])); @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Category header --}}
            <div class="px-4 py-3 bg-gradient-to-r from-[#173327]/5 to-[#6E7A25]/5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white flex-shrink-0 shadow-sm">
                    @if($catGroup['icon'] === 'sunrise')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m-4.5 3.5L6 6m9 0l1.5-1.5M4 12H2m20 0h-2M6.343 17.657L4.929 19.071M19.071 19.071l-1.414-1.414M12 18a6 6 0 00-6-6 6 6 0 006 6 6 6 0 006-6 6 6 0 00-6 6z"/></svg>
                    @elseif($catGroup['icon'] === 'sun')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    @elseif($catGroup['icon'] === 'moon')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    @elseif($catGroup['icon'] === 'cookie')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15h18v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3zM3 15l2.5-7.5A2 2 0 017.4 6h9.2a2 2 0 011.9 1.5L21 15M9 15V11M15 15V11"/></svg>
                    @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-gray-900">{{ $catGroup['name'] }}</p>
                    <p class="text-[10px] text-gray-500">{{ count($catGroup['meals']) }} {{ __('meal(s)') }} · {{ number_format($catCalories) }} kcal</p>
                </div>
            </div>

            {{-- Meal cards for this category - Flex layout --}}
            <div class="p-4">
                <div class="flex flex-col gap-3">
                    @foreach($catGroup['meals'] as $meal)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex">
                        {{-- Left gradient sidebar with icon --}}
                        <div class="w-20 sm:w-24 bg-gradient-to-br from-[#173327] to-[#6E7A25] relative overflow-hidden flex flex-col items-center justify-center flex-shrink-0 py-4">
                            <div class="absolute inset-0 bg-diamond opacity-[0.08]"></div>
                            <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
                            <svg class="w-8 h-8 text-white/80 relative z-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @if(isset($meal['quantity']) && $meal['quantity'] > 1)
                            <span class="relative z-10 inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-white/20 text-white border border-white/20 backdrop-blur-sm">x{{ $meal['quantity'] }}</span>
                            @endif
                        </div>

                        {{-- Right content area --}}
                        <div class="flex-1 p-4 flex flex-col justify-between">
                            <div class="flex items-start justify-between gap-3">
                                <h4 class="text-base sm:text-lg font-extrabold text-gray-900 leading-tight tracking-tight">{{ $meal['name'] ?? '' }}</h4>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-sm flex-shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    {{ number_format($meal['calories'] ?? 0) }} kcal
                                </span>
                            </div>
                            <div class="flex items-center gap-2 mt-3 flex-wrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-[#6E7A25]/10 text-[#6E7A25] border border-[#6E7A25]/10">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4"/></svg>
                                    {{ $meal['protein'] ?? 0 }}g {{ __('Protein') }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-[#025C5F]/10 text-[#025C5F] border border-[#025C5F]/10">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                                    {{ $meal['carbs'] ?? 0 }}g {{ __('Carbs') }}
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-[#949B50]/10 text-[#949B50] border border-[#949B50]/10">
                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    {{ $meal['fat'] ?? 0 }}g {{ __('Fat') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-100 p-10 text-center">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">{{ __('No meals scheduled for today') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ __('Your selected meals will appear here once you schedule them.') }}</p>
    </div>
    @endif
</div>

{{-- Weekly Meal Schedule - Interactive Calendar --}}
@php
    $totalWeekCalories = array_sum(array_map(fn($d) => $d['calories'] ?? 0, $weekMeals));
    $totalWeekMeals = array_sum(array_map(fn($d) => $d['mealCount'] ?? 0, $weekMeals));
    $todayIndex = (new DateTime())->format('N') - 1;
    $defaultDay = 0;
    for ($i = 0; $i < 7; $i++) {
        if ($weekMeals[$i]['mealCount'] > 0) {
            $defaultDay = $i;
            if ($i === $todayIndex) break;
        }
    }
@endphp

<div x-data="{ selectedDay: {{ $defaultDay }} }" x-cloak class="mb-6">
    {{-- Calendar Header with gradient --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white shadow-lg shadow-[#173327]/20 relative overflow-hidden mb-4">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold">{{ __('Weekly') }} {{ __('Schedule') }}</h3>
                    <p class="text-[10px] text-white/60 mt-0.5">{{ __('Your meals for each day of the week') }}</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-2xl font-extrabold">{{ $totalWeekMeals }}</p>
                <p class="text-[10px] text-white/60 font-medium">{{ number_format($totalWeekCalories) }} kcal {{ __('total') }}</p>
            </div>
        </div>
    </div>

    {{-- Day Selector Pills --}}
    <div class="grid grid-cols-7 gap-1.5 sm:gap-2 mb-4">
        @foreach($weekMeals as $idx => $day)
        <button @click="selectedDay = {{ $idx }}"
            class="rounded-2xl p-2 sm:p-3 text-center transition-all duration-300 border-2 relative"
            :class="selectedDay === {{ $idx }}
                ? 'bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white border-transparent shadow-lg shadow-[#173327]/20 scale-105'
                : 'bg-white border-gray-100 hover:border-[#6E7A25]/30 hover:shadow-sm'">
            <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wide"
               :class="selectedDay === {{ $idx }} ? 'text-white/60' : 'text-gray-400'">{{ $day['day'] }}</p>
            <div class="my-1.5 sm:my-2 mx-auto w-7 h-7 sm:w-8 sm:h-8 rounded-xl flex items-center justify-center font-bold text-xs sm:text-sm"
                :class="selectedDay === {{ $idx }} ? 'bg-white/15 text-white' : '{{ $day['mealCount'] > 0 ? "bg-[#6E7A25]/10 text-[#173327]" : "bg-gray-100 text-gray-300" }}'">{{ $day['mealCount'] }}</div>
            <p class="text-[8px] sm:text-[9px] font-medium"
               :class="selectedDay === {{ $idx }} ? 'text-white/50' : 'text-gray-400'">{{ $day['calories'] > 0 ? $day['calories'] . ' kcal' : '—' }}</p>
            @if($day['mealCount'] > 0)
            <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-[#6E7A25]" x-show="selectedDay !== {{ $idx }}"></span>
            @endif
        </button>
        @endforeach
    </div>

    {{-- Day Detail Panels --}}
    @foreach($weekMeals as $idx => $day)
    <div x-show="selectedDay === {{ $idx }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Day header bar --}}
        <div class="px-5 py-4 bg-gradient-to-r from-[#173327]/5 to-[#6E7A25]/5 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white font-bold text-sm shadow-sm">{{ substr($day['day'], 0, 1) }}</div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $day['day'] }} {{ __('Schedule') }}</p>
                    <p class="text-[10px] text-gray-500">{{ $day['mealCount'] }} {{ __('meals') }} · {{ number_format($day['calories']) }} kcal</p>
                </div>
            </div>
            @if($day['mealCount'] > 0)
            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-700 bg-green-50 px-2.5 py-1 rounded-full">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> {{ __('Scheduled') }}
            </span>
            @else
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-400">{{ __('No meals') }}</span>
            @endif
        </div>

        {{-- Categories with meals --}}
        <div class="p-5">
            @if(!empty($day['categories']))
            <div class="space-y-4">
                @foreach($day['categories'] as $catGroup)
                @php $dayCatCalories = array_sum(array_map(fn($m) => $m['calories'] ?? 0, $catGroup['meals'])); @endphp
                <div class="bg-gray-50/50 rounded-xl border border-gray-100 overflow-hidden">
                    {{-- Category header --}}
                    <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2 bg-gradient-to-r from-[#173327]/3 to-[#6E7A25]/3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white flex-shrink-0">
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
                        <span class="text-[10px] text-gray-400">{{ count($catGroup['meals']) }} {{ __('meal(s)') }} · {{ number_format($dayCatCalories) }} kcal</span>
                    </div>
                    {{-- Meal cards - flex layout, no images --}}
                    <div class="p-3">
                        <div class="flex flex-col gap-3">
                            @foreach($catGroup['meals'] as $meal)
                            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex">
                                {{-- Left gradient sidebar --}}
                                <div class="w-16 sm:w-20 bg-gradient-to-br from-[#173327] to-[#6E7A25] relative overflow-hidden flex flex-col items-center justify-center flex-shrink-0 py-3">
                                    <div class="absolute top-0 right-0 w-12 h-12 bg-white/10 rounded-full -mr-6 -mt-6"></div>
                                    <svg class="w-7 h-7 text-white/80 relative z-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @if(isset($meal['quantity']) && $meal['quantity'] > 1)
                                    <span class="relative z-10 inline-flex items-center px-1.5 py-0.5 rounded-full text-[8px] font-bold bg-white/20 text-white border border-white/20 backdrop-blur-sm">x{{ $meal['quantity'] }}</span>
                                    @endif
                                </div>
                                {{-- Right content --}}
                                <div class="flex-1 p-3 flex flex-col justify-between">
                                    <div class="flex items-start justify-between gap-2">
                                        <h4 class="text-sm sm:text-base font-extrabold text-gray-900 leading-tight tracking-tight">{{ $meal['name'] ?? '' }}</h4>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-sm flex-shrink-0">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            {{ number_format($meal['calories'] ?? 0) }} kcal
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 mt-2 flex-wrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-[#6E7A25]/10 text-[#6E7A25] border border-[#6E7A25]/10">P {{ $meal['protein'] ?? 0 }}g</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-[#025C5F]/10 text-[#025C5F] border border-[#025C5F]/10">C {{ $meal['carbs'] ?? 0 }}g</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold bg-[#949B50]/10 text-[#949B50] border border-[#949B50]/10">F {{ $meal['fat'] ?? 0 }}g</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-500">{{ __('No meals scheduled for this day yet.') }}</p>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

@endsection
