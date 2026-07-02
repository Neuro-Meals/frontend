@extends('layouts.admin')

@section('title', 'Deliveries - Nutrio Meals')
@section('page_title', 'Deliveries')

@section('content')
@php
    $statusColors = [
        'delivered' => 'bg-green-50 text-green-700 border-green-200',
        'en_route' => 'bg-blue-50 text-blue-700 border-blue-200',
        'preparing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'scheduled' => 'bg-gray-50 text-gray-600 border-gray-200',
    ];
    $statusLabels = [
        'delivered' => 'Delivered',
        'en_route' => 'En Route',
        'preparing' => 'Preparing',
        'scheduled' => 'Scheduled',
    ];
@endphp

{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">Total Today</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">Delivered</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">In Transit</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['enRoute'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">On-Time Rate</p>
        <p class="text-2xl font-bold text-[#6E7A25]">{{ $stats['onTimeRate'] }}%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Zones Summary --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-1">Delivery Zones</h3>
        <p class="text-xs text-gray-400 mb-5">Today's performance by zone</p>
        <div class="space-y-4">
            @foreach($zones as $zone)
            @php $pct = round($zone['completed'] / $zone['orders'] * 100); @endphp
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-xs font-semibold text-gray-900">{{ $zone['name'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $zone['drivers'] }} drivers · {{ $zone['completed'] }}/{{ $zone['orders'] }} completed</p>
                    </div>
                    <span class="text-xs font-bold {{ $pct >= 80 ? 'text-green-600' : 'text-amber-600' }}">{{ $pct }}%</span>
                </div>
                <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                    <div class="h-full rounded-full {{ $pct >= 80 ? 'bg-green-500' : 'bg-amber-500' }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Deliveries Table --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50">
            <h3 class="text-sm font-bold text-gray-900">Live Deliveries</h3>
            <p class="text-xs text-gray-400 mt-0.5">Real-time delivery tracking</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">Delivery ID</th>
                        <th class="px-6 py-3 font-medium">Customer</th>
                        <th class="px-6 py-3 font-medium">Zone</th>
                        <th class="px-6 py-3 font-medium">Driver</th>
                        <th class="px-6 py-3 font-medium">ETA</th>
                        <th class="px-6 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                        <td class="px-6 py-3.5">
                            <span class="text-xs font-bold text-gray-900">{{ $delivery['id'] }}</span>
                            <p class="text-[10px] text-gray-400">{{ $delivery['order'] }}</p>
                        </td>
                        <td class="px-6 py-3.5 text-xs font-medium text-gray-700">{{ $delivery['customer'] }}</td>
                        <td class="px-6 py-3.5 text-xs text-gray-500">{{ $delivery['zone'] }}</td>
                        <td class="px-6 py-3.5">
                            <span class="text-xs {{ $delivery['driver'] === 'Unassigned' ? 'text-red-500' : 'text-gray-700' }}">{{ $delivery['driver'] }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <p class="text-xs text-gray-500">{{ $delivery['time'] }}</p>
                            <p class="text-[10px] {{ $delivery['eta'] === 'On time' ? 'text-green-600' : ($delivery['eta'] === 'Pending' ? 'text-gray-400' : 'text-amber-600') }}">{{ $delivery['eta'] }}</p>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$delivery['status']] }}">
                                {{ $statusLabels[$delivery['status']] }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
