@extends('layouts.chef')

@section('title', __('Chef Dashboard') . ' - ' . __('Nutrio Meals'))

@section('content')
@php
$user = app(\App\Services\Api\AuthApiService::class)->user() ?? [];
$chefName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: ($user['email'] ?? 'Chef');
$today = date('l, M j');
@endphp

<div x-data="chefDashboard()" x-init="init()" x-cloak class="pb-4">
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

        {{-- Tab Navigation --}}
        <div class="bg-white rounded-2xl p-1.5 shadow-sm border border-gray-100 flex gap-1">
            <button @click="switchTab('morning')"
                :class="activeTab === 'morning' ? 'bg-chef-600 text-white shadow-md shadow-chef-600/20' : 'text-gray-500 hover:bg-gray-50'"
                class="flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                {{ __('Morning') }}
                <span x-show="stats.morning > 0" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" :class="activeTab === 'morning' ? 'bg-white/20' : 'bg-gray-100'" x-text="stats.morning">{{ $stats['morning'] }}</span>
            </button>
            <button @click="switchTab('noon')"
                :class="activeTab === 'noon' ? 'bg-chef-600 text-white shadow-md shadow-chef-600/20' : 'text-gray-500 hover:bg-gray-50'"
                class="flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                {{ __('Noon') }}
                <span x-show="stats.noon > 0" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" :class="activeTab === 'noon' ? 'bg-white/20' : 'bg-gray-100'" x-text="stats.noon">{{ $stats['noon'] }}</span>
            </button>
            <button @click="switchTab('evening')"
                :class="activeTab === 'evening' ? 'bg-chef-600 text-white shadow-md shadow-chef-600/20' : 'text-gray-500 hover:bg-gray-50'"
                class="flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                {{ __('Evening') }}
                <span x-show="stats.evening > 0" class="px-1.5 py-0.5 rounded-full text-[9px] font-bold" :class="activeTab === 'evening' ? 'bg-white/20' : 'bg-gray-100'" x-text="stats.evening">{{ $stats['evening'] }}</span>
            </button>
            <button @click="switchTab('summary')"
                :class="activeTab === 'summary' ? 'bg-chef-600 text-white shadow-md shadow-chef-600/20' : 'text-gray-500 hover:bg-gray-50'"
                class="flex-1 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                {{ __('Summary') }}
            </button>
        </div>

        {{-- Orders by Tab (Alpine filtered) --}}
        <template x-if="activeTab !== 'summary'">
            <div class="space-y-3">
                {{-- Section header --}}
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                         :class="{
                            'bg-amber-50': activeTab === 'morning',
                            'bg-orange-50': activeTab === 'noon',
                            'bg-indigo-50': activeTab === 'evening'
                         }">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             :class="{
                                'text-amber-500': activeTab === 'morning',
                                'text-orange-500': activeTab === 'noon',
                                'text-indigo-500': activeTab === 'evening'
                             }">
                            <path x-show="activeTab !== 'evening'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M3 12h1m15.364-6.364l.707.707M4.929 4.929l.707.707m12.728 0l.707-.707M4.929 19.071l.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            <path x-show="activeTab === 'evening'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-gray-900">
                        <span x-text="activeTab === 'morning' ? '{{ __('Morning Orders') }}' : activeTab === 'noon' ? '{{ __('Noon Orders') }}' : '{{ __('Evening Orders') }}'"></span>
                    </h2>
                    <span class="text-[10px] text-gray-400" x-text="filteredOrders.length + ' {{ __('orders') }}'"></span>
                </div>

                {{-- Empty state --}}
                <template x-if="filteredOrders.length === 0">
                    <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 shadow-sm">
                        <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-sm text-gray-500" x-text="'{{ __('No') }} ' + activeTab + ' {{ __('orders scheduled.') }}'"></p>
                    </div>
                </template>

                {{-- Order cards rendered by Alpine --}}
                <template x-for="order in filteredOrders" :key="order.id">
                    <div class="meal-card bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-gray-900 truncate" x-text="order.order_number"></p>
                                <p class="text-[10px] text-gray-400 mt-0.5" x-text="order.customer + ' · ' + order.time"></p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-[10px] font-semibold border flex-shrink-0 ml-2"
                                  x-text="order.status_label"
                                  :class="{
                                    'bg-indigo-50 text-indigo-700 border-indigo-200': order.status === 'scheduled',
                                    'bg-blue-50 text-blue-700 border-blue-200': order.status === 'pending',
                                    'bg-cyan-50 text-cyan-700 border-cyan-200': order.status === 'confirmed',
                                    'bg-amber-50 text-amber-700 border-amber-200': order.status === 'preparing',
                                    'bg-green-50 text-green-700 border-green-200': order.status === 'ready_for_delivery',
                                    'bg-purple-50 text-purple-700 border-purple-200': order.status === 'out_for_delivery',
                                    'bg-gray-50 text-gray-500 border-gray-200': order.status === 'delivered',
                                    'bg-red-50 text-red-600 border-red-200': order.status === 'cancelled'
                                  }"></span>
                        </div>

                        <div class="flex items-start gap-2 mb-2">
                            <svg class="w-4 h-4 text-chef-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="text-xs text-gray-700 leading-relaxed flex-1" x-text="order.meal_summary"></p>
                        </div>

                        <div class="flex items-center gap-3 mb-2 flex-wrap">
                            <div class="flex items-center gap-1.5" x-show="order.meal_count > 0">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                <span class="text-[10px] font-semibold text-gray-600" x-text="order.meal_count + ' {{ __('items') }}'"></span>
                            </div>
                            <div class="flex items-center gap-1.5" x-show="order.total_calories > 0">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                <span class="text-[10px] font-semibold text-gray-600" x-text="order.total_calories + ' kcal'"></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-[10px] font-semibold text-gray-600" x-text="Number(order.total_amount).toFixed(2) + ' SAR'"></span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2 mb-2" x-show="order.delivery_address">
                            <svg class="w-3.5 h-3.5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <p class="text-[10px] text-gray-500 leading-relaxed flex-1" x-text="order.delivery_address"></p>
                        </div>

                        <div class="flex items-start gap-2 mb-3 bg-amber-50 p-2 rounded-lg border border-amber-100" x-show="order.delivery_notes">
                            <svg class="w-3.5 h-3.5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <p class="text-[10px] text-amber-800 leading-tight flex-1 italic" x-text="order.delivery_notes"></p>
                        </div>

                        {{-- Action buttons --}}
                        <template x-if="!['delivered','cancelled','out_for_delivery','scheduled'].includes(order.status)">
                            <div class="grid grid-cols-1 gap-2 mt-3">
                                <template x-if="['pending','confirmed'].includes(order.status)">
                                    <button type="button"
                                        @click="startPreparing(order.id)"
                                        class="btn-action w-full py-2.5 rounded-xl bg-chef-600 text-white text-xs font-bold shadow-md shadow-chef-600/20 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        {{ __('Start Preparing') }}
                                    </button>
                                </template>
                                <template x-if="order.status === 'preparing'">
                                    <button type="button"
                                        @click="markReady(order.id)"
                                        class="btn-action w-full py-2.5 rounded-xl bg-green-600 text-white text-xs font-bold shadow-md shadow-green-600/20 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ __('Mark as Ready') }}
                                    </button>
                                </template>
                                <template x-if="order.status === 'ready_for_delivery'">
                                    <div class="flex items-center justify-center gap-1.5 py-2 rounded-xl bg-green-50 border border-green-100">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[10px] font-bold text-green-600">{{ __('Ready for delivery') }}</span>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <div class="flex items-center justify-center gap-1.5 mt-2 pt-3 border-t border-gray-50" x-show="order.status === 'delivered'">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-[10px] font-bold text-gray-400">{{ __('Order delivered') }}</span>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- Summary Tab --}}
        <template x-if="activeTab === 'summary'">
            <div class="space-y-4">

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
        </template>
    </div>
