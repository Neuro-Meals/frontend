@extends('layouts.user')

@section('title', __('Nutrition Tracker') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Nutrition Tracker'))

@section('content')

{{-- Today's Summary --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <span class="text-[10px] font-medium text-white/60">{{ __('Calories Today') }}</span>
        <div class="text-2xl font-bold mt-1">{{ number_format($todayStats['calories']) }}</div>
        <div class="mt-2 h-1.5 bg-white/10 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full" style="width: {{ min(round($todayStats['calories'] / ($todayStats['calorieTarget'] ?: 1) * 100), 100) }}%"></div>
        </div>
        <span class="text-[10px] text-white/50 mt-1 block">{{ __('Target') }}: {{ number_format($todayStats['calorieTarget']) }} kcal</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <span class="text-[10px] font-medium text-gray-400">{{ __('Protein') }}</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $todayStats['protein'] }}g</div>
        <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-[#6E7A25] rounded-full" style="width: {{ min(round($todayStats['protein'] / ($todayStats['proteinTarget'] ?: 1) * 100), 100) }}%"></div>
        </div>
        <span class="text-[10px] text-gray-400 mt-1 block">{{ __('Target') }}: {{ $todayStats['proteinTarget'] }}g</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <span class="text-[10px] font-medium text-gray-400">{{ __('Carbs') }}</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $todayStats['carbs'] }}g</div>
        <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-[#6E7A25] rounded-full" style="width: {{ min(round($todayStats['carbs'] / ($todayStats['carbsTarget'] ?: 1) * 100), 100) }}%"></div>
        </div>
        <span class="text-[10px] text-gray-400 mt-1 block">{{ __('Target') }}: {{ $todayStats['carbsTarget'] }}g</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-[10px] font-medium text-gray-400">{{ __('Fats') }}</span>
        </div>
        <div class="text-2xl font-bold text-gray-900">{{ $todayStats['fat'] }}g</div>
        <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-[#6E7A25] rounded-full" style="width: {{ min(round($todayStats['fat'] / ($todayStats['fatTarget'] ?: 1) * 100), 100) }}%"></div>
        </div>
        <span class="text-[10px] text-gray-400 mt-1 block">{{ __('Target') }}: {{ $todayStats['fatTarget'] }}g</span>
    </div>
</div>

{{-- Today's Meals with Images, Orders & Serving Info --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 sm:p-6 mb-6">
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-lg font-bold text-gray-900">{{ __("Today's") }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Meals') }}</span></h3>
        <a href="{{ route('user.meals') }}" class="text-xs font-bold text-[#6E7A25] hover:text-[#173327] transition-colors">{{ __('View all') }} →</a>
    </div>

    @if(!empty($todayMeals))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @foreach($todayMeals as $meal)
        <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="h-44 sm:h-52 overflow-hidden bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 relative">
                <img src="{{ meal_image_url($meal['image'] ?? null) }}" alt="{{ $meal['name'] ?? __('Meal') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/meal-placeholder.svg') }}'">
                <div class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur text-[10px] font-bold text-[#173327] shadow-sm">
                    {{ $meal['time'] ?? '' }}
                </div>
                <div class="absolute top-3 right-3 px-2.5 py-1 rounded-full bg-[#173327]/90 backdrop-blur text-[10px] font-bold text-white shadow-sm">
                    {{ is_array($meal['category'] ?? null) ? ($meal['category']['name_en'] ?? $meal['category']['name'] ?? 'Meal') : ($meal['category'] ?? 'Meal') }}
                </div>
            </div>
            <div class="p-4 sm:p-5">
                <h4 class="text-base font-bold text-gray-900 mb-2">{{ $meal['name'] ?? '' }}</h4>

                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                    <span class="font-medium text-[#6E7A25]">{{ number_format($meal['calories'] ?? 0) }} kcal</span>
                    <span>P {{ $meal['protein'] ?? 0 }}g · C {{ $meal['carbs'] ?? 0 }}g · F {{ $meal['fat'] ?? 0 }}g</span>
                </div>

                <div class="grid grid-cols-3 gap-2 mb-4">
                    <div class="text-center bg-gray-50 rounded-lg py-2">
                        <p class="text-[10px] text-gray-400">{{ __('Price') }}</p>
                        <p class="text-xs font-bold text-gray-900">SAR {{ number_format($meal['price'] ?? 0, 2) }}</p>
                    </div>
                    <div class="text-center bg-gray-50 rounded-lg py-2">
                        <p class="text-[10px] text-gray-400">{{ __('Serving') }}</p>
                        <p class="text-xs font-bold text-gray-900">{{ $meal['serving'] ?? '-' }}</p>
                    </div>
                    <div class="text-center bg-gray-50 rounded-lg py-2">
                        <p class="text-[10px] text-gray-400">{{ __('Orders') }}</p>
                        <p class="text-xs font-bold text-gray-900">{{ ($meal['orders'] ?? 0) > 0 ? ($meal['orders'] ?? 0) : __('None') }}</p>
                    </div>
                </div>

                @if(($meal['orders'] ?? 0) > 0)
                <div class="flex items-center gap-2 text-[10px] font-medium text-emerald-600 bg-emerald-50 rounded-lg px-3 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $meal['orders'] ?? 0 }} {{ __('orders placed for this meal') }}</span>
                </div>
                @else
                <div class="flex items-center gap-2 text-[10px] font-medium text-gray-500 bg-gray-50 rounded-lg px-3 py-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('No orders yet for this meal') }}</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="flex flex-col items-center justify-center py-12 text-center">
        <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h4 class="text-sm font-bold text-gray-900 mb-1">{{ __('No meals scheduled today') }}</h4>
        <p class="text-xs text-gray-400 max-w-xs">{{ __('Your meal plan will appear here once your subscription is active.') }}</p>
    </div>
    @endif
</div>

{{-- Weekly Chart & Weight Progress --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Weekly Nutrition Chart --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('Weekly') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Nutrition') }}</span></h3>
        @php $calMax = max(array_column($weeklyData, 'calories')) ?: 2000; @endphp
        <div class="flex items-end gap-2 h-48">
            @foreach($weeklyData as $day)
            @php $pct = ($day['calories'] / $calMax) * 100; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 bg-gradient-to-t from-[#6E7A25] to-[#6E7A25]/70 group-hover:opacity-80" style="height: {{ max($pct, 4) }}%"></div>
                    <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap">
                        {{ number_format($day['calories']) }} kcal
                    </div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $day['day'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Water & Steps --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('Hydration') }} &amp; <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Activity') }}</span></h3>
        <div class="space-y-5">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#025C5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-medium text-gray-700">{{ __('Water') }}</span>
                    </div>
                    <span class="text-xs font-bold text-gray-900">{{ $todayStats['water'] }}/{{ $todayStats['waterTarget'] }} cups</span>
                </div>
                <div class="flex gap-1">
                    @for($i = 0; $i < $todayStats['waterTarget']; $i++)
                    <div class="flex-1 h-6 rounded {{ $i < $todayStats['water'] ? 'bg-[#025C5F]' : 'bg-gray-100' }}"></div>
                    @endfor
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        <span class="text-xs font-medium text-gray-700">{{ __('Steps') }}</span>
                    </div>
                    <span class="text-xs font-bold text-gray-900">{{ number_format($todayStats['steps']) }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-[#6E7A25] h-2.5 rounded-full transition-all duration-500" style="width: {{ min(round($todayStats['steps'] / ($todayStats['stepsTarget'] ?: 1) * 100), 100) }}%"></div>
                </div>
                <span class="text-[10px] text-gray-400 mt-1 block">{{ __('Target') }}: {{ number_format($todayStats['stepsTarget']) }} steps</span>
            </div>
        </div>
    </div>
</div>

{{-- Weight Progress --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900">{{ __('Weight') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Journey') }}</span></h3>
            <div class="text-right">
                <span class="text-lg font-bold text-[#6E7A25]">-{{ number_format($stats['lost'], 1) }}kg</span>
                <span class="text-[10px] text-gray-400 block">{{ $stats['remaining'] }}kg {{ __('to goal') }}</span>
            </div>
        </div>
        @if(!empty($weightProgress))
            @php $weights = array_column($weightProgress, 'weight'); $wMax = max($weights); $wMin = min($weights); $wRange = $wMax - $wMin ?: 1; @endphp
            <div class="flex items-end gap-3 h-40">
                @foreach($weightProgress as $wp)
                @php $heightPct = (($wp['weight'] - $wMin) / $wRange) * 100; $isLast = $loop->last; @endphp
                <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-32 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 {{ $isLast ? 'bg-gradient-to-t from-[#6E7A25] to-[#6E7A25]/70' : 'bg-gradient-to-t from-[#173327]/60 to-[#173327]/30' }} group-hover:opacity-80" style="height: {{ max($heightPct, 8) }}%"></div>
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap">
                            {{ $wp['weight'] }}kg
                        </div>
                    </div>
                    <span class="text-[9px] text-gray-400 font-medium">{{ $wp['week'] }}</span>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-40 text-gray-400">
                <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                <span class="text-sm">{{ __('No weight data available yet') }}</span>
            </div>
        @endif
    </div>

    {{-- Summary Stats --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#122620] rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#6E7A25]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <h3 class="text-sm font-bold mb-4 relative z-10">{{ __('Progress') }} <span class="text-[#6E7A25]">{{ __('Summary') }}</span></h3>
        <div class="space-y-3 relative z-10">
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">{{ __('Current Weight') }}</span>
                <span class="text-sm font-bold">{{ $stats['currentWeight'] }}kg</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">{{ __('Start Weight') }}</span>
                <span class="text-sm font-bold">{{ $stats['startWeight'] }}kg</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">{{ __('Goal Weight') }}</span>
                <span class="text-sm font-bold">{{ $stats['goalWeight'] }}kg</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-white/10">
                <span class="text-xs text-white/50">{{ __('Streak') }}</span>
                <span class="text-sm font-bold text-[#6E7A25]">{{ $stats['streakDays'] }} {{ __('days') }} 🔥</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">{{ __('Adherence') }}</span>
                <span class="text-sm font-bold text-[#6E7A25]">{{ $stats['adherenceRate'] }}%</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">{{ __('Avg Daily Calories') }}</span>
                <span class="text-sm font-bold">{{ number_format($stats['avgDailyCalories']) }}</span>
            </div>
        </div>
    </div>
</div>

@endsection
