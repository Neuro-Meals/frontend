@extends('layouts.user')

@section('title', __('Orders') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('My Orders'))

@section('content')

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

{{-- Active Subscription Banner --}}
@if($subscriptionInfo)
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-2xl p-5 sm:p-6 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0 backdrop-blur-sm">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <p class="text-base font-bold">{{ $subscriptionInfo['plan_name'] }}</p>
                <p class="text-xs text-white/70 mt-0.5">
                    {{ $subscriptionInfo['meals_per_day'] }} {{ __('meals/day') }} ·
                    {{ $subscriptionInfo['start_date'] }} → {{ $subscriptionInfo['end_date'] }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if($subscriptionInfo['status'] === 'paused')
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-yellow-400/20 text-yellow-200 text-xs font-bold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('Paused') }}
            </span>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/15 text-white text-xs font-bold backdrop-blur-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('Active') }}
            </span>
            @endif
        </div>
    </div>

    {{-- Meal Progress --}}
    <div class="relative z-10 mt-5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-white/70">{{ __('Meal Progress') }}</span>
            <span class="text-xs font-bold">{{ $subscriptionInfo['meals_consumed'] }} / {{ $subscriptionInfo['total_meals'] }} {{ __('meals') }}</span>
        </div>
        <div class="h-2.5 bg-white/10 rounded-full overflow-hidden">
            <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $subscriptionInfo['progress'] }}%"></div>
        </div>
        <div class="flex items-center justify-between mt-2">
            <span class="text-[10px] text-white/50">{{ $subscriptionInfo['progress'] }}% {{ __('consumed') }}</span>
            <span class="text-[10px] text-white/50">{{ $subscriptionInfo['remaining'] }} {{ __('meals remaining') }}</span>
        </div>
    </div>
</div>
@else
<div class="bg-white border border-gray-100 rounded-2xl p-5 sm:p-6 shadow-sm mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-900">{{ __('No active subscription') }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ __('Subscribe to a plan to get your meals delivered automatically.') }}</p>
        </div>
    </div>
    <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold hover:shadow-lg transition-all w-fit">
        {{ __('Subscribe to a Plan') }}
    </a>
</div>
@endif

{{-- Info Banner --}}
@if($subscriptionInfo)
<div class="bg-[#6E7A25]/5 border border-[#6E7A25]/10 rounded-xl p-4 mb-6 flex items-start gap-3">
    <div class="w-8 h-8 rounded-lg bg-[#6E7A25]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
        <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div>
        <p class="text-xs text-gray-700 leading-relaxed">{{ __('Your meals are automatically prepared and delivered based on your subscription plan. No need to create orders manually — just enjoy your food!') }}</p>
    </div>
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <span class="text-[10px] font-medium text-white/60">{{ __('Total Orders') }}</span>
            <div class="text-2xl font-bold mt-1">{{ $stats['total'] }}</div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Delivered') }}</span>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $stats['delivered'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Cancelled') }}</span>
        <div class="text-2xl font-bold text-red-500 mt-1">{{ $stats['cancelled'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-col justify-between">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Total Spent') }}</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">SAR {{ number_format($stats['totalSpent']) }}</div>
    </div>
</div>

{{-- Orders Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-900">{{ __('Order') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('History') }}</span></h3>
        @if(!empty($orders))
        <span class="text-[10px] text-gray-400">{{ count($orders) }} {{ __('order(s)') }}</span>
        @endif
    </div>
    @if(!empty($orders))
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">{{ __('Order ID') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Plan') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Meals') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Amount') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Date') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3.5 text-xs font-bold text-gray-900">{{ $order['id'] }}</td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">{{ $order['plan'] }}</td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">{{ $order['meals'] }}</td>
                    <td class="px-5 py-3.5 text-xs font-bold text-gray-900">SAR {{ number_format($order['amount']) }}</td>
                    <td class="px-5 py-3.5 text-xs text-gray-500">{{ date('M d, Y', strtotime($order['date'])) }}</td>
                    <td class="px-5 py-3.5">
                        @if($order['status'] === 'delivered')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-green-50 text-green-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Delivered') }}
                        </span>
                        @elseif($order['status'] === 'cancelled')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-red-50 text-red-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Cancelled') }}
                        </span>
                        @elseif($order['status'] === 'preparing')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Preparing') }}
                        </span>
                        @elseif($order['status'] === 'out_for_delivery')
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-orange-50 text-orange-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Out for Delivery') }}
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-yellow-50 text-yellow-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ ucfirst($order['status']) }}
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-10 text-center">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">{{ __('No orders yet') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $subscriptionInfo ? __('Your meal deliveries will appear here automatically.') : __('Subscribe to a plan to get started.') }}</p>
        @if(!$subscriptionInfo)
        <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center mt-4 px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold hover:shadow-lg transition-all">
            {{ __('Subscribe to a Plan') }}
        </a>
        @endif
    </div>
    @endif
</div>

@endsection
