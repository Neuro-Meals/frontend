@extends('layouts.user')

@section('title', __('Delivery') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Delivery Tracking'))

@section('content')

{{-- Today Banner --}}
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-2xl p-5 sm:p-6 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0 backdrop-blur-sm">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
            </div>
            <div>
                <p class="text-lg font-bold">{{ __('Today\'s Deliveries') }}</p>
                <p class="text-xs text-white/70 mt-0.5">{{ date('l, M d, Y') }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-3xl font-bold">{{ $stats['totalToday'] }}</p>
            <p class="text-[10px] text-white/60">{{ __('deliveries') }}</p>
        </div>
    </div>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <span class="text-[10px] font-medium text-white/60">{{ __('Total Today') }}</span>
            <div class="text-2xl font-bold mt-1">{{ $stats['totalToday'] }}</div>
            <div class="text-[10px] text-white/50 mt-1">{{ $stats['totalMealsToday'] }} {{ __('meals') }}</div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Delivered') }}</span>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $stats['deliveredToday'] }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ $stats['completionRate'] }}% {{ __('complete') }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('In Transit') }}</span>
        <div class="text-2xl font-bold text-orange-500 mt-1">{{ $stats['inTransitToday'] }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ __('on the way') }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Pending') }}</span>
        <div class="text-2xl font-bold text-yellow-500 mt-1">{{ $stats['pendingToday'] }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ __('awaiting') }}</div>
    </div>
</div>

{{-- Deliveries with Stepper --}}
<div class="space-y-4">
    @if(!empty($todayDeliveries))
        @foreach($todayDeliveries as $delivery)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-all">
            {{-- Delivery Header --}}
            <div class="px-4 sm:px-5 py-4 border-b border-gray-50 flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white flex-shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="text-sm font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                            <span class="text-[10px] text-gray-400">{{ $delivery['id'] }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-0.5 text-[10px] text-gray-500 flex-wrap">
                            <span class="font-medium">{{ $delivery['scheduled_time'] }}</span>
                            <span class="text-gray-300">·</span>
                            <span>{{ $delivery['meal_count'] }} {{ __('meals') }}</span>
                            @if($delivery['driver_name'])
                            <span class="text-gray-300">·</span>
                            <span class="text-[#6E7A25] font-medium">{{ $delivery['driver_name'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- Status badge --}}
                <div>
                    @if($delivery['status'] === 'delivered')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Delivered') }}
                    </span>
                    @elseif($delivery['status'] === 'out_for_delivery')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-50 text-orange-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('On the way') }}
                    </span>
                    @elseif($delivery['status'] === 'picked_up')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        {{ __('Picked up') }}
                    </span>
                    @elseif($delivery['status'] === 'assigned')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('Driver assigned') }}
                    </span>
                    @elseif($delivery['status'] === 'failed')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        {{ __('Failed') }}
                    </span>
                    @elseif($delivery['status'] === 'cancelled')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-50 text-red-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        {{ __('Cancelled') }}
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-700">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ __('Pending') }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Dot Stepper --}}
            @if($delivery['status'] !== 'cancelled' && $delivery['status'] !== 'failed')
            <div class="px-4 sm:px-5 py-6 bg-gray-50/30">
                <div class="flex items-center justify-between relative">
                    {{-- Connecting line --}}
                    <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200 mx-6"></div>
                    <div class="absolute top-5 left-0 h-0.5 bg-gradient-to-r from-[#173327] to-[#6E7A25] mx-6 transition-all duration-1000" style="width: calc({{ ($delivery['current_step'] - 1) / 4 * 100 }}% - 3rem);"></div>

                    @foreach($delivery['stepper'] as $step)
                    <div class="flex flex-col items-center gap-2 relative z-10 flex-1">
                        {{-- Dot --}}
                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-500 {{ $step['completed'] ? 'bg-gradient-to-br from-[#173327] to-[#6E7A25] text-white shadow-lg shadow-[#6E7A25]/30' : ($step['active'] ? 'bg-white border-2 border-[#6E7A25] text-[#6E7A25] shadow-md' : 'bg-white border-2 border-gray-200 text-gray-300') }}">
                            @if($step['completed'] && !$step['active'])
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @elseif($step['icon'] === 'clipboard')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            @elseif($step['icon'] === 'user')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @elseif($step['icon'] === 'bag')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            @elseif($step['icon'] === 'truck')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                            @elseif($step['icon'] === 'check')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </div>
                        {{-- Label --}}
                        <div class="text-center">
                            <p class="text-[10px] font-bold {{ $step['completed'] || $step['active'] ? 'text-gray-900' : 'text-gray-400' }}">{{ __($step['label']) }}</p>
                            @if($step['time'])
                            <p class="text-[9px] {{ $step['completed'] ? 'text-[#6E7A25] font-medium' : 'text-gray-400' }} mt-0.5">{{ $step['time'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Delivery Details --}}
            <div class="px-4 sm:px-5 py-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                {{-- Address --}}
                <div class="flex items-start gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#6E7A25]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400 font-medium">{{ __('Address') }}</p>
                        <p class="text-xs text-gray-700 mt-0.5 break-words">{{ $delivery['address'] ?: __('N/A') }}</p>
                    </div>
                </div>

                {{-- Driver --}}
                <div class="flex items-start gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#025C5F]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-[#025C5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400 font-medium">{{ __('Driver') }}</p>
                        @if($delivery['driver_name'])
                        <p class="text-xs text-gray-700 mt-0.5 font-medium">{{ $delivery['driver_name'] }}</p>
                        @if($delivery['driver_phone'])
                        <p class="text-[10px] text-gray-500 mt-0.5">{{ $delivery['driver_phone'] }}</p>
                        @endif
                        @else
                        <p class="text-xs text-gray-400 mt-0.5">{{ __('Not assigned yet') }}</p>
                        @endif
                    </div>
                </div>

                {{-- Meals --}}
                <div class="flex items-start gap-2">
                    <div class="w-8 h-8 rounded-lg bg-[#949B50]/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-[#949B50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400 font-medium">{{ __('Meals') }}</p>
                        <p class="text-xs text-gray-700 mt-0.5 font-medium">{{ $delivery['meal_count'] }} {{ __('meals') }}</p>
                        @if(!empty($delivery['meal_names']))
                        <p class="text-[10px] text-gray-500 mt-0.5 truncate">{{ implode(', ', array_slice($delivery['meal_names'], 0, 3)) }}@if(count($delivery['meal_names']) > 3) +{{ count($delivery['meal_names']) - 3 }}@endif</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($delivery['status'] === 'failed' && $delivery['failure_reason'])
            <div class="px-4 sm:px-5 py-3 bg-red-50 border-t border-red-100">
                <p class="text-xs text-red-700"><span class="font-bold">{{ __('Failure reason') }}:</span> {{ $delivery['failure_reason'] }}</p>
            </div>
            @endif

            @if($delivery['notes'])
            <div class="px-4 sm:px-5 py-3 bg-yellow-50 border-t border-yellow-100">
                <p class="text-xs text-yellow-700"><span class="font-bold">{{ __('Notes') }}:</span> {{ $delivery['notes'] }}</p>
            </div>
            @endif
        </div>
        @endforeach
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">{{ __('No deliveries today') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ __('Your deliveries for today will appear here once scheduled.') }}</p>
    </div>
    @endif
</div>

@endsection
