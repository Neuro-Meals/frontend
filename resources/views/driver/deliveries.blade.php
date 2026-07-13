@extends('layouts.driver')

@section('title', __('My Deliveries') . ' - ' . __('Nutrio Meals'))

@section('content')
<div x-data="{ tab: 'active' }" class="pb-4">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-brand-700 to-brand-600 text-white p-5 rounded-b-3xl shadow-lg shadow-brand-700/20">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('driver.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-lg font-bold">{{ __('My Deliveries') }}</h1>
        </div>
        <p class="text-xs text-white/70">{{ __('Manage your assigned deliveries') }}</p>
    </div>

    <div class="p-4">
        {{-- Tabs --}}
        <div class="bg-white rounded-2xl p-1 shadow-sm border border-gray-100 mb-4 flex">
            <button @click="tab = 'active'" :class="tab === 'active' ? 'bg-brand-700 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'" class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">{{ __('Active') }}</button>
            <button @click="tab = 'history'" :class="tab === 'history' ? 'bg-brand-700 text-white shadow-sm' : 'text-gray-500 hover:bg-gray-50'" class="flex-1 py-2 rounded-xl text-xs font-bold transition-all">{{ __('History') }}</button>
        </div>

        @php
        $activeDeliveries = array_filter($deliveries, fn ($d) => !in_array($d['status'], ['delivered', 'failed', 'cancelled']));
        $historyDeliveries = array_filter($deliveries, fn ($d) => in_array($d['status'], ['delivered', 'failed', 'cancelled']));
        @endphp

        {{-- Active Tab --}}
        <div x-show="tab === 'active'" class="space-y-3 animate-slide-up">
            @forelse($activeDeliveries as $delivery)
            @php
                $statusColor = match($delivery['status']) {
                    'assigned' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'picked_up' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'out_for_delivery' => 'bg-purple-50 text-purple-700 border-purple-200',
                    default => 'bg-gray-50 text-gray-600 border-gray-200',
                };
            @endphp
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <p class="text-xs font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $delivery['zone'] }} · {{ $delivery['time'] }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ $statusColor }}">{{ __($delivery['status_label']) }}</span>
                </div>
                <div class="flex items-start gap-2 mb-2">
                    <svg class="w-4 h-4 text-brand-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-xs text-gray-700 leading-relaxed flex-1">{{ $delivery['address'] ?: __('No address provided') }}</p>
                </div>
                @if($delivery['address'])
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($delivery['address']) }}" target="_blank" rel="noopener"
                    class="mb-4 flex items-center justify-center gap-1.5 w-full py-2 rounded-xl border border-brand-200 bg-brand-50 text-brand-700 text-xs font-bold hover:bg-brand-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    {{ __('Get Directions') }}
                </a>
                @endif
                <div class="grid grid-cols-2 gap-2">
                    @if(in_array($delivery['status'], ['assigned', 'pending']))
                    <button type="button"
                        onclick="confirmDeliveryAction('{{ route('driver.deliveries.status', $delivery['id']) }}', 'picked_up', {
                            title: '{{ __('Pick up this order?') }}',
                            text: '{{ __('Confirm that you have picked up the order from the kitchen.') }}',
                            confirmText: '{{ __('Yes, Pick Up') }}',
                            icon: 'question',
                            confirmColor: '#173327'
                        })"
                        class="btn-action col-span-2 w-full py-2.5 rounded-xl bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-700/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Pick Up') }}
                    </button>
                    @elseif($delivery['status'] === 'picked_up')
                    <button type="button"
                        onclick="confirmDeliveryAction('{{ route('driver.deliveries.status', $delivery['id']) }}', 'out_for_delivery', {
                            title: '{{ __('Head out for delivery?') }}',
                            text: '{{ __('This will notify the customer that their order is on the way.') }}',
                            confirmText: '{{ __('Yes, Go') }}',
                            icon: 'question',
                            confirmColor: '#7c3aed'
                        })"
                        class="btn-action col-span-2 w-full py-2.5 rounded-xl bg-purple-600 text-white text-xs font-bold shadow-md shadow-purple-600/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ __('Out for Delivery') }}
                    </button>
                    @elseif($delivery['status'] === 'out_for_delivery')
                    <button type="button"
                        onclick="confirmDeliveryAction('{{ route('driver.deliveries.status', $delivery['id']) }}', 'delivered', {
                            title: '{{ __('Confirm Delivery?') }}',
                            text: '{{ __('Only confirm once you have arrived and handed the order to the customer.') }}',
                            confirmText: '{{ __('Yes, Delivered') }}',
                            icon: 'success',
                            confirmColor: '#16a34a'
                        })"
                        class="btn-action col-span-1 w-full py-2.5 rounded-xl bg-green-600 text-white text-xs font-bold shadow-md shadow-green-600/20 flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Delivered') }}
                    </button>
                    <button type="button" onclick="confirmFailDelivery('{{ route('driver.deliveries.status', $delivery['id']) }}')" class="btn-action w-full py-2.5 rounded-xl bg-red-50 text-red-600 border border-red-100 text-xs font-bold flex items-center justify-center gap-1">
                        {{ __('Failed') }}
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm text-gray-500">{{ __('No active deliveries.') }}</p>
                <a href="{{ route('driver.dashboard') }}" class="inline-block mt-3 text-xs font-bold text-brand-600">{{ __('Back to Dashboard') }}</a>
            </div>
            @endforelse
        </div>

        {{-- History Tab --}}
        <div x-show="tab === 'history'" class="space-y-3 animate-slide-up" style="display: none;">
            @forelse($historyDeliveries as $delivery)
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $delivery['date'] }} · {{ $delivery['customer'] }}</p>
                    @if($delivery['status'] === 'failed' && $delivery['failure_reason'])
                    <p class="text-[10px] text-red-500 mt-1">{{ $delivery['failure_reason'] }}</p>
                    @endif
                </div>
                <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ $delivery['status'] === 'delivered' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                    {{ __($delivery['status_label']) }}
                </span>
            </div>
            @empty
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
                <p class="text-sm text-gray-500">{{ __('No delivery history yet.') }}</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
