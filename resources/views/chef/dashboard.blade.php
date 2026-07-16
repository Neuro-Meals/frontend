@extends('layouts.chef')

@section('title', __('Chef Dashboard') . ' - ' . __('Nutrio Meals'))

@section('content')
@php
$user = app(\App\Services\Api\AuthApiService::class)->user() ?? [];
$chefName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: ($user['email'] ?? 'Chef');
$today = date('l, M j');
@endphp

<div x-data="chefDashboard()" x-init="init()" class="pb-4">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-chef-700 to-chef-600 text-white p-5 rounded-b-3xl shadow-lg shadow-chef-700/20 animate-slide-up">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs text-white/70 mb-0.5">{{ __('Good day, Chef') }}</p>
                <h1 class="text-lg font-bold">{{ $chefName }}</h1>
                <p class="text-[10px] text-white/60 mt-0.5">{{ $today }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-white/15 flex items-center justify-center border border-white/20">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10 animate-slide-up animate-delay-1">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-2 h-2 rounded-full bg-orange-300 pulse-dot"></div>
                    <span class="text-[10px] text-white/70">{{ __('Total') }}</span>
                </div>
                <p class="text-xl font-bold" x-text="stats.total_today">{{ $stats['total_today'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl p-3 border border-white/10 animate-slide-up animate-delay-2">
                <div class="flex items-center gap-1.5 mb-1">
                    <div class="w-2 h-2 rounded-full bg-amber-300"></div>
                    <span class="text-[10px] text-white/70">{{ __('Pending') }}</span>
                </div>
                <p class="text-xl font-bold" x-text="stats.pending">{{ $stats['pending'] }}</p>
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
        {{-- Secondary KPI Cards --}}
        <div class="grid grid-cols-4 gap-2">
            <div class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 text-center">
                <p class="text-[9px] text-gray-400 mb-0.5">{{ __('Preparing') }}</p>
                <p class="text-lg font-bold text-chef-600">{{ $stats['preparing'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 text-center">
                <p class="text-[9px] text-gray-400 mb-0.5">{{ __('Ready') }}</p>
                <p class="text-lg font-bold text-green-600">{{ $stats['ready'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 text-center">
                <p class="text-[9px] text-gray-400 mb-0.5">{{ __('Drivers') }}</p>
                <p class="text-lg font-bold text-blue-600">{{ $stats['available_drivers'] }}</p>
            </div>
            <div class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 text-center">
                <p class="text-[9px] text-gray-400 mb-0.5">{{ __('Cancelled') }}</p>
                <p class="text-lg font-bold text-red-500">{{ $stats['cancelled'] }}</p>
            </div>
        </div>

        {{-- Status mini bar --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between animate-slide-up animate-delay-2">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-chef-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-chef-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-400">{{ __('Today\'s Schedule') }}</p>
                    <p class="text-sm font-bold text-gray-900">{{ $stats['total_today'] }} {{ __('orders to prepare') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-chef-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-chef-500"></span>
                </span>
                <span class="text-xs font-bold text-chef-600">{{ __('On Duty') }}</span>
            </div>
        </div>

        {{-- Timeframe Tabs --}}
        <div class="bg-white rounded-2xl p-1.5 shadow-sm border border-gray-100 flex animate-slide-up animate-delay-2">
            <button @click="activeTab = 'morning'" :class="activeTab === 'morning' ? 'bg-chef-600 text-white shadow-md shadow-chef-600/20' : 'text-gray-500 hover:bg-gray-50'" class="timeframe-tab flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                {{ __('Morning') }}
                <span x-show="stats.morning > 0" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" :class="activeTab === 'morning' ? 'bg-white/20' : 'bg-gray-100'" x-text="stats.morning">{{ $stats['morning'] }}</span>
            </button>
            <button @click="activeTab = 'noon'" :class="activeTab === 'noon' ? 'bg-chef-600 text-white shadow-md shadow-chef-600/20' : 'text-gray-500 hover:bg-gray-50'" class="timeframe-tab flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                {{ __('Noon') }}
                <span x-show="stats.noon > 0" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" :class="activeTab === 'noon' ? 'bg-white/20' : 'bg-gray-100'" x-text="stats.noon">{{ $stats['noon'] }}</span>
            </button>
            <button @click="activeTab = 'evening'" :class="activeTab === 'evening' ? 'bg-chef-600 text-white shadow-md shadow-chef-600/20' : 'text-gray-500 hover:bg-gray-50'" class="timeframe-tab flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                {{ __('Evening') }}
                <span x-show="stats.evening > 0" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" :class="activeTab === 'evening' ? 'bg-white/20' : 'bg-gray-100'" x-text="stats.evening">{{ $stats['evening'] }}</span>
            </button>
        </div>

        {{-- Morning Meals --}}
        <div x-show="activeTab === 'morning'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h2 class="text-sm font-bold text-gray-900">{{ __('Morning Orders') }}</h2>
                <span class="text-[10px] text-gray-400">{{ $stats['morning'] }} {{ __('orders') }}</span>
            </div>

            @if(count($morningOrders) === 0)
            <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 shadow-sm">
                <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <p class="text-sm text-gray-500">{{ __('No morning orders scheduled.') }}</p>
            </div>
            @endif

            @foreach($morningOrders as $order)
                @include('chef.partials.order-card')
            @endforeach
        </div>

        {{-- Noon Meals --}}
        <div x-show="activeTab === 'noon'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg bg-orange-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <h2 class="text-sm font-bold text-gray-900">{{ __('Noon Orders') }}</h2>
                <span class="text-[10px] text-gray-400">{{ $stats['noon'] }} {{ __('orders') }}</span>
            </div>

            @if(count($noonOrders) === 0)
            <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 shadow-sm">
                <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <p class="text-sm text-gray-500">{{ __('No noon orders scheduled.') }}</p>
            </div>
            @endif

            @foreach($noonOrders as $order)
                @include('chef.partials.order-card')
            @endforeach
        </div>

        {{-- Evening Meals --}}
        <div x-show="activeTab === 'evening'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3">
            <div class="flex items-center gap-2 mb-1">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </div>
                <h2 class="text-sm font-bold text-gray-900">{{ __('Evening Orders') }}</h2>
                <span class="text-[10px] text-gray-400">{{ $stats['evening'] }} {{ __('orders') }}</span>
            </div>

            @if(count($eveningOrders) === 0)
            <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 shadow-sm">
                <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </div>
                <p class="text-sm text-gray-500">{{ __('No evening orders scheduled.') }}</p>
            </div>
            @endif

            @foreach($eveningOrders as $order)
                @include('chef.partials.order-card')
            @endforeach
        </div>

        {{-- Meals Summary --}}
        @if(count($mealsSummary) > 0)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-3">
            <h2 class="text-sm font-bold text-gray-900 mb-3">{{ __('Today\'s Meal Summary') }}</h2>
            <div class="space-y-2">
                @foreach($mealsSummary as $meal)
                <div class="flex items-center justify-between p-2.5 rounded-xl bg-chef-50/30">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-chef-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-chef-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <span class="text-xs font-medium text-gray-900">{{ $meal['meal_name'] ?? 'Unknown' }}</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-chef-600 text-white text-[10px] font-bold">x{{ $meal['quantity'] ?? 1 }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Allergy Alerts --}}
        @if(count($allergyCustomers) > 0)
        <div class="bg-red-50 rounded-2xl p-4 shadow-sm border border-red-100 animate-slide-up animate-delay-3">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-sm font-bold text-red-900">{{ __('Allergy Alerts') }}</h2>
            </div>
            <div class="space-y-2">
                @foreach($allergyCustomers as $customer)
                <div class="flex items-start gap-2 p-2.5 rounded-xl bg-white border border-red-100">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-gray-900">{{ $customer['full_name'] ?? 'Unknown' }}</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($customer['allergies'] ?? [] as $allergy)
                            <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-[9px] font-bold">{{ $allergy }}</span>
                            @endforeach
                        </div>
                        @if(!empty($customer['phone']))
                        <p class="text-[10px] text-gray-400 mt-1">{{ $customer['phone'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Notifications --}}
        @if(count($notifications) > 0)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-3">
            <h2 class="text-sm font-bold text-gray-900 mb-3">{{ __('Notifications') }}</h2>
            <div class="space-y-3">
                @foreach($notifications as $notification)
                <div class="flex items-start gap-3 p-3 rounded-xl {{ $notification['is_read'] ? 'bg-gray-50' : 'bg-chef-50/50 border border-chef-100' }}">
                    <div class="w-8 h-8 rounded-full {{ $notification['is_read'] ? 'bg-gray-100 text-gray-400' : 'bg-chef-100 text-chef-600' }} flex items-center justify-center flex-shrink-0">
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
    </div>
</div>
@endsection

@push('scripts')
<script>
function chefDashboard() {
    return {
        stats: @json($stats),
        activeTab: 'morning',

        init() {
            const saved = localStorage.getItem('chef_active_tab');
            if (saved && ['morning', 'noon', 'evening'].includes(saved)) {
                this.activeTab = saved;
            }
        },
    };
}
</script>
@endpush
