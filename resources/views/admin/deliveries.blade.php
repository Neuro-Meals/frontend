@extends('layouts.admin')

@section('title', __('Deliveries') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Deliveries'))

@section('content')
<div x-data="deliveryManager()">
@php
    $statusColors = [
        'delivered' => 'bg-green-50 text-green-700 border-green-200',
        'en_route' => 'bg-blue-50 text-blue-700 border-blue-200',
        'out_for_delivery' => 'bg-blue-50 text-blue-700 border-blue-200',
        'preparing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'assigned' => 'bg-purple-50 text-purple-700 border-purple-200',
        'scheduled' => 'bg-gray-50 text-gray-600 border-gray-200',
        'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
        'failed' => 'bg-red-50 text-red-600 border-red-200',
        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
    ];
    $statusLabels = [
        'delivered' => __('Delivered'),
        'en_route' => __('En Route'),
        'out_for_delivery' => __('Out for Delivery'),
        'preparing' => __('Preparing'),
        'assigned' => __('Assigned'),
        'scheduled' => __('Scheduled'),
        'pending' => __('Pending'),
        'failed' => __('Failed'),
        'cancelled' => __('Cancelled'),
    ];
@endphp

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm">
    {{ session('error') }}
</div>
@endif

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Total Deliveries --}}
    <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white shadow-lg relative overflow-hidden" style="animation-delay: 0.1s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 12px);"></div>
        <div class="relative z-10">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 001 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Total Deliveries') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['total'] }}</p>
        </div>
    </div>
    {{-- Delivered --}}
    <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-[#6E7A25] to-[#8b5cf6] rounded-2xl p-5 text-white shadow-lg relative overflow-hidden" style="animation-delay: 0.15s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Delivered') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['delivered'] }}</p>
        </div>
    </div>
    {{-- In Transit --}}
    <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden" style="animation-delay: 0.2s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('In Transit') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['enRoute'] }}</p>
        </div>
    </div>
    {{-- On-Time Rate --}}
    <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-[#033133] to-[#6E7A25] rounded-2xl p-5 text-white shadow-lg relative overflow-hidden" style="animation-delay: 0.25s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('On-Time Rate') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['onTimeRate'] }}%</p>
        </div>
    </div>
    {{-- Total Meals --}}
    <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden" style="animation-delay: 0.3s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Total Meals') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['totalMeals'] }}</p>
        </div>
    </div>
    {{-- Unassigned --}}
    <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden" style="animation-delay: 0.35s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Unassigned') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['unassigned'] }}</p>
        </div>
    </div>
    {{-- Active Drivers --}}
    <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-[#173327] to-[#033133] rounded-2xl p-5 text-white shadow-lg relative overflow-hidden" style="animation-delay: 0.4s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Active Drivers') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['activeDrivers'] }}</p>
        </div>
    </div>
    {{-- Failed --}}
    <div class="animate__animated animate__fadeInUp bg-gradient-to-br from-gray-700 to-gray-900 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden" style="animation-delay: 0.45s;">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p class="text-xs text-white/60 font-medium mb-1">{{ __('Failed/Cancelled') }}</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['failed'] }}</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-50 bg-gradient-to-r from-[#173327]/5 to-transparent flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 001 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Live Deliveries') }}</h3>
                    <p class="text-[10px] text-gray-400">{{ __('Real-time delivery tracking with meals') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if(count($availableDrivers) > 0)
                <button x-show="selectedOrderIds.length > 0" @click="bulkAssignOpen = true"
                    class="text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-3 py-2 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-text="'{{ __('Bulk Assign') }} (' + selectedOrderIds.length + ')'"></span>
                </button>
                @endif
                <button @click="openDriverManager()" class="text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-3 py-2 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('Manage Drivers') }}
                </button>
            </div>
        </div>

        {{-- Bulk Selection Info Bar --}}
        <div x-show="selectedOrderIds.length > 0" x-transition class="px-6 py-2 bg-[#F6F3E9] border-b border-[#d1cb9f] flex items-center justify-between">
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" @change="toggleAllDeliveries($event)" :checked="allDeliveriesSelected" class="w-4 h-4 rounded border-gray-300 text-[#6E7A25] focus:ring-[#6E7A25]">
                    <span class="text-xs font-bold text-[#6E7A25]">{{ __('Select All') }}</span>
                </label>
                <span class="text-xs text-gray-600" x-text="selectedOrderIds.length + ' {{ __('orders selected') }}'"></span>
                <button @click="clearSelection()" class="text-xs text-red-500 hover:underline">{{ __('Clear') }}</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-100">
                        <th class="px-4 py-3 w-10">
                            <input type="checkbox" @change="toggleAllDeliveries($event)" :checked="allDeliveriesSelected" class="w-4 h-4 rounded border-gray-300 text-[#6E7A25] focus:ring-[#6E7A25]">
                        </th>
                        <th class="px-4 py-3 font-medium">{{ __('Delivery') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Customer') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Meals') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Driver') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Time') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Status') }}</th>
                        <th class="px-4 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors" :class="selectedOrderIds.includes({{ $delivery['order_id'] }}) ? 'bg-[#F6F3E9]/40' : ''">
                        <td class="px-4 py-3.5">
                            @if(in_array($delivery['status'], ['pending', 'scheduled', 'assigned']))
                            <input type="checkbox" value="{{ $delivery['order_id'] }}" x-model="selectedOrderIds" class="w-4 h-4 rounded border-gray-300 text-[#6E7A25] focus:ring-[#6E7A25]">
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 001 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-gray-900">{{ $delivery['delivery_id'] }}</span>
                                    <p class="text-[10px] text-gray-400">{{ $delivery['order'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white text-[10px] font-bold flex-shrink-0">{{ strtoupper(substr($delivery['customer'], 0, 1)) }}</div>
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-900 truncate">{{ $delivery['customer'] }}</p>
                                    <p class="text-[10px] text-gray-400 truncate">{{ $delivery['zone'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="max-w-[200px]">
                                <p class="text-xs text-gray-700 truncate" title="{{ $delivery['meal_summary'] }}">{{ $delivery['meal_summary'] }}</p>
                                @if($delivery['meal_count'] > 0)
                                <span class="inline-flex items-center gap-1 mt-0.5">
                                    <span class="text-[9px] font-bold text-[#6E7A25] bg-[#6E7A25]/10 px-1.5 py-0.5 rounded-full">{{ $delivery['meal_count'] }} {{ __('meals') }}</span>
                                    @if($delivery['total_calories'] > 0)
                                    <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded-full">{{ $delivery['total_calories'] }} kcal</span>
                                    @endif
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            @if($delivery['driver'] === 'Unassigned')
                            <span class="inline-flex items-center gap-1 text-xs text-red-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Unassigned') }}
                            </span>
                            @else
                            <div class="flex items-center gap-1.5">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0">{{ strtoupper(substr($delivery['driver'], 0, 1)) }}</div>
                                <span class="text-xs text-gray-700">{{ $delivery['driver'] }}</span>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <p class="text-xs text-gray-500">{{ $delivery['time'] }}</p>
                            <p class="text-[10px] {{ $delivery['eta'] === 'On time' ? 'text-green-600' : ($delivery['eta'] === 'Pending' ? 'text-gray-400' : 'text-amber-600') }}">{{ $delivery['eta'] === 'On time' ? __('On time') : ($delivery['eta'] === 'Pending' ? __('Pending') : $delivery['eta']) }}</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$delivery['status']] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ $statusLabels[$delivery['status']] ?? __(ucfirst($delivery['status'])) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center justify-end gap-1">
                                @if(in_array($delivery['status'], ['pending', 'scheduled', 'assigned']))
                                {{-- Assign Driver icon button --}}
                                <form action="{{ route('admin.deliveries.assign-driver', $delivery['id']) }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    @if(count($availableDrivers) > 0)
                                    <select name="driver_id" class="text-[10px] border border-gray-200 rounded-lg px-1.5 py-1 bg-gray-50 outline-none max-w-[100px]" onchange="if(this.value)this.form.submit()">
                                        <option value="">{{ __('Assign...') }}</option>
                                        @foreach($availableDrivers as $driver)
                                        <option value="{{ $driver['id'] }}">{{ $driver['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @else
                                    <span class="text-[10px] text-amber-600 bg-amber-50 border border-amber-100 rounded-lg px-2 py-1">{{ __('No drivers') }}</span>
                                    @endif
                                </form>
                                @endif
                                @if(in_array($delivery['status'], ['assigned', 'preparing', 'en_route', 'out_for_delivery']))
                                {{-- Update Status icon button --}}
                                <form action="{{ route('admin.deliveries.update-status', $delivery['id']) }}" method="POST" class="inline-flex items-center">
                                    @csrf
                                    <select name="status" class="text-[10px] border border-gray-200 rounded-lg px-1.5 py-1 bg-gray-50 outline-none max-w-[100px]" onchange="if(this.value)this.form.submit()">
                                        <option value="">{{ __('Status...') }}</option>
                                        <option value="preparing">{{ __('Preparing') }}</option>
                                        <option value="out_for_delivery">{{ __('Out for Delivery') }}</option>
                                        <option value="delivered">{{ __('Delivered') }}</option>
                                        <option value="failed">{{ __('Failed') }}</option>
                                    </select>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

{{-- Bulk Assign Driver Modal --}}
<div x-show="bulkAssignOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" aria-labelledby="bulk-assign-title" role="dialog" aria-modal="true">
    <div x-show="bulkAssignOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="bulkAssignOpen = false" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    <div x-show="bulkAssignOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 id="bulk-assign-title" class="text-lg font-bold text-gray-900">{{ __('Bulk Assign Driver') }}</h3>
            <button @click="bulkAssignOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-sm text-gray-500 mb-4" x-text="selectedOrderIds.length + ' {{ __('orders will be assigned to the selected driver.') }}'"></p>
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Select Driver') }} <span class="text-red-500">*</span></label>
                <select x-model="bulkDriverId" class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                    <option value="">{{ __('Choose a driver...') }}</option>
                    @foreach($allDrivers as $driver)
                    <option value="{{ $driver['id'] }}" @if(!$driver['is_active']) disabled @endif>{{ $driver['name'] }}@if(!$driver['is_active']) (Inactive)@endif</option>
                    @endforeach
                </select>
            </div>
            <div x-show="bulkAssignError" x-text="bulkAssignError" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
            <div x-show="bulkAssignSuccess" x-text="bulkAssignSuccess" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button @click="bulkAssignOpen = false" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition-colors">{{ __('Cancel') }}</button>
                <button @click="submitBulkAssign()" :disabled="bulkAssigning || !bulkDriverId" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-sm font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-60 disabled:cursor-not-allowed flex items-center gap-2">
                    <svg x-show="bulkAssigning" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    <span x-text="bulkAssigning ? '{{ __('Assigning...') }}' : '{{ __('Assign Driver') }}'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Driver Management Modal --}}
<div x-show="driverModalOpen" x-cloak class="fixed inset-0 z-50" aria-labelledby="driver-modal-title" role="dialog" aria-modal="true">
    <div x-show="driverModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="closeDriverModal()"></div>

    <div x-show="driverModalOpen"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full rtl:-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full rtl:-translate-x-full"
         class="absolute inset-y-0 right-0 rtl:right-auto rtl:left-0 w-full sm:w-[32rem] lg:w-[36rem] bg-white shadow-2xl"
         style="max-width: 100vw;">
        <div class="h-full flex flex-col">
            {{-- Gradient Header --}}
            <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] px-6 py-5 flex-shrink-0 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <h3 id="driver-modal-title" class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ __('Manage Drivers') }}
                    </h3>
                    <button @click="closeDriverModal()" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="p-3 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white text-center shadow-md">
                        <p class="text-lg font-bold" x-text="drivers.length"></p>
                        <p class="text-[10px] text-white/70">{{ __('Total') }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-gray-100 text-center">
                        <p class="text-lg font-bold text-green-600" x-text="drivers.filter(d => d.status === 'active').length"></p>
                        <p class="text-[10px] text-gray-400">{{ __('Active') }}</p>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-gray-100 text-center">
                        <p class="text-lg font-bold text-gray-500" x-text="drivers.filter(d => d.status !== 'active').length"></p>
                        <p class="text-[10px] text-gray-400">{{ __('Inactive') }}</p>
                    </div>
                </div>

                {{-- Add / Edit Form --}}
                <form class="space-y-4 mb-6" @submit.prevent="saveDriver">
                    <input type="hidden" x-model="driverForm.id">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('First Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" x-model="driverForm.first_name" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Last Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" x-model="driverForm.last_name" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Email') }} <span class="text-red-500">*</span></label>
                            <input type="email" x-model="driverForm.email" :required="!driverForm.id" :disabled="!!driverForm.id" :class="driverForm.id ? 'bg-gray-50 text-gray-500' : ''" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none disabled:cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Phone') }} <span class="text-red-500">*</span></label>
                            <input type="tel" x-model="driverForm.phone" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div x-show="!driverForm.id" class="col-span-2">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-gray-700">{{ __('Password') }}</label>
                                <button type="button" @click="driverForm.password = Math.random().toString(36).slice(2) + Math.random().toString(36).slice(2, 4).toUpperCase()" class="text-[10px] font-bold text-[#6E7A25] hover:underline">{{ __('Generate') }}</button>
                            </div>
                            <input type="text" x-model="driverForm.password" placeholder="{{ __('Leave empty to auto-generate') }}" minlength="6" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Location') }}</label>
                            <input type="text" x-model="driverForm.location" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Address') }}</label>
                            <input type="text" x-model="driverForm.address" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div class="col-span-2 flex items-center gap-2 p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <input type="checkbox" id="driverActive" x-model="driverForm.is_active" class="w-4 h-4 rounded border-gray-300 text-[#6E7A25] focus:ring-[#6E7A25]">
                            <label for="driverActive" class="text-xs font-bold text-gray-700">{{ __('Active driver account') }}</label>
                        </div>
                    </div>
                    <div x-show="driverError" x-text="driverError" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                    <div x-show="driverSuccess" x-text="driverSuccess" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>

                    {{-- Created Credentials --}}
                    <div x-show="driverCredentials" x-cloak class="bg-[#F6F3E9] border border-[#d1cb9f] rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2v4.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 111.414-1.414L13 13.586V9a2 2 0 012-2zM5 9a2 2 0 012-2h2a2 2 0 012 2v.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 9.586V9a2 2 0 00-2-2H7a2 2 0 00-2 2v6a2 2 0 002 2h3a1 1 0 100-2H7V9z"/></svg>
                            <p class="text-xs font-bold text-[#6E7A25]">{{ __('Driver Login Credentials') }}</p>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-[#e8e4d0]">
                                <span class="text-[10px] text-gray-500">{{ __('Email') }}</span>
                                <span class="text-xs font-mono font-semibold text-gray-800" x-text="driverCredentials?.email"></span>
                            </div>
                            <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-[#e8e4d0]">
                                <span class="text-[10px] text-gray-500">{{ __('Password') }}</span>
                                <span class="text-xs font-mono font-semibold text-gray-800" x-text="driverCredentials?.password"></span>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-2">{{ __('These credentials have also been emailed to the driver.') }}</p>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="resetDriverForm()" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">{{ __('Reset') }}</button>
                        <button type="submit" :disabled="driverSaving" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-60">
                            <span x-show="!driverSaving" x-text="driverForm.id ? '{{ __('Update Driver') }}' : '{{ __('Add Driver') }}'"></span>
                            <span x-show="driverSaving">{{ __('Saving...') }}</span>
                        </button>
                    </div>
                </form>

                {{-- Search --}}
                <div class="mb-4">
                    <div class="flex items-center bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" x-model="driverSearch" placeholder="{{ __('Search drivers...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400">
                    </div>
                </div>

                {{-- Driver List --}}
                <h4 class="text-sm font-bold text-gray-900 mb-3">{{ __('Driver List') }}</h4>
                <div class="space-y-3">
                    <template x-for="driver in filteredDrivers" :key="driver.id">
                        <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:shadow-sm transition-all bg-white">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white font-bold text-xs flex-shrink-0" x-text="(driver.name || 'D').charAt(0)"></div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-900 truncate" x-text="driver.name"></p>
                                    <p class="text-[10px] text-gray-400 truncate" x-text="driver.email + ' · ' + driver.phone"></p>
                                    <p class="text-[10px] text-gray-400 truncate" x-show="driver.location || driver.address" x-text="(driver.location ? driver.location + (driver.address ? ' · ' : '') : '') + (driver.address || '')"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="driver.status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'" x-text="driver.status === 'active' ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"></span>
                                <button @click="editDriver(driver)" class="p-1.5 text-[#6E7A25] hover:bg-[#6E7A25]/10 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.43-9.121a2.948 2.948 0 00-4.172 0L11.879 5.88a2.948 2.948 0 000 4.172l5.586 5.586a2.948 2.948 0 004.172 0l.586-.586a2.948 2.948 0 000-4.172l-5.586-5.586z"/></svg>
                                </button>
                                <button @click="toggleDriverStatus(driver)" class="p-1.5 rounded-lg transition-colors" :class="driver.status === 'active' ? 'text-amber-600 hover:bg-amber-50' : 'text-green-600 hover:bg-green-50'" :title="driver.status === 'active' ? '{{ __('Deactivate') }}' : '{{ __('Activate') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="filteredDrivers.length === 0 && !driverLoading" class="p-8 text-center text-gray-400 text-sm">
                        {{ __('No drivers found matching your search.') }}
                    </div>
                    <div x-show="driverLoading" class="space-y-3 animate-pulse">
                        <template x-for="i in 3">
                            <div class="h-14 bg-gray-100 rounded-xl"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
@php
$assignableOrderIds = collect($deliveries)->whereIn('status', ['pending', 'scheduled', 'assigned'])->pluck('order_id')->values();
@endphp
<script>
    function deliveryManager() {
        return {
            driverModalOpen: false,
            driverLoading: false,
            driverSaving: false,
            driverError: '',
            driverSuccess: '',
            driverSearch: '',
            drivers: [],
            driverCredentials: null,
            // Bulk assign state
            selectedOrderIds: [],
            bulkAssignOpen: false,
            bulkDriverId: '',
            bulkAssigning: false,
            bulkAssignError: '',
            bulkAssignSuccess: '',
            allAssignableOrderIds: @json($assignableOrderIds),
            driverForm: {
                id: null,
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                password: '',
                location: '',
                address: '',
                is_active: true,
            },

            get filteredDrivers() {
                const term = this.driverSearch.toLowerCase().trim();
                if (!term) return this.drivers;
                return this.drivers.filter(d =>
                    (d.name || '').toLowerCase().includes(term) ||
                    (d.email || '').toLowerCase().includes(term) ||
                    (d.phone || '').toLowerCase().includes(term) ||
                    (d.location || '').toLowerCase().includes(term)
                );
            },

            get allDeliveriesSelected() {
                return this.allAssignableOrderIds.length > 0 &&
                    this.allAssignableOrderIds.every(id => this.selectedOrderIds.includes(id));
            },

            toggleAllDeliveries(e) {
                if (e.target.checked) {
                    this.selectedOrderIds = [...this.allAssignableOrderIds];
                } else {
                    this.selectedOrderIds = [];
                }
            },
            clearSelection() {
                this.selectedOrderIds = [];
            },

            async submitBulkAssign() {
                if (!this.bulkDriverId || this.selectedOrderIds.length === 0) return;
                this.bulkAssigning = true;
                this.bulkAssignError = '';
                this.bulkAssignSuccess = '';

                try {
                    const res = await fetch('{{ route('admin.deliveries.bulk-assign-driver') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            driver_id: parseInt(this.bulkDriverId),
                            order_ids: this.selectedOrderIds.map(id => parseInt(id)),
                        }),
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.bulkAssignSuccess = data.message || 'Driver assigned successfully.';
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        this.bulkAssignError = data.message || 'Failed to assign driver.';
                    }
                } catch (err) {
                    this.bulkAssignError = 'Network error. Please try again.';
                } finally {
                    this.bulkAssigning = false;
                }
            },

            openDriverManager() {
                this.driverModalOpen = true;
                this.driverSearch = '';
                this.loadDrivers();
            },
            closeDriverModal() {
                this.driverModalOpen = false;
                this.resetDriverForm();
                this.driverError = '';
                this.driverSuccess = '';
                this.driverCredentials = null;
            },
            resetDriverForm() {
                this.driverForm = { id: null, first_name: '', last_name: '', email: '', phone: '', password: '', location: '', address: '', is_active: true };
                this.driverError = '';
                this.driverSuccess = '';
                this.driverCredentials = null;
            },
            editDriver(driver) {
                this.driverForm = { ...driver, password: '', is_active: driver.status === 'active' };
            },
            async loadDrivers() {
                this.driverLoading = true;
                try {
                    const res = await fetch('{{ route('admin.drivers') }}', {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.drivers = data.drivers || [];
                    }
                } catch (e) {
                    this.driverError = '{{ __('Failed to load drivers.') }}';
                } finally {
                    this.driverLoading = false;
                }
            },
            async saveDriver() {
                this.driverSaving = true;
                this.driverError = '';
                this.driverSuccess = '';
                const isEdit = !!this.driverForm.id;
                const url = isEdit ? '{{ url('admin/drivers') }}/' + this.driverForm.id : '{{ route('admin.drivers.store') }}';
                const formData = new FormData();

                const fields = isEdit
                    ? ['id', 'first_name', 'last_name', 'phone', 'location', 'address', 'is_active']
                    : ['first_name', 'last_name', 'email', 'phone', 'password', 'location', 'address'];

                fields.forEach(key => {
                    const value = this.driverForm[key];
                    if (value !== null && value !== undefined) formData.append(key, value);
                });

                if (isEdit) {
                    formData.append('_method', 'PUT');
                }
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                    const data = await res.json();
                    if (data.success) {
                        await this.loadDrivers();
                        this.resetDriverForm();
                        this.driverSuccess = data.message || '{{ __('Driver saved successfully.') }}';
                        if (data.credentials) {
                            this.driverCredentials = data.credentials;
                        }
                    } else {
                        this.driverError = data.message || '{{ __('Failed to save driver.') }}';
                    }
                } catch (e) {
                    this.driverError = '{{ __('Network error. Please try again.') }}';
                } finally {
                    this.driverSaving = false;
                }
            },
            async toggleDriverStatus(driver) {
                const action = driver.status === 'active' ? '{{ __('deactivate') }}' : '{{ __('activate') }}';
                if (!confirm(`{{ __('Are you sure you want to') }} ${action} ${driver.name}?`)) return;
                try {
                    const formData = new FormData();
                    formData.append('is_active', driver.status !== 'active');
                    formData.append('_method', 'PUT');
                    const res = await fetch('{{ url('admin/drivers') }}/' + driver.id, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const data = await res.json();
                    if (data.success) {
                        await this.loadDrivers();
                        this.driverSuccess = data.message || '{{ __('Driver status updated.') }}';
                    } else {
                        this.driverError = data.message || '{{ __('Failed to update driver status.') }}';
                    }
                } catch (e) {
                    this.driverError = '{{ __('Network error. Please try again.') }}';
                }
            },
        };
    }
</script>
@endpush

@endsection
