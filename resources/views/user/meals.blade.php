@extends('layouts.user')

@section('title', 'My Meals - Nutrio Meals')
@section('page_title', 'My Meals')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20">
        <span class="text-[10px] font-medium text-white/60">This Week</span>
        <div class="text-2xl font-bold mt-1">{{ $stats['totalThisWeek'] }}<span class="text-sm text-white/50">/{{ $stats['totalPlan'] }}</span></div>
        <span class="text-[10px] text-white/50">{{ $stats['remaining'] }} remaining</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Avg Calories</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['avgCalories']) }}</div>
        <span class="text-[10px] text-gray-400">kcal per day</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Favorite Meal</span>
        <div class="text-sm font-bold text-gray-900 mt-1 truncate">{{ $stats['favoriteMeal'] }}</div>
        <span class="text-[10px] text-gray-400">{{ $stats['favoriteCount'] }} times ordered</span>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">Plan Total</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['totalPlan'] }}</div>
        <span class="text-[10px] text-gray-400">meals in plan</span>
    </div>
</div>

{{-- Today's Meals --}}
<div class="mb-6">
    <h3 class="text-sm font-bold text-gray-900 mb-4">Today's <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Meals</span></h3>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        @foreach($todayMeals as $meal)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
            <div class="h-32 bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 relative flex items-center justify-center overflow-hidden">
                @if(!empty($meal['image']) && $meal['image'] !== 'whitelogo.png')
                    <img src="{{ asset($meal['image']) }}" alt="{{ $meal['name'] }}" class="absolute inset-0 w-full h-full object-cover">
                @else
                    <svg class="w-12 h-12 text-[#6E7A25]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                @endif
                <span class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $meal['status'] === 'delivered' ? 'bg-green-50 text-green-700' : 'bg-[#949B50]/10 text-[#949B50]' }}">{{ ucfirst($meal['status']) }}</span>
            </div>
            <div class="p-4">
                <p class="text-[10px] text-gray-400">{{ $meal['time'] }}</p>
                <h4 class="text-sm font-bold text-gray-900 mt-1">{{ $meal['name'] }}</h4>
                <div class="flex items-center gap-3 mt-3 text-[10px] text-gray-500">
                    <span class="font-bold text-gray-900">{{ $meal['calories'] }} kcal</span>
                    <span>P: {{ $meal['protein'] }}g</span>
                    <span>C: {{ $meal['carbs'] }}g</span>
                    <span>F: {{ $meal['fat'] }}g</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Weekly Meal Schedule --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">Weekly <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Schedule</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">Day</th>
                    <th class="px-5 py-3 font-medium">Meals</th>
                    <th class="px-5 py-3 font-medium">Calories</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($weekMeals as $day)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-semibold text-gray-900">{{ $day['day'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $day['meals'] }} meals</td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ number_format($day['calories']) }} kcal</td>
                    <td class="px-5 py-3">
                        @if($day['completed'])
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-700"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Completed</span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-[#949B50]/10 text-[#949B50]">In Progress</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
