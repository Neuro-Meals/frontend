@extends('layouts.driver')

@section('title', __('My Deliveries') . ' - ' . __('Nutrio Meals'))

@section('content')
<div x-data="{ tab: 'active', selectedHistory: null }" class="pb-4">
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
                <a href="{{ route('driver.deliveries.map', $delivery['id']) }}"
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
                <a href="{{ route('driver.deliveries.detail', $delivery['id']) }}" class="mt-3 flex items-center justify-between pt-3 border-t border-gray-50 hover:text-brand-700 transition-colors">
                    <span class="text-[10px] text-gray-400 font-semibold">{{ __('View full details') }}</span>
                    <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
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
            @php
                $isDelivered = $delivery['status'] === 'delivered';
                $isFailed = $delivery['status'] === 'failed';
                $statusColor = $isDelivered
                    ? 'bg-green-50 text-green-700 border-green-200'
                    : ($isFailed ? 'bg-red-50 text-red-600 border-red-200' : 'bg-gray-50 text-gray-600 border-gray-200');
                $stepLabels = [
                    'assigned' => __('Assigned'),
                    'picked_up' => __('Picked Up'),
                    'out_for_delivery' => __('Out for Delivery'),
                    'delivered' => __('Delivered'),
                ];
                $stepOrder = ['assigned', 'picked_up', 'out_for_delivery', 'delivered'];
                $currentStepIndex = array_search($delivery['status'], $stepOrder);
                if ($currentStepIndex === false) {
                    $currentStepIndex = $isFailed ? 2 : 0;
                }
            @endphp
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 overflow-hidden transition-all"
                 :class="selectedHistory === {{ $delivery['id'] }} ? 'ring-2 ring-brand-200' : ''">
                <div class="flex items-center justify-between cursor-pointer" @click="selectedHistory = selectedHistory === {{ $delivery['id'] }} ? null : {{ $delivery['id'] }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-700 to-brand-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($delivery['customer'], 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                            <p class="text-[10px] text-gray-400">{{ $delivery['date'] }} · {{ $delivery['customer'] }}</p>
                            @if($isFailed && $delivery['failure_reason'])
                            <p class="text-[10px] text-red-500 mt-0.5">{{ $delivery['failure_reason'] }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-1 rounded-full text-[10px] font-semibold border {{ $statusColor }}">
                            {{ __($delivery['status_label']) }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="selectedHistory === {{ $delivery['id'] }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                {{-- Expanded details --}}
                <div x-show="selectedHistory === {{ $delivery['id'] }}" x-transition class="mt-4 pt-4 border-t border-gray-100">
                    {{-- Delivery stepper --}}
                    <div class="mb-5">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Delivery Progress') }}</p>
                        <div class="relative flex justify-between items-start">
                            <div class="absolute top-3.5 left-0 w-full h-0.5 bg-gray-200 z-0"></div>
                            @foreach($stepOrder as $index => $step)
                            @php
                                $isCompleted = $index <= $currentStepIndex && !($isFailed && $step === 'delivered');
                                $isCurrent = $index === $currentStepIndex && !$isFailed && $step !== 'delivered';
                                $stepActive = $index === $currentStepIndex;
                                $stepColor = $isFailed && $step === 'delivered' ? 'bg-red-500 border-red-500' : ($isCompleted ? 'bg-brand-600 border-brand-600' : 'bg-gray-200 border-gray-200');
                                $textColor = $isFailed && $step === 'delivered' ? 'text-red-600' : ($isCompleted || $stepActive ? 'text-gray-900' : 'text-gray-400');
                            @endphp
                            <div class="flex flex-col items-center z-10 w-1/4">
                                <div class="w-7 h-7 rounded-full border-2 flex items-center justify-center {{ $stepColor }}">
                                    @if($isFailed && $step === 'delivered')
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    @elseif($isCompleted)
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                    <div class="w-2 h-2 rounded-full bg-white"></div>
                                    @endif
                                </div>
                                <p class="text-[9px] font-semibold mt-1.5 text-center leading-tight {{ $textColor }}">{{ $stepLabels[$step] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Customer details --}}
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Customer Details') }}</p>
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($delivery['customer'], 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $delivery['customer'] }}</p>
                                @if($delivery['customer_phone'])
                                <p class="text-[10px] text-gray-500">{{ $delivery['customer_phone'] }}</p>
                                @endif
                            </div>
                        </div>
                        @if($delivery['customer_email'])
                        <div class="flex items-start gap-2 mb-2">
                            <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-gray-700 break-all">{{ $delivery['customer_email'] }}</p>
                        </div>
                        @endif
                        @if($delivery['customer_location'] || $delivery['customer_address'] || $delivery['address'])
                        <div class="flex items-start gap-2">
                            <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-xs text-gray-700 leading-relaxed flex-1">
                                {{ $delivery['address'] ?: ($delivery['customer_address'] ? $delivery['customer_address'] . ($delivery['customer_location'] ? ', ' . $delivery['customer_location'] : '') : ($delivery['customer_location'] ?: __('No address provided'))) }}
                            </p>
                        </div>
                        @endif

                        @if($delivery['customer_phone'])
                        <div class="grid grid-cols-2 gap-2 mt-3">
                            <a href="tel:{{ $delivery['customer_phone'] }}" class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-brand-700 text-white text-xs font-bold hover:bg-brand-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ __('Call') }}
                            </a>
                            @if($delivery['whatsapp_phone'])
                            <a href="https://wa.me/{{ $delivery['whatsapp_phone'] }}" target="_blank" rel="noopener" class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl bg-[#25D366] text-white text-xs font-bold hover:bg-[#1da851] transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.6 6.32A7.85 7.85 0 0012 4a7.94 7.94 0 00-8 7.88c0 1.39.36 2.74 1.05 3.94L4 20l4.3-1.12A7.93 7.93 0 0012 19.77h.02A7.94 7.94 0 0020 11.89a7.85 7.85 0 00-2.4-5.57zM12 18.1a6.2 6.2 0 01-3.16-.87l-.23-.14-2.55.67.68-2.49-.18-.28a6.23 6.23 0 119.16 1.91 6.18 6.18 0 01-3.72 1.2zM14.6 13.5c-.08-.13-.28-.2-.58-.35-.3-.15-1.77-.87-2.05-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.08-.3-.15-1.27-.47-2.42-1.5a8.9 8.9 0 01-1.65-2.02c-.17-.3 0-.46.13-.6.13-.14.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5l-.37-.01c-.13 0-.35.05-.53.25-.18.2-.7.68-.7 1.66s.72 1.93.82 2.06c.1.13 1.4 2.13 3.4 2.99.47.2.85.33 1.14.42.48.15.92.13 1.27.08.39-.06 1.2-.49 1.37-.96.17-.47.17-.87.12-.96z"/></svg>
                                {{ __('WhatsApp') }}
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('driver.deliveries.detail', $delivery['id']) }}" class="mt-4 flex items-center justify-center gap-1.5 w-full py-2.5 rounded-xl border border-brand-200 bg-brand-50 text-brand-700 text-xs font-bold hover:bg-brand-100 transition-colors">
                        {{ __('View full details') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
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
