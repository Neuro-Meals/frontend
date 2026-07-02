@extends('layouts.admin')

@section('title', __('Payments') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Payments'))

@section('content')
@php
    $statusColors = [
        'completed' => 'bg-green-50 text-green-700 border-green-200',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
        'failed' => 'bg-red-50 text-red-600 border-red-200',
        'refunded' => 'bg-purple-50 text-purple-700 border-purple-200',
    ];
    $methodIcons = [
        'Credit Card' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'Apple Pay' => 'M12 4v16m8-8H4',
        'Mada' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'Bank Transfer' => 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9',
    ];
@endphp

{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-bold text-green-600">+15.5%</span>
        </div>
        <p class="text-xs text-gray-400 mb-1">{{ __('Total Revenue') }}</p>
        <p class="text-2xl font-bold text-gray-900">SAR {{ number_format($stats['totalRevenue']) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mb-1">{{ __('Success Rate') }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['successRate'] }}%</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mb-1">{{ __('Pending') }}</p>
        <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
        </div>
        <p class="text-xs text-gray-400 mb-1">{{ __('Failed / Refunded') }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['failed'] }} / SAR {{ $stats['refunded'] }}</p>
    </div>
</div>

{{-- Filter Bar --}}
<div class="bg-white rounded-2xl border border-gray-100 p-4 mb-6 shadow-sm flex flex-wrap items-center gap-3">
    <div class="flex items-center bg-gray-50 rounded-lg px-3 py-2 border border-gray-100 flex-1 min-w-[200px]">
        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" placeholder="{{ __('Search by Payment ID...') }}" class="bg-transparent text-sm outline-none flex-1 text-gray-600 placeholder-gray-400">
    </div>
    <select class="text-sm border border-gray-100 rounded-lg px-3 py-2 bg-gray-50 text-gray-600 outline-none cursor-pointer">
        <option>{{ __('All Methods') }}</option>
        <option>{{ __('Credit Card') }}</option>
        <option>{{ __('Apple Pay') }}</option>
        <option>{{ __('Mada') }}</option>
        <option>{{ __('Bank Transfer') }}</option>
    </select>
    <select class="text-sm border border-gray-100 rounded-lg px-3 py-2 bg-gray-50 text-gray-600 outline-none cursor-pointer">
        <option>{{ __('All Status') }}</option>
        <option>{{ __('Completed') }}</option>
        <option>{{ __('Pending') }}</option>
        <option>{{ __('Failed') }}</option>
        <option>{{ __('Refunded') }}</option>
    </select>
    <button class="ml-auto px-4 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        {{ __('Export') }}
    </button>
</div>

{{-- Payments Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">{{ __('Payment ID') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Order') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Customer') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Method') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Amount') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Date') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-6 py-3.5">
                        <span class="text-xs font-bold text-gray-900">{{ $payment['id'] }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-500">{{ $payment['order'] }}</td>
                    <td class="px-6 py-3.5 text-xs font-medium text-gray-700">{{ $payment['customer'] }}</td>
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $methodIcons[$payment['method']] ?? $methodIcons['Credit Card'] }}"/></svg>
                            <span class="text-xs text-gray-600">{{ $payment['method'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="text-xs font-bold text-gray-900">SAR {{ $payment['amount'] }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-400">{{ $payment['date'] }}</td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$payment['status']] }}">
                            {{ __(ucfirst($payment['status'])) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-50 flex items-center justify-between">
        <p class="text-xs text-gray-400">{{ __('Showing 1-7 of') }} 901 {{ __('payments') }}</p>
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