</div>
@endsection

@push('scripts')
<script>
function chefDashboard() {
    return {
        stats: @json($stats),
        allOrders: @json(array_merge($morningOrders, $noonOrders, $eveningOrders)),
        activeTab: 'morning',
        loading: false,

        init() {
            const saved = localStorage.getItem('chef_active_tab');
            if (saved && ['morning', 'noon', 'evening', 'summary'].includes(saved)) {
                this.activeTab = saved;
            }
        },

        switchTab(tab) {
            this.activeTab = tab;
            localStorage.setItem('chef_active_tab', tab);
        },

        get filteredOrders() {
            return this.allOrders.filter(o => o.timeframe === this.activeTab);
        },

        async startPreparing(orderId) {
            if (this.loading) return;
            this.loading = true;
            try {
                const res = await fetch('{{ route('chef.orders.start_preparing', '__ID__') }}'.replace('__ID__', orderId), {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                if (data.success) {
                    const order = this.allOrders.find(o => o.id === orderId);
                    if (order) {
                        order.status = 'preparing';
                        order.status_label = '{{ __('Preparing') }}';
                    }
                } else {
                    alert(data.message || '{{ __('Failed to start preparation.') }}');
                }
            } catch (e) {
                alert('{{ __('Network error. Please try again.') }}');
            } finally {
                this.loading = false;
            }
        },

        async markReady(orderId) {
            if (this.loading) return;
            this.loading = true;
            try {
                const res = await fetch('{{ route('chef.orders.mark_ready', '__ID__') }}'.replace('__ID__', orderId), {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                if (data.success) {
                    const order = this.allOrders.find(o => o.id === orderId);
                    if (order) {
                        order.status = 'ready_for_delivery';
                        order.status_label = '{{ __('Ready for Delivery') }}';
                    }
                } else {
                    alert(data.message || '{{ __('Failed to mark as ready.') }}');
                }
            } catch (e) {
                alert('{{ __('Network error. Please try again.') }}');
            } finally {
                this.loading = false;
            }
        },
    };
}
</script>
@endpush
