@extends('layouts.driver')

@section('title', __('Driver Dashboard') . ' - ' . __('Nutrio Meals'))

@section('content')
@php
$user = app(\App\Services\Api\AuthApiService::class)->user() ?? [];
$driverName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: ($user['email'] ?? 'Driver');
@endphp

<div x-data="driverDashboard()" x-init="init()" class="pb-4">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-brand-700 to-brand-600 text-white p-5 rounded-b-3xl shadow-lg shadow-brand-700/20 animate-slide-up">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs text-white/70 mb-0.5">{{ __('Good day,') }}</p>
                <h1 class="text-lg font-bold">{{ $driverName }}</h1>
            </div>
            <div class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center border border-white/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10 animate-slide-up animate-delay-1">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-2 h-2 rounded-full bg-blue-300 pulse-dot"></div>
                    <span class="text-[10px] text-white/70">{{ __('Today') }}</span>
                </div>
                <p class="text-xl font-bold" x-text="stats.today">{{ $stats['today'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10 animate-slide-up animate-delay-2">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-2 h-2 rounded-full bg-amber-300"></div>
                    <span class="text-[10px] text-white/70">{{ __('Active') }}</span>
                </div>
                <p class="text-xl font-bold" x-text="stats.in_progress">{{ $stats['in_progress'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10 animate-slide-up animate-delay-3">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-2 h-2 rounded-full bg-green-300"></div>
                    <span class="text-[10px] text-white/70">{{ __('Done') }}</span>
                </div>
                <p class="text-xl font-bold" x-text="stats.completed">{{ $stats['completed'] }}</p>
            </div>
        </div>
    </div>

    <div class="p-4 space-y-4">
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
                    <div class="flex items-start gap-2 mb-4">
                        <svg class="w-4 h-4 text-brand-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-xs text-gray-700 leading-relaxed">{{ $delivery['address'] ?: __('No address provided') }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        @if(in_array($delivery['status'], ['assigned', 'pending']))
                        <form action="{{ route('driver.deliveries.status', $delivery['id']) }}" method="POST" class="col-span-2">
                            @csrf
                            <input type="hidden" name="status" value="picked_up">
                            <button type="submit" class="btn-action w-full py-2.5 rounded-xl bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-700/20 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Pick Up') }}
                            </button>
                        </form>
                        @elseif($delivery['status'] === 'picked_up')
                        <form action="{{ route('driver.deliveries.status', $delivery['id']) }}" method="POST" class="col-span-2">
                            @csrf
                            <input type="hidden" name="status" value="out_for_delivery">
                            <button type="submit" class="btn-action w-full py-2.5 rounded-xl bg-purple-600 text-white text-xs font-bold shadow-md shadow-purple-600/20 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                {{ __('Out for Delivery') }}
                            </button>
                        </form>
                        @elseif($delivery['status'] === 'out_for_delivery')
                        <form action="{{ route('driver.deliveries.status', $delivery['id']) }}" method="POST" class="col-span-1">
                            @csrf
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="btn-action w-full py-2.5 rounded-xl bg-green-600 text-white text-xs font-bold shadow-md shadow-green-600/20 flex items-center justify-center gap-1">
                                {{ __('Delivered') }}
                            </button>
                        </form>
                        <button @click="openFail({{ $delivery['id'] }})" class="btn-action w-full py-2.5 rounded-xl bg-red-50 text-red-600 border border-red-100 text-xs font-bold flex items-center justify-center gap-1">
                            {{ __('Failed') }}
                        </button>
                        @endif
                    </div>
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

    {{-- Fail Reason Modal --}}
    <div x-show="failModalOpen" x-cloak class="fixed inset-0 z-50 flex items-end justify-center" style="background: rgba(0,0,0,0.5);">
        <div x-show="failModalOpen" x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="bg-white rounded-t-3xl w-full max-w-md p-5 pb-8">
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-5"></div>
            <h3 class="text-base font-bold text-gray-900 mb-1">{{ __('Mark Delivery as Failed') }}</h3>
            <p class="text-xs text-gray-400 mb-4">{{ __('Please tell us why the delivery could not be completed.') }}</p>
            <form @submit.prevent="submitFail" class="space-y-3">
                <input type="hidden" x-model="failId">
                <textarea x-model="failReason" rows="3" placeholder="{{ __('Reason...') }}" class="w-full px-3 py-2 rounded-xl border border-gray-200 text-sm focus:border-brand-600 focus:ring-2 focus:ring-brand-600/20 outline-none resize-none" required></textarea>
                <div class="flex gap-2">
                    <button type="button" @click="failModalOpen = false" class="flex-1 py-3 rounded-xl border border-gray-200 text-xs font-bold text-gray-600">{{ __('Cancel') }}</button>
                    <button type="submit" :disabled="failSaving" class="flex-1 py-3 rounded-xl bg-red-600 text-white text-xs font-bold shadow-md shadow-red-600/20 disabled:opacity-60">
                        <span x-show="!failSaving">{{ __('Submit') }}</span>
                        <span x-show="failSaving">{{ __('Submitting...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function driverDashboard() {
    return {
        stats: @json($stats),
        failModalOpen: false,
        failId: null,
        failReason: '',
        failSaving: false,

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

        openFail(id) {
            this.failId = id;
            this.failReason = '';
            this.failModalOpen = true;
        },

        async submitFail() {
            this.failSaving = true;
            try {
                const res = await fetch(`{{ url('driver/deliveries') }}/${this.failId}/status`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: new URLSearchParams({ status: 'failed', reason: this.failReason }),
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || '{{ __('Failed to update status.') }}');
                }
            } catch (e) {
                alert('{{ __('Network error. Please try again.') }}');
            } finally {
                this.failSaving = false;
            }
        },
    };
}
</script>
@endpush
