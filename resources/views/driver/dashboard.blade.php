@extends('layouts.driver')

@section('title', __('Driver Dashboard') . ' - ' . __('Nutrio Meals'))

@section('content')
@php
$user = app(\App\Services\Api\AuthApiService::class)->user() ?? [];
$driverName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: ($user['email'] ?? 'Driver');
@endphp

<div x-data="driverDashboard()" x-init="init()" class="pb-4">
    {{-- Top bar --}}
    <div class="flex items-center justify-between px-5 pt-5 pb-3 animate-slide-up">
        <div>
            <p class="text-[11px] text-gray-400 mb-0.5">{{ __('Good day,') }}</p>
            <h1 class="text-sm font-bold text-gray-900">{{ $driverName }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('driver.notifications') }}" class="relative w-10 h-10 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if(count($notifications) > 0)
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 pulse-dot"></span>
                @endif
            </a>
        </div>
    </div>

    {{-- Driver / Zone hero card --}}
    <div class="mx-4 relative bg-gradient-to-br from-brand-700 to-brand-800 text-white rounded-3xl p-5 shadow-lg shadow-brand-700/20 overflow-hidden animate-slide-up animate-delay-1">
        <svg class="absolute -bottom-6 -right-6 w-28 h-28 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8 6 6 9 6 13a6 6 0 0012 0c0-4-2-7-6-11z"/></svg>
        <p class="text-xs text-white/70 mb-1">{{ __('Driver') }}</p>
        <h2 class="text-3xl font-extrabold tracking-tight">{{ $driverCode }}</h2>
        <p class="text-xs text-white/70 mt-1 flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $primaryZone }}
        </p>
    </div>

    <div class="p-4 space-y-4">
        @php
            $shiftComplete = ($stats['assigned'] + $stats['in_progress']) === 0 && $stats['completed'] > 0;
        @endphp

        @unless($shiftComplete)
        {{-- Today's Meals + Status cards --}}
        <div class="grid grid-cols-2 gap-3 animate-slide-up animate-delay-2">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p class="text-2xl font-extrabold text-gray-900">{{ $stats['today'] }}</p>
                <p class="text-xs text-gray-400 font-semibold">{{ __('Today\'s Meals') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <div class="w-10 h-10 rounded-full {{ $onDelivery ? 'bg-purple-50' : ($readyForPickup ? 'bg-green-50' : 'bg-gray-50') }} flex items-center justify-center mb-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $onDelivery ? 'bg-purple-400' : ($readyForPickup ? 'bg-green-400' : 'bg-gray-400') }} opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $onDelivery ? 'bg-purple-500' : ($readyForPickup ? 'bg-green-500' : 'bg-gray-400') }}"></span>
                    </span>
                </div>
                <p class="text-sm font-extrabold {{ $onDelivery ? 'text-purple-600' : ($readyForPickup ? 'text-green-600' : 'text-gray-500') }}">
                    {{ $onDelivery ? __('On Delivery') : ($readyForPickup ? __('Ready for Pickup') : __('No Deliveries Yet')) }}
                </p>
                <p class="text-[10px] text-gray-400 font-semibold">{{ __('Status') }}</p>
            </div>
        </div>

        {{-- Start Delivery CTA --}}
        @if(count($currentDeliveries) > 0)
        <a href="{{ route('driver.deliveries') }}" class="btn-action w-full flex items-center justify-center gap-2 py-4 rounded-2xl bg-gradient-to-r from-brand-700 to-brand-600 text-white text-sm font-bold shadow-md shadow-brand-700/20 animate-slide-up animate-delay-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
            {{ __('Start Delivery') }}
        </a>
        @endif
        @endunless

        {{-- Browse & claim available loads --}}
        <a href="{{ route('driver.available-loads') }}" class="btn-action w-full flex items-center justify-between gap-3 bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold text-gray-900">{{ __('Available Loads') }}</p>
                    <p class="text-[10px] text-gray-400">{{ __('Browse and claim ready orders near you') }}</p>
                </div>
            </div>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" transform="rotate(180 12 12)"/></svg>
        </a>

        {{-- Shift Completed celebration --}}
        @if($shiftComplete)
        <div class="relative bg-white rounded-3xl p-6 text-center shadow-lg border border-gray-100 overflow-hidden animate-pop-in">
            <span class="absolute top-3 left-3 text-2xl animate-float">🎉</span>
            <span class="absolute top-3 right-3 text-2xl animate-float animate-delay-2">🎉</span>
            <div class="w-20 h-20 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-lg font-bold text-brand-700 mb-1">{{ __('Shift Completed') }}</h2>
            <p class="text-xs text-gray-500 mb-1">{{ __('Great Job!') }}</p>
            <p class="text-xs text-gray-400 mb-5">{{ __('You have completed all deliveries.') }}</p>

            <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-lg font-extrabold text-gray-900">{{ $stats['completed'] + $stats['failed'] }}</p>
                    <p class="text-[10px] text-gray-500">{{ __('Total') }}</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3">
                    <p class="text-lg font-extrabold text-green-600">{{ $stats['completed'] }}</p>
                    <p class="text-[10px] text-gray-500">{{ __('Delivered') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-lg font-extrabold text-gray-400">0</p>
                    <p class="text-[10px] text-gray-500">{{ __('Remaining') }}</p>
                </div>
            </div>

            <a href="{{ route('driver.deliveries') }}" class="btn-action w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-brand-700 text-white text-sm font-bold shadow-md shadow-brand-700/20 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                {{ __('View Delivery Summary') }}
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-gray-50 text-gray-600 text-sm font-bold border border-gray-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    {{ __('End Shift') }}
                </button>
            </form>
        </div>
        @endif

        {{-- Rating / Status mini bar --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between animate-slide-up animate-delay-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400">{{ __('Rating') }}</p>
                    <p class="text-sm font-bold text-gray-900" x-text="stats.rating + '/5'">{{ $stats['rating'] }}/5</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                <span class="text-xs font-bold text-green-600">{{ __('Online') }}</span>
            </div>
        </div>

        {{-- Current Deliveries --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-gray-900">{{ __('Current Deliveries') }}</h2>
                <a href="{{ route('driver.deliveries') }}" class="text-xs font-bold text-brand-600">{{ __('See all') }}</a>
            </div>

            @if(count($currentDeliveries) === 0)
            <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 shadow-sm animate-slide-up animate-delay-3">
                <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <p class="text-sm text-gray-500">{{ __('No active deliveries right now.') }}</p>
            </div>
            @endif

            <div class="space-y-3">
                @foreach($currentDeliveries as $delivery)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-xs font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                            <p class="text-[10px] text-gray-400">{{ $delivery['zone'] }} · {{ $delivery['time'] }}</p>
                        </div>
                        @php
                            $statusColor = match($delivery['status']) {
                                'assigned' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'picked_up' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'out_for_delivery' => 'bg-purple-50 text-purple-700 border-purple-200',
                                default => 'bg-gray-50 text-gray-600 border-gray-200',
                            };
                        @endphp
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
                @endforeach
            </div>
        </div>

        {{-- Notifications --}}
        @if(count($notifications) > 0)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-3">
            <h2 class="text-sm font-bold text-gray-900 mb-3">{{ __('Notifications') }}</h2>
            <div class="space-y-3">
                @foreach($notifications as $notification)
                <div class="flex items-start gap-3 p-3 rounded-xl {{ $notification['is_read'] ? 'bg-gray-50' : 'bg-brand-50/50 border border-brand-100' }}">
                    <div class="w-8 h-8 rounded-full {{ $notification['is_read'] ? 'bg-gray-100 text-gray-400' : 'bg-brand-100 text-brand-600' }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-900 truncate">{{ $notification['title'] }}</p>
                        <p class="text-[10px] text-gray-500 line-clamp-2">{{ $notification['message'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Recent History --}}
        <div>
            <h2 class="text-sm font-bold text-gray-900 mb-3">{{ __('Recent History') }}</h2>
            @if(count($history) === 0)
            <p class="text-xs text-gray-400 text-center py-4">{{ __('No completed deliveries yet.') }}</p>
            @else
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @foreach(array_slice($history, 0, 5) as $delivery)
                <div class="flex items-center justify-between p-4 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-xs font-bold text-gray-900">{{ $delivery['order_number'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $delivery['customer'] }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $delivery['status'] === 'delivered' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-600 border-red-200' }}">
                        {{ __($delivery['status_label']) }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function driverDashboard() {
    return {
        stats: @json($stats),

        init() {
            this.watchLocation();
        },

        watchLocation() {
            if (!navigator.geolocation) return;
            navigator.geolocation.watchPosition(
                (position) => this.sendLocation(position.coords.latitude, position.coords.longitude),
                () => {},
                { enableHighAccuracy: true, maximumAge: 30000, timeout: 10000 }
            );
        },

        async sendLocation(lat, lng) {
            const deliveries = @json($currentDeliveries);
            const active = deliveries.find(d => ['picked_up', 'out_for_delivery'].includes(d.status));
            if (!active) return;
            try {
                await fetch(`{{ url('driver/deliveries') }}/${active.id}/location`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng }),
                });
            } catch (e) {}
        },
    };
}
</script>
@endpush
