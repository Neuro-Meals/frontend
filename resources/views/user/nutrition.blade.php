@extends('layouts.user')

@section('title', 'Nutrition Tracker - Nutrio Meals')
@section('page_title', 'Nutrition Tracker')

@section('content')

{{-- Today's Summary --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#033133] to-[#259B00] rounded-xl p-4 text-white shadow-lg shadow-[#259B00]/20">
        <span class="text-[10px] font-medium text-white/60">Calories Today</span>
        <div class="text-2xl font-bold mt-1">{{ number_format($todayStats['calories']) }}</div>
        <div class="mt-2 h-1.5 bg-white/10 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full" style="width: {{ min(round($todayStats['calories'] / $todayStats['calorieTarget'] * 100), 100) }}%"></div>
        </div>
        <span class="text-[10px] text-white/50 mt-1 block">Target: {{ number_format($todayStats['calorieTarget']) }} kcal</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Protein</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $todayStats['protein'] }}g</div>
        <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-[#259B00] rounded-full" style="width: {{ min(round($todayStats['protein'] / $todayStats['proteinTarget'] * 100), 100) }}%"></div>
        </div>
        <span class="text-[10px] text-gray-400 mt-1 block">Target: {{ $todayStats['proteinTarget'] }}g</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Carbs</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $todayStats['carbs'] }}g</div>
        <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-amber-500 rounded-full" style="width: {{ min(round($todayStats['carbs'] / $todayStats['carbsTarget'] * 100), 100) }}%"></div>
        </div>
        <span class="text-[10px] text-gray-400 mt-1 block">Target: {{ $todayStats['carbsTarget'] }}g</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Fats</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $todayStats['fat'] }}g</div>
        <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-violet-500 rounded-full" style="width: {{ min(round($todayStats['fat'] / $todayStats['fatTarget'] * 100), 100) }}%"></div>
        </div>
        <span class="text-[10px] text-gray-400 mt-1 block">Target: {{ $todayStats['fatTarget'] }}g</span>
    </div>
</div>

{{-- Weekly Chart & Weight Progress --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Weekly Nutrition Chart --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-bold text-gray-900 mb-4">Weekly <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Nutrition</span></h3>
        @php $calMax = max(array_column($weeklyData, 'calories')) ?: 2000; @endphp
        <div class="flex items-end gap-2 h-48">
            @foreach($weeklyData as $day)
            @php $pct = ($day['calories'] / $calMax) * 100; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 bg-gradient-to-t from-[#259B00] to-[#259B00]/70 group-hover:opacity-80" style="height: {{ max($pct, 4) }}%"></div>
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
        <h3 class="text-sm font-bold text-gray-900 mb-4">Hydration & <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Activity</span></h3>
        <div class="space-y-5">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-xs font-medium text-gray-700">Water</span>
                    </div>
                    <span class="text-xs font-bold text-gray-900">{{ $todayStats['water'] }}/{{ $todayStats['waterTarget'] }} cups</span>
                </div>
                <div class="flex gap-1">
                    @for($i = 0; $i < $todayStats['waterTarget']; $i++)
                    <div class="flex-1 h-6 rounded {{ $i < $todayStats['water'] ? 'bg-blue-500' : 'bg-gray-100' }}"></div>
                    @endfor
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#259B00]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                        <span class="text-xs font-medium text-gray-700">Steps</span>
                    </div>
                    <span class="text-xs font-bold text-gray-900">{{ number_format($todayStats['steps']) }}</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-[#259B00] h-2.5 rounded-full transition-all duration-500" style="width: {{ min(round($todayStats['steps'] / $todayStats['stepsTarget'] * 100), 100) }}%"></div>
                </div>
                <span class="text-[10px] text-gray-400 mt-1 block">Target: {{ number_format($todayStats['stepsTarget']) }} steps</span>
            </div>
        </div>
    </div>
</div>

{{-- Weight Progress --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-900">Weight <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Journey</span></h3>
            <div class="text-right">
                <span class="text-lg font-bold text-[#259B00]">-{{ number_format($stats['lost'], 1) }}kg</span>
                <span class="text-[10px] text-gray-400 block">{{ $stats['remaining'] }}kg to goal</span>
            </div>
        </div>
        @php $wMax = max(array_column($weightProgress, 'weight')); $wMin = min(array_column($weightProgress, 'weight')); $wRange = $wMax - $wMin ?: 1; @endphp
        <div class="flex items-end gap-3 h-40">
            @foreach($weightProgress as $wp)
            @php $heightPct = (($wp['weight'] - $wMin) / $wRange) * 100; $isLast = $loop->last; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-32 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 {{ $isLast ? 'bg-gradient-to-t from-[#259B00] to-[#259B00]/70' : 'bg-gradient-to-t from-[#033133]/60 to-[#033133]/30' }} group-hover:opacity-80" style="height: {{ max($heightPct, 8) }}%"></div>
                    <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap">
                        {{ $wp['weight'] }}kg
                    </div>
                </div>
                <span class="text-[9px] text-gray-400 font-medium">{{ $wp['week'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="bg-gradient-to-br from-[#033133] to-[#01241f] rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#259B00]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <h3 class="text-sm font-bold mb-4 relative z-10">Progress <span class="text-[#259B00]">Summary</span></h3>
        <div class="space-y-3 relative z-10">
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">Current Weight</span>
                <span class="text-sm font-bold">{{ $stats['currentWeight'] }}kg</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">Start Weight</span>
                <span class="text-sm font-bold">{{ $stats['startWeight'] }}kg</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">Goal Weight</span>
                <span class="text-sm font-bold">{{ $stats['goalWeight'] }}kg</span>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-white/10">
                <span class="text-xs text-white/50">Streak</span>
                <span class="text-sm font-bold text-[#259B00]">{{ $stats['streakDays'] }} days 🔥</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">Adherence</span>
                <span class="text-sm font-bold text-[#259B00]">{{ $stats['adherenceRate'] }}%</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-xs text-white/50">Avg Daily Calories</span>
                <span class="text-sm font-bold">{{ number_format($stats['avgDailyCalories']) }}</span>
            </div>
        </div>
    </div>
</div>

@endsection
