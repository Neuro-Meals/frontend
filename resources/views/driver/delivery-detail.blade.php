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
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Customer Information') }}</h2>
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

        {{-- Order items --}}
        @if(!empty($delivery['items']))
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-3">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Order Items') }}</h2>
            <div class="space-y-2">
                @foreach($delivery['items'] as $item)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center text-brand-700 font-bold text-xs">
                            {{ $loop->iteration }}
                        </div>
                        <p class="text-xs font-semibold text-gray-900">{{ $item['name_en'] ?? ($item['name'] ?? __('Meal')) }}</p>
                    </div>
                    <span class="text-[10px] text-gray-400">x{{ $item['quantity'] ?? 1 }}</span>
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
        </div>
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
