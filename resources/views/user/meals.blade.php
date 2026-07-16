@extends('layouts.user')

@section('title', 'My Meals - Nutrio Meals')
@section('page_title', 'My Meals')

@section('content')

{{-- Subscription Banner --}}
@if($hasActiveSubscription)
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-xl p-4 sm:p-5 text-white shadow-lg mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold">{{ $activeSubscription['plan_name'] ?? 'Active Plan' }}</p>
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

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <span class="text-[10px] font-medium text-white/60">This Week</span>
        <div class="text-2xl font-bold mt-1">{{ $stats['totalThisWeek'] }}<span class="text-sm text-white/50">/{{ $stats['totalPlan'] }}</span></div>
        <span class="text-[10px] text-white/50">{{ $stats['remaining'] }} remaining</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
        </div>
        <div>
            <span class="text-[10px] font-medium text-gray-400">Avg Calories</span>
            <div class="text-lg font-bold text-gray-900">{{ number_format($stats['avgCalories']) }}</div>
            <span class="text-[10px] text-gray-400">kcal per day</span>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </div>
        <div>
            <span class="text-[10px] font-medium text-gray-400">Favorite Meal</span>
            <div class="text-sm font-bold text-gray-900 truncate">{{ $stats['favoriteMeal'] }}</div>
            <span class="text-[10px] text-gray-400">{{ $stats['favoriteCount'] }} times ordered</span>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <span class="text-[10px] font-medium text-gray-400">Plan Total</span>
            <div class="text-lg font-bold text-gray-900">{{ $stats['totalPlan'] }}</div>
            <span class="text-[10px] text-gray-400">meals in plan</span>
        </div>
    </div>
</div>

{{-- Today's Meals grouped by Category --}}
<div class="mb-6">
    <h3 class="text-sm font-bold text-gray-900 mb-4">Today's <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Meals</span></h3>

    @if(!empty($todayMealsByCategory))
    <div class="space-y-5">
        @foreach($todayMealsByCategory as $catGroup)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            {{-- Category header --}}
            <div class="px-4 py-3 bg-gradient-to-r from-[#173327]/5 to-[#6E7A25]/5 border-b border-gray-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white flex-shrink-0">
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
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $catGroup['name'] }}</p>
                    <p class="text-[10px] text-gray-500">{{ count($catGroup['meals']) }} {{ __('meal(s)') }}</p>
                </div>
            </div>

            {{-- Meal cards for this category --}}
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($catGroup['meals'] as $meal)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
                        <div class="h-32 bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 relative flex items-center justify-center overflow-hidden">
                            <img src="{{ meal_image_url($meal['image'] ?? null) }}" alt="{{ $meal['name'] ?? __('Meal') }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'">
                            <svg class="w-12 h-12 text-[#6E7A25]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @if(isset($meal['quantity']) && $meal['quantity'] > 1)
                            <span class="absolute top-3 left-3 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#173327] text-white">x{{ $meal['quantity'] }}</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h4 class="text-sm font-bold text-gray-900 mt-1">{{ $meal['name'] ?? '' }}</h4>
                            <div class="flex items-center gap-3 mt-3 text-[10px] text-gray-500">
                                <span class="font-bold text-gray-900">{{ number_format($meal['calories'] ?? 0) }} kcal</span>
                                <span>P: {{ $meal['protein'] ?? 0 }}g</span>
                                <span>C: {{ $meal['carbs'] ?? 0 }}g</span>
                                <span>F: {{ $meal['fat'] ?? 0 }}g</span>
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
    <div class="bg-white rounded-xl border border-gray-100 p-8 text-center">
        <div class="w-14 h-14 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-3">
            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">{{ __('No meals scheduled for today') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ __('Your selected meals will appear here once you schedule them.') }}</p>
    </div>
    @endif
</div>

{{-- Weekly Meal Schedule --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="text-lg font-bold text-gray-900">Weekly <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Schedule</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('Your meals for each day of the week') }}</p>
        </div>
    </div>

    <div class="space-y-5">
        @foreach($weekMeals as $day)
        <div class="bg-gray-50/50 rounded-2xl border border-gray-100 overflow-hidden">
            {{-- Day header --}}
            <div class="px-4 sm:px-5 py-3 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        {{ substr($day['day'], 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $day['day'] }}</p>
                        <p class="text-[10px] text-gray-500">{{ $day['mealCount'] }} {{ __('meals') }} · {{ number_format($day['calories']) }} kcal</p>
                    </div>
                </div>
                @if($day['completed'])
                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-700 bg-green-50 px-2.5 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> {{ __('Completed') }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-[#949B50]/10 text-[#949B50]">{{ __('In Progress') }}</span>
                @endif
            </div>

            {{-- Meals for the day grouped by category --}}
            <div class="p-4 sm:p-5">
                @if(!empty($day['categories']))
                <div class="space-y-4">
                    @foreach($day['categories'] as $catGroup)
                    <div class="bg-gray-50/50 rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-3 py-2 border-b border-gray-100 flex items-center gap-2">
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
                        <div class="p-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($catGroup['meals'] as $meal)
                                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
                                    <div class="h-24 sm:h-28 bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 relative flex items-center justify-center overflow-hidden">
                                        <img src="{{ meal_image_url($meal['image'] ?? null) }}" alt="{{ $meal['name'] ?? __('Meal') }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'">
                                        <svg class="w-8 h-8 text-[#6E7A25]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        @if(isset($meal['quantity']) && $meal['quantity'] > 1)
                                        <span class="absolute top-2 left-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-[#173327] text-white">x{{ $meal['quantity'] }}</span>
                                        @endif
                                    </div>
                                    <div class="p-2.5">
                                        <h4 class="text-xs font-bold text-gray-900 truncate">{{ $meal['name'] ?? '' }}</h4>
                                        <div class="flex items-center gap-2 mt-1.5 text-[10px] text-gray-500">
                                            <span class="font-bold text-gray-900">{{ number_format($meal['calories'] ?? 0) }} kcal</span>
                                            <span>P: {{ $meal['protein'] ?? 0 }}g</span>
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
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-2">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </div>
                    <p class="text-sm text-gray-500">{{ __('No meals scheduled for this day yet.') }}</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
