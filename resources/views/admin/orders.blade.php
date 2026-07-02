@extends('layouts.admin')

@section('title', __('Orders') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Orders'))

@section('content')
@php
    $statusColors = [
        'delivered' => 'bg-green-50 text-green-700 border-green-200',
        'en_route' => 'bg-blue-50 text-blue-700 border-blue-200',
        'preparing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
    ];
    $statusLabels = [
        'delivered' => __('Delivered'),
        'en_route' => __('En Route'),
        'preparing' => __('Preparing'),
        'pending' => __('Pending'),
        'cancelled' => __('Cancelled'),
    ];
@endphp

{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Total Orders') }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __("Today's Orders") }}</p>
        <p class="text-2xl font-bold text-[#6E7A25]">{{ $stats['today'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Pending') }}</p>
        <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Total Revenue') }}</p>
        <p class="text-2xl font-bold text-gray-900">SAR {{ number_format($stats['revenue']) }}</p>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 shadow-sm flex flex-wrap items-center gap-3">
    <div class="flex items-center bg-gray-50 rounded-lg px-3 py-2 border border-gray-100 flex-1 min-w-[200px]">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="{{ __('Search by Order ID...') }}" class="bg-transparent text-sm outline-none flex-1 text-gray-600 placeholder-gray-400">
    </div>
    <select class="text-sm border border-gray-100 rounded-lg px-3 py-2 bg-gray-50 text-gray-600 outline-none cursor-pointer">
        <option>{{ __('All Status') }}</option>
        <option>{{ __('Delivered') }}</option>
        <option>{{ __('En Route') }}</option>
        <option>{{ __('Preparing') }}</option>
        <option>{{ __('Pending') }}</option>
        <option>{{ __('Cancelled') }}</option>
    </select>
    <input type="date" class="text-sm border border-gray-100 rounded-lg px-3 py-2 bg-gray-50 text-gray-600 outline-none cursor-pointer">
    <button class="ml-auto px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        {{ __('Create Order') }}
    </button>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">{{ __('Order ID') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Customer') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Plan') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Amount') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Delivery Window') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Date') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Status') }}</th>
                    <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-6 py-3.5">
                        <span class="text-xs font-bold text-gray-900">{{ $order['id'] }}</span>
                    </td>
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0">
                                {{ strtoupper(substr($order['customer'], 0, 1)) }}
                            </div>
                            <span class="text-xs font-medium text-gray-700">{{ $order['customer'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-500">{{ $order['plan'] }}</td>
                    <td class="px-6 py-3.5">
                        <span class="text-xs font-bold text-gray-900">SAR {{ $order['amount'] }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-500">{{ $order['delivery'] }}</td>
                    <td class="px-6 py-3.5 text-xs text-gray-400">{{ date('M d, Y', strtotime($order['date'])) }}</td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$order['status']] }}">
                            {{ $statusLabels[$order['status']] }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-right">
                        <button class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-50 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ __('Showing 1-8 of') }} {{ number_format($stats['total']) }} {{ __('orders') }}</p>
        <div class="flex items-center gap-1">
            <button class="px-3 py-1.5 text-xs font-medium text-gray-400 rounded-lg hover:bg-gray-50 transition-colors">{{ __('Previous') }}</button>
            <button class="px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg">1</button>
            <button class="px-3 py-1.5 text-xs font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">2</button>
            <button class="px-3 py-1.5 text-xs font-medium text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">3</button>
            <button class="px-3 py-1.5 text-xs font-medium text-gray-400 rounded-lg hover:bg-gray-50 transition-colors">{{ __('Next') }}</button>
        </div>
    </div>
</div>
@endsection
