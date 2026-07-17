@extends('layouts.driver')

@section('title', __('Delivery Details') . ' - ' . __('Nutrio Meals'))

@section('content')
@php
$statusColors = [
    'assigned' => 'bg-blue-50 text-blue-700 border-blue-200',
    'picked_up' => 'bg-amber-50 text-amber-700 border-amber-200',
    'out_for_delivery' => 'bg-purple-50 text-purple-700 border-purple-200',
    'delivered' => 'bg-green-50 text-green-700 border-green-200',
    'failed' => 'bg-red-50 text-red-600 border-red-200',
    'cancelled' => 'bg-gray-50 text-gray-600 border-gray-200',
    'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
];
$statusLabel = match($delivery['status']) {
    'assigned' => __('Assigned'),
    'picked_up' => __('Picked Up'),
    'out_for_delivery' => __('Out for Delivery'),
    'delivered' => __('Delivered'),
    'failed' => __('Failed'),
    'cancelled' => __('Cancelled'),
    default => __('Pending'),
};
@endphp

<div class="pb-4">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-brand-700 to-brand-600 text-white p-5 rounded-b-3xl shadow-lg shadow-brand-700/20 animate-slide-up">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('driver.deliveries') }}" class="p-2 -ml-2 rounded-full hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-lg font-bold">{{ __('Delivery Details') }}</h1>
        </div>
        <p class="text-xs text-white/70">{{ $delivery['order_number'] }} · {{ $delivery['zone'] }}</p>
    </div>

    @if(($stepper['total'] ?? 0) > 0)
    <div class="px-4 -mt-4 relative z-10">
        <div class="bg-white rounded-2xl p-4 shadow-lg border border-gray-100 animate-slide-up">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-gray-700">{{ __('Delivery') }} {{ $stepper['position'] }} {{ __('of') }} {{ $stepper['total'] }}</span>
                <span class="text-[10px] font-bold text-gray-400">{{ $stepper['remaining'] }} {{ __('Remaining') }}</span>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-brand-600 to-brand-700 rounded-full transition-all duration-500" style="width: {{ $stepper['total'] > 0 ? round(($stepper['position'] / $stepper['total']) * 100) : 0 }}%"></div>
            </div>
        </div>
    </div>
    @endif

    <div class="p-4 space-y-4">
        {{-- Status card --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between animate-slide-up animate-delay-1">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-brand-700 to-brand-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                    {{ strtoupper(substr($delivery['status_label'], 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs text-gray-400">{{ __('Status') }}</p>
                    <p class="text-sm font-bold text-gray-900">{{ $statusLabel }}</p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$delivery['status']] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                {{ __($delivery['status_label']) }}
            </span>
        </div>

        {{-- Customer info card --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Customer Information') }}</h2>
                @if(($stepper['total'] ?? 0) > 0)
                <span class="px-2 py-0.5 rounded-full bg-brand-50 text-brand-700 text-[10px] font-bold">{{ __('Stop') }} {{ $stepper['position'] }}</span>
                @endif
            </div>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 font-bold text-lg flex-shrink-0">
                    {{ strtoupper(substr($delivery['customer'], 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-900">{{ $delivery['customer'] }}</p>
                    <p class="text-[10px] text-gray-500">{{ $delivery['customer_phone'] ?: __('No phone number') }}</p>
                </div>
            </div>

            <div class="space-y-3 mb-4">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-brand-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <p class="text-xs text-gray-700 leading-relaxed flex-1">{{ $delivery['address'] ?: __('No address provided') }}</p>
                </div>
                @if($delivery['notes'])
                <div class="flex items-start gap-2 bg-amber-50 p-2 rounded-lg border border-amber-100">
                    <svg class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <p class="text-xs text-amber-800 leading-relaxed flex-1 italic">{{ $delivery['notes'] }}</p>
                </div>
                @endif
                @if($delivery['scheduled_at'])
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-brand-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-gray-700">{{ $delivery['date'] }} · {{ $delivery['time'] }}</p>
                </div>
                @endif
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-brand-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-gray-700 font-bold">{{ __('Order Amount') }}: SAR {{ number_format($delivery['amount'], 2) }}</p>
                </div>
            </div>

            @if($delivery['customer_phone'])
            <div class="grid grid-cols-2 gap-2">
                <a href="tel:{{ $delivery['customer_phone'] }}" class="flex items-center justify-center gap-1.5 py-3 rounded-xl bg-brand-50 text-brand-700 border border-brand-200 text-xs font-bold hover:bg-brand-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    {{ __('Call') }}
                </a>
                @if($delivery['whatsapp_phone'])
                <a href="https://wa.me/{{ $delivery['whatsapp_phone'] }}" target="_blank" rel="noopener"
                    class="flex items-center justify-center gap-1.5 py-3 rounded-xl bg-green-50 text-green-700 border border-green-200 text-xs font-bold hover:bg-green-100 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.6 6.32A7.85 7.85 0 0012 4a7.94 7.94 0 00-8 7.88c0 1.39.36 2.74 1.05 3.94L4 20l4.3-1.12A7.93 7.93 0 0012 19.77h.02A7.94 7.94 0 0020 11.89a7.85 7.85 0 00-2.4-5.57zM12 18.1a6.2 6.2 0 01-3.16-.87l-.23-.14-2.55.67.68-2.49-.18-.28a6.23 6.23 0 119.16 1.91 6.18 6.18 0 01-3.72 1.2zM14.6 13.5c-.08-.13-.28-.2-.58-.35-.3-.15-1.77-.87-2.05-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.08-.3-.15-1.27-.47-2.42-1.5a8.9 8.9 0 01-1.65-2.02c-.17-.3 0-.46.13-.6.13-.14.3-.35.44-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5l-.37-.01c-.13 0-.35.05-.53.25-.18.2-.7.68-.7 1.66s.72 1.93.82 2.06c.1.13 1.4 2.13 3.4 2.99.47.2.85.33 1.14.42.48.15.92.13 1.27.08.39-.06 1.2-.49 1.37-.96.17-.47.17-.87.12-.96z"/></svg>
                    {{ __('WhatsApp') }}
                </a>
                @endif
            </div>
            @endif
        </div>

        {{-- Meal Summary --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-3">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Order Summary') }}</h2>
                <span class="text-[10px] font-bold text-gray-400">{{ $delivery['order_number'] }}</span>
            </div>
            <div class="bg-gray-50/60 rounded-xl p-3 mb-3">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-3.5 h-3.5 text-brand-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <p class="text-xs font-semibold text-gray-700 flex-1">{{ $delivery['meal_summary'] }}</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-[10px] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full">{{ $delivery['meal_count'] }} {{ __('items') }}</span>
                    @if($delivery['total_quantity'])
                    <span class="text-[10px] font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-full">{{ $delivery['total_quantity'] }} {{ __('qty') }}</span>
                    @endif
                    @if($delivery['total_calories'])
                    <span class="text-[10px] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full">{{ $delivery['total_calories'] }} {{ __('kcal') }}</span>
                    @endif
                    @if($delivery['total_protein_g'])
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">{{ $delivery['total_protein_g'] }}g {{ __('protein') }}</span>
                    @endif
                    @if($delivery['total_carbs_g'])
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">{{ $delivery['total_carbs_g'] }}g {{ __('carbs') }}</span>
                    @endif
                    @if($delivery['total_fat_g'])
                    <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full">{{ $delivery['total_fat_g'] }}g {{ __('fat') }}</span>
                    @endif
                    <span class="text-[10px] font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-full ml-auto">SAR {{ number_format($delivery['amount'], 2) }}</span>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <p class="text-[9px] text-gray-400 uppercase mb-0.5">{{ __('Scheduled') }}</p>
                    <p class="text-xs font-bold text-gray-900">{{ $delivery['time'] }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase mb-0.5">{{ __('Zone') }}</p>
                    <p class="text-xs font-bold text-gray-900 truncate">{{ $delivery['zone'] }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase mb-0.5">{{ __('ETA') }}</p>
                    <p class="text-xs font-bold text-gray-900">{{ $delivery['eta'] }}</p>
                </div>
            </div>
        </div>

        {{-- Order items with full details --}}
        @if(!empty($delivery['items']))
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-3">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Order Items') }}</h2>
                <div class="flex items-center gap-2">
                    @if($delivery['total_quantity'])
                    <span class="text-[10px] font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-full">{{ $delivery['total_quantity'] }} {{ __('qty') }}</span>
                    @endif
                    @if($delivery['total_calories'])
                    <span class="text-[10px] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full">{{ $delivery['total_calories'] }} {{ __('kcal') }}</span>
                    @endif
                </div>
            </div>
            <div class="space-y-3">
                @foreach($delivery['items'] as $item)
                @php
                    $itemName = $item['meal_name'] ?? ($item['name'] ?? ($item['name_en'] ?? ($item['title'] ?? __('Meal'))));
                    $itemQty = (int) ($item['quantity'] ?? 1);
                    $itemCal = (int) ($item['calories'] ?? 0);
                @endphp
                <div class="bg-gray-50/60 rounded-xl p-3 border border-gray-100">
                    <div class="flex items-start gap-2.5 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-white border border-gray-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                            @if(!empty($item['image_url']))
                            <img src="{{ $item['image_url'] }}" class="w-full h-full object-cover" alt="">
                            @else
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-900 leading-tight">{{ $itemName }}</p>
                            <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                <span class="text-[10px] font-bold text-brand-700 bg-brand-50 px-1.5 py-0.5 rounded-full">×{{ $itemQty }}</span>
                                @if($itemCal)
                                <span class="text-[10px] font-bold text-brand-700 bg-brand-50 px-1.5 py-0.5 rounded-full">{{ $itemCal }} kcal</span>
                                @endif
                                @if(!empty($item['protein_g']))
                                <span class="text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-full">P {{ $item['protein_g'] }}g</span>
                                @endif
                                @if(!empty($item['carbs_g']))
                                <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded-full">C {{ $item['carbs_g'] }}g</span>
                                @endif
                                @if(!empty($item['fat_g']))
                                <span class="text-[9px] font-bold text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded-full">F {{ $item['fat_g'] }}g</span>
                                @endif
                            </div>
                        </div>
                        @if(!empty($item['unit_price']))
                        <span class="text-[10px] font-bold text-gray-600 flex-shrink-0">SAR {{ number_format($item['unit_price'], 2) }}</span>
                        @endif
                    </div>
                    @if(!empty($item['ingredients']) && is_array($item['ingredients']))
                    <div class="mb-2">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('Ingredients') }}</p>
                        <div class="flex flex-wrap items-center gap-1">
                            @foreach($item['ingredients'] as $ing)
                            <span class="px-1.5 py-0.5 rounded-full bg-white border border-gray-200 text-[10px] text-gray-600">{{ $ing }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @if(!empty($item['allergens']) && is_array($item['allergens']))
                    <div>
                        <p class="text-[9px] font-bold text-red-400 uppercase tracking-wide mb-1">{{ __('Allergens') }}</p>
                        <div class="flex flex-wrap items-center gap-1">
                            @foreach($item['allergens'] as $a)
                            <span class="px-1.5 py-0.5 rounded-full bg-red-50 border border-red-100 text-[10px] text-red-600">{{ $a }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Map & Actions --}}
        <div class="grid grid-cols-2 gap-3 animate-slide-up animate-delay-3">
            <a href="{{ route('driver.deliveries.map', $delivery['id']) }}" class="col-span-2 flex items-center justify-center gap-1.5 py-3 rounded-xl bg-gradient-to-r from-brand-700 to-brand-600 text-white text-xs font-bold shadow-md shadow-brand-700/20 hover:shadow-lg transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                {{ __('Open Live Directions Map') }}
            </a>

            @if(in_array($delivery['status'], ['assigned', 'pending']))
            <button type="button"
                onclick="confirmDeliveryAction('{{ route('driver.deliveries.status', $delivery['id']) }}', 'picked_up', {
                    title: '{{ __('Pick up this order?') }}',
                    text: '{{ __('Confirm that you have picked up the order from the kitchen.') }}',
                    confirmText: '{{ __('Yes, Pick Up') }}',
                    icon: 'question',
                    confirmColor: '#173327'
                })"
                class="btn-action col-span-2 w-full py-3 rounded-xl bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-700/20 flex items-center justify-center gap-2">
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
                class="btn-action col-span-2 w-full py-3 rounded-xl bg-purple-600 text-white text-xs font-bold shadow-md shadow-purple-600/20 flex items-center justify-center gap-2">
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
                class="btn-action col-span-1 w-full py-3 rounded-xl bg-green-600 text-white text-xs font-bold shadow-md shadow-green-600/20 flex items-center justify-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ __('Delivered') }}
            </button>
            <button type="button" onclick="confirmFailDelivery('{{ route('driver.deliveries.status', $delivery['id']) }}')" class="btn-action w-full py-3 rounded-xl bg-red-50 text-red-600 border border-red-100 text-xs font-bold flex items-center justify-center gap-1">
                {{ __('Failed') }}
            </button>
            @endif

            @if(!in_array($delivery['status'], ['delivered', 'failed', 'cancelled', 'out_for_delivery']))
            <button type="button" onclick="confirmFailDelivery('{{ route('driver.deliveries.status', $delivery['id']) }}')" class="col-span-2 flex items-center justify-center gap-1.5 py-2.5 text-red-500 text-[11px] font-bold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ __('Report an Issue') }}
            </button>
            @endif
        </div>

        {{-- Previous / Next Stop --}}
        @if(($stepper['total'] ?? 0) > 1)
        <div class="flex items-center justify-between pt-2 animate-slide-up animate-delay-3">
            @if($stepper['prev_id'])
            <a href="{{ route('driver.deliveries.detail', $stepper['prev_id']) }}" class="flex items-center gap-1 text-xs font-bold text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                {{ __('Previous Stop') }}
            </a>
            @else
            <span></span>
            @endif
            @if($stepper['next_id'])
            <a href="{{ route('driver.deliveries.detail', $stepper['next_id']) }}" class="flex items-center gap-1 text-xs font-bold text-brand-700">
                {{ __('Next Stop') }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" transform="rotate(180 12 12)"/></svg>
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// confirmDeliveryAction and confirmFailDelivery are defined in layouts.driver
</script>
@endpush

@push('styles')
<style>
.animate-slide-up { animation: slideUp 0.35s ease-out both; }
@keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
.animate-delay-1 { animation-delay: 0.05s; }
.animate-delay-2 { animation-delay: 0.1s; }
.animate-delay-3 { animation-delay: 0.15s; }
</style>
@endpush
