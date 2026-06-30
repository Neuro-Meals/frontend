@extends('layouts.user')

@section('title', 'Dashboard - Nutrio Meals')
@section('page_title', 'Dashboard')

@section('content')

{{-- Welcome --}}
<div class="mb-6 flex flex-row items-start sm:items-center justify-between gap-3 flex-wrap">
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 tracking-tight">Hello {{ Auth::user()->name }} 👋</h1>
        <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Here's your nutrition journey today.</p>
    </div>
    <div class="flex items-center gap-2 shrink-0">
        <a href="#" class="px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span class="hidden sm:inline">Export</span>
        </a>
        <a href="#" class="px-3 py-1.5 text-xs font-medium bg-brand-500 text-white rounded-lg hover:bg-brand-400 transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span class="hidden sm:inline">New Order</span><span class="sm:hidden">Order</span>
        </a>
    </div>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-2 gap-3 sm:gap-4 xl:grid-cols-4 mb-6">
    {{-- Active Plan --}}
    <div class="card-sm bg-gradient-to-br from-brand-600 to-brand-700 rounded-xl border border-brand-500 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-brand-100">Active Plan</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $stats['activePlan'] }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-brand-200 font-medium relative z-10">Manage your subscription</div>
    </div>

    {{-- Meals This Week --}}
    <div class="card-sm bg-gradient-to-br from-sky-500 to-sky-600 rounded-xl border border-sky-400 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-sky-100">Meals This Week</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $stats['mealsThisWeek'] }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-sky-100 font-medium relative z-10">Out of 21 planned</div>
    </div>

    {{-- Daily Calories --}}
    <div class="card-sm bg-gradient-to-br from-accent-400 to-accent-500 rounded-xl border border-accent-300 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-accent-50">Daily Calories</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-accent-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ number_format($stats['dailyCalories']) }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-accent-50 font-medium relative z-10">Target: 2,000 kcal</div>
    </div>

    {{-- Streak --}}
    <div class="card-sm bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl border border-violet-400 p-3 sm:p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
        <div class="flex items-start justify-between relative z-10">
            <span class="text-[10px] sm:text-xs font-medium text-violet-100">Streak Days</span>
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-violet-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.24 17 7c.586 1.172 1.5 2 3 2.5a8 8 0 01-2.343 9.157z"/></svg>
        </div>
        <div class="mt-2 sm:mt-3 text-xl sm:text-3xl font-bold tracking-tight text-white relative z-10">{{ $stats['streakDays'] }}</div>
        <div class="mt-1 text-[10px] sm:text-xs text-violet-100 font-medium relative z-10">Keep it up!</div>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 gap-4 lg:grid-cols-3 mb-6">
    {{-- Calorie Tracking --}}
    <div class="bg-white rounded-xl border p-5 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Calorie Tracking</h3>
                <p class="text-xs text-gray-400">Last 7 days</p>
            </div>
            <div class="text-right">
                <div class="text-lg font-semibold text-gray-900">{{ number_format($stats['dailyCalories']) }} kcal</div>
                <div class="text-xs text-brand-500 font-medium">On track</div>
            </div>
        </div>
        @php
            $calorieDays = array_fill(0, 7, 0);
            $calMax = max($calorieDays) ?: 2000;
            $dayLabels = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        @endphp
        <div class="flex items-end gap-2 h-56">
            @foreach($calorieDays as $i => $cal)
                @php $pct = ($cal / $calMax) * 100; @endphp
                <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-48 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 bg-brand-500" style="height: {{ max($pct, 4) }}%"></div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $dayLabels[$i] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Nutrition Breakdown --}}
    <div class="bg-white rounded-xl border p-5">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Nutrition Breakdown</h3>
        <div class="space-y-4">
            {{-- Protein --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-700">Protein</span>
                    <span class="text-xs font-semibold text-gray-900">{{ $stats['proteinTarget'] }}g</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-brand-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
            {{-- Carbs --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-700">Carbs</span>
                    <span class="text-xs font-semibold text-gray-900">0g</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-accent-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
            {{-- Fats --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-700">Fats</span>
                    <span class="text-xs font-semibold text-gray-900">0g</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-violet-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>
            </div>
        </div>
        <div class="mt-5 pt-4 border-t border-gray-50">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-brand-400 rounded-full animate-pulse"></span>
                <p class="text-[11px] text-gray-400">Tracking in real-time</p>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b px-5 py-4 gap-3">
        <div>
            <h3 class="text-sm font-semibold text-gray-900">Recent Orders</h3>
            <p class="text-xs text-gray-400">Your latest meal orders</p>
        </div>
        <a href="#" class="text-xs font-medium text-brand-500 hover:text-brand-600">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500">
                    <th class="px-5 py-3 font-medium">Order ID</th>
                    <th class="px-5 py-3 font-medium">Plan</th>
                    <th class="px-5 py-3 font-medium">Amount</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                        <p class="text-sm font-medium">No orders yet</p>
                        <p class="text-xs mt-1">Start your healthy journey by placing your first order.</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- Mobile Bottom Nav --}}
<div class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-lg border-t border-gray-200/80 lg:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.05)]">
    <div class="flex items-center justify-around py-2 px-2 max-w-lg mx-auto">
        <a href="{{ route('user.dashboard') }}" class="flex flex-col items-center gap-0.5 py-1.5 px-2 rounded-xl active:scale-95 transition-all hover:bg-brand-50 group">
            <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center group-hover:bg-brand-500 transition-colors">
                <svg class="w-4 h-4 text-brand-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            </div>
            <span class="text-[9px] font-semibold text-gray-600 group-hover:text-brand-700 transition-colors">Home</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-0.5 py-1.5 px-2 rounded-xl active:scale-95 transition-all hover:bg-brand-50 group">
            <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center group-hover:bg-brand-500 transition-colors">
                <svg class="w-4 h-4 text-brand-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <span class="text-[9px] font-semibold text-gray-600 group-hover:text-brand-700 transition-colors">Meals</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-0.5 py-1.5 px-2 rounded-xl active:scale-95 transition-all hover:bg-brand-50 group">
            <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center group-hover:bg-brand-500 transition-colors">
                <svg class="w-4 h-4 text-brand-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-[9px] font-semibold text-gray-600 group-hover:text-brand-700 transition-colors">Nutrition</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-0.5 py-1.5 px-2 rounded-xl active:scale-95 transition-all hover:bg-brand-50 group">
            <div class="w-9 h-9 rounded-full bg-brand-100 flex items-center justify-center group-hover:bg-brand-500 transition-colors">
                <svg class="w-4 h-4 text-brand-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-[9px] font-semibold text-gray-600 group-hover:text-brand-700 transition-colors">Settings</span>
        </a>
    </div>
</div>

<div class="h-16 lg:hidden"></div>

@endsection
