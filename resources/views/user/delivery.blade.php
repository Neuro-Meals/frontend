@extends('layouts.user')

@section('title', __('Delivery') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Delivery Tracking'))

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20">
        <span class="text-[10px] font-medium text-white/60">{{ __('Total Deliveries') }}</span>
        <div class="text-2xl font-bold mt-1">{{ $stats['totalDeliveries'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('On-Time Rate') }}</span>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $stats['onTimeRate'] }}%</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Avg Delivery Time') }}</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['avgDeliveryTime'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Preferred Slot') }}</span>
        <div class="text-sm font-bold text-gray-900 mt-1">{{ $stats['preferredSlot'] }}</div>
    </div>
</div>

{{-- Upcoming Deliveries --}}
<div class="mb-6">
    <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('Upcoming') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Deliveries') }}</span></h3>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($upcoming as $delivery)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition-all">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $delivery['id'] }}</span>
                    <h4 class="text-sm font-bold text-gray-900 mt-1">{{ $delivery['date'] }}</h4>
                    <p class="text-xs text-gray-500">{{ $delivery['time'] }}</p>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#949B50]/10 text-[#949B50]">{{ __('Scheduled') }}</span>
            </div>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-gray-400">{{ __('Zone') }}</span>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $delivery['zone'] }}</p>
                </div>
                <div>
                    <span class="text-gray-400">{{ __('Driver') }}</span>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $delivery['driver'] }}</p>
                </div>
                <div>
                    <span class="text-gray-400">{{ __('Order') }}</span>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $delivery['order'] }}</p>
                </div>
                <div>
                    <span class="text-gray-400">{{ __('Meals') }}</span>
                    <p class="font-semibold text-gray-900 mt-0.5">{{ $delivery['meals'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Delivery History --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">{{ __('Delivery') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('History') }}</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">{{ __('Delivery ID') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Order') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Date') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Time') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Driver') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('ETA') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $delivery)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $delivery['id'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $delivery['order'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $delivery['date'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $delivery['time'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $delivery['driver'] }}</td>
                    <td class="px-5 py-3 text-xs {{ $delivery['eta'] === 'On time' ? 'text-green-600 font-semibold' : 'text-[#949B50] font-semibold' }}">{{ $delivery['eta'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-green-700">{{ __('Delivered') }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
