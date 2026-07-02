@extends('layouts.admin')

@section('title', 'Subscriptions - Nutrio Meals')
@section('page_title', 'Subscriptions')

@section('content')
{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="kpi-card bg-gradient-to-br from-[#033133] to-[#259B00] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#259B00]/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Total Plans</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['total'] }}</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['active'] }} active · {{ $stats['draft'] }} draft</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-blue-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Total Subscribers</p>
            <p class="text-2xl font-bold tracking-tight">{{ number_format($stats['totalSubscribers']) }}</p>
            <p class="text-xs text-white/50 mt-1">+{{ $stats['growthRate'] }}% growth</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-amber-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Monthly Recurring</p>
            <p class="text-2xl font-bold tracking-tight">SAR {{ number_format($stats['mrr']) }}</p>
            <p class="text-xs text-white/50 mt-1">Avg SAR {{ $stats['avgRevenue'] }}/plan</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-violet-500 to-purple-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-violet-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Churn Rate</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['churnRate'] }}%</p>
            <p class="text-xs text-white/50 mt-1">Retention {{ 100 - $stats['churnRate'] }}%</p>
        </div>
    </div>
</div>

{{-- Action Bar --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center bg-white rounded-lg px-3 py-2 border border-gray-100 shadow-sm flex-1 max-w-xs">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="Search subscriptions..." class="bg-transparent text-sm outline-none flex-1 text-gray-600 placeholder-gray-400">
    </div>
    <button class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#033133] to-[#259B00] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create Plan
    </button>
</div>

{{-- Plans Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($plans as $plan)
    <div class="kpi-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="p-5 border-b border-gray-50" style="background: linear-gradient(135deg, {{ $plan['color'] }}15, {{ $plan['color'] }}05);">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: {{ $plan['color'] }}20;">
                    <svg class="w-6 h-6" style="color: {{ $plan['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $plan['status'] === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200' }}">
                    {{ ucfirst($plan['status']) }}
                </span>
            </div>
            <h3 class="text-base font-bold text-gray-900">{{ $plan['name'] }}</h3>
            <p class="text-xs text-gray-400 mt-1">{{ $plan['calories'] }} kcal/day</p>
        </div>
        {{-- Body --}}
        <div class="p-5">
            <div class="flex items-end gap-1 mb-4">
                <span class="text-2xl font-bold text-gray-900">SAR {{ $plan['price'] }}</span>
                <span class="text-xs text-gray-400 mb-1">/ {{ $plan['duration'] }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">Meals</p>
                    <p class="text-sm font-bold text-gray-900">{{ $plan['meals'] }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">Subscribers</p>
                    <p class="text-sm font-bold text-gray-900">{{ $plan['subscribers'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button class="flex-1 px-3 py-2 text-xs font-bold text-white rounded-lg transition-all" style="background: {{ $plan['color'] }};">
                    Edit Plan
                </button>
                <button class="px-3 py-2 text-xs font-medium text-gray-500 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    View
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
