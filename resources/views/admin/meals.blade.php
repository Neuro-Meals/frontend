@extends('layouts.admin')

@section('title', 'Meals & Nutrition - Nutrio Meals')
@section('page_title', 'Meals & Nutrition')

@section('content')
@php
    $statusColors = [
        'active' => 'bg-green-50 text-green-700 border-green-200',
        'draft' => 'bg-gray-50 text-gray-500 border-gray-200',
    ];
    $catColors = [
        'High Protein' => 'bg-green-50 text-green-700',
        'Vegan' => 'bg-purple-50 text-purple-700',
        'Keto' => 'bg-blue-50 text-blue-700',
        'Breakfast' => 'bg-amber-50 text-amber-700',
        'Maintenance' => 'bg-teal-50 text-teal-700',
    ];
@endphp

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="kpi-card bg-gradient-to-br from-[#033133] to-[#259B00] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#259B00]/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Total Meals</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['total'] }}</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['active'] }} active · {{ $stats['draft'] }} draft</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-blue-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Categories</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['categories'] }}</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-amber-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Total Orders</p>
            <p class="text-2xl font-bold tracking-tight">{{ number_format($stats['totalOrders']) }}</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-violet-500 to-purple-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-violet-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Avg Rating</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['avgRating'] }}<span class="text-lg">/5</span></p>
        </div>
    </div>
</div>

{{-- Category Distribution --}}
<div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm mb-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">Category <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Distribution</span></h3>
    <div class="flex flex-wrap gap-3">
        @foreach($categories as $cat)
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-100 hover:shadow-sm transition-all">
            <div class="w-3 h-3 rounded-full" style="background: {{ $cat['color'] }};"></div>
            <span class="text-xs font-semibold text-gray-700">{{ $cat['name'] }}</span>
            <span class="text-xs font-bold text-gray-900">{{ $cat['count'] }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Action Bar --}}
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center bg-white rounded-lg px-3 py-2 border border-gray-100 shadow-sm flex-1 max-w-xs">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="Search meals..." class="bg-transparent text-sm outline-none flex-1 text-gray-600 placeholder-gray-400">
    </div>
    <button class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#033133] to-[#259B00] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Meal
    </button>
</div>

{{-- Meals Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($meals as $meal)
    <div class="kpi-card bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Image --}}
        @if($meal['image'])
        <div class="h-40 overflow-hidden">
            <img src="{{ asset('images/meals/' . $meal['image']) }}" alt="{{ $meal['name'] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
        </div>
        @else
        <div class="h-40 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        @endif
        {{-- Body --}}
        <div class="p-5">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ $meal['name'] }}</h3>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $catColors[$meal['category']] ?? 'bg-gray-50 text-gray-600' }}">{{ $meal['category'] }}</span>
                </div>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$meal['status']] }}">
                    {{ ucfirst($meal['status']) }}
                </span>
            </div>
            {{-- Macros --}}
            <div class="grid grid-cols-4 gap-2 mt-4">
                <div class="text-center bg-gray-50 rounded-lg py-2">
                    <p class="text-[10px] text-gray-400">Kcal</p>
                    <p class="text-xs font-bold text-gray-900">{{ $meal['calories'] }}</p>
                </div>
                <div class="text-center bg-green-50 rounded-lg py-2">
                    <p class="text-[10px] text-green-400">Protein</p>
                    <p class="text-xs font-bold text-green-700">{{ $meal['protein'] }}g</p>
                </div>
                <div class="text-center bg-amber-50 rounded-lg py-2">
                    <p class="text-[10px] text-amber-400">Carbs</p>
                    <p class="text-xs font-bold text-amber-700">{{ $meal['carbs'] }}g</p>
                </div>
                <div class="text-center bg-blue-50 rounded-lg py-2">
                    <p class="text-[10px] text-blue-400">Fat</p>
                    <p class="text-xs font-bold text-blue-700">{{ $meal['fat'] }}g</p>
                </div>
            </div>
            {{-- Footer --}}
            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-50">
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-xs font-bold text-gray-700">{{ $meal['rating'] > 0 ? $meal['rating'] : '—' }}</span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $meal['orders'] }} orders</span>
                </div>
                <button class="text-xs font-bold text-[#259B00] hover:text-[#033133] transition-colors">Edit →</button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
