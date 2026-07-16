@extends('layouts.chef')

@section('title', __('Kitchen Shift') . ' - ' . __('Nutrio Meals'))

@section('content')
<div x-data="chefShift()" x-init="init()" x-cloak class="pb-10">

    {{-- ============ HEADER ============ --}}
    <div class="relative bg-gradient-to-br from-brand-700 to-brand-800 text-white px-5 pt-5 pb-9 rounded-b-[2rem] shadow-lg shadow-brand-700/30 overflow-hidden animate-slide-up">
        <div class="absolute inset-0 bg-diamond opacity-[0.06]"></div>

        <div class="relative flex items-center justify-between mb-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-10 h-10 rounded-full bg-white/10 border border-white/15 flex items-center justify-center hover:bg-white/20 transition-colors" title="{{ __('Logout') }}">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>

            <div class="flex flex-col items-center">
                <img src="{{ asset('whitelogo.png') }}" alt="Nutrio Meals" class="h-10 w-auto">
            </div>

            <div class="flex items-center gap-2">
                @include('partials.language_switcher', ['isDark' => true])
                <div class="relative w-10 h-10 rounded-full bg-white/10 border border-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @if(count($notifications) > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-400 pulse-dot"></span>
                    @endif
                </div>
            </div>
        </div>

        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-white/70 text-xs mb-1 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Kitchen') }}
                </p>
                <h1 class="text-2xl font-extrabold" x-text="activeLabel"></h1>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center animate-float">
                <template x-if="activeIcon === 'sunrise'"><svg class="w-7 h-7 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m-4.5 3.5L6 6m9 0l1.5-1.5M4 12H2m20 0h-2M6.343 17.657L4.929 19.071M19.071 19.071l-1.414-1.414M12 18a6 6 0 00-6-6 6 6 0 006 6 6 6 0 006-6 6 6 0 00-6 6z"/></svg></template>
                <template x-if="activeIcon === 'sun'"><svg class="w-7 h-7 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></template>
                <template x-if="activeIcon === 'moon'"><svg class="w-7 h-7 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></template>
                <template x-if="activeIcon === 'cookie'"><svg class="w-7 h-7 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15h18v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3zM3 15l2.5-7.5A2 2 0 017.4 6h9.2a2 2 0 011.9 1.5L21 15M9 15V11M15 15V11"/></svg></template>
                <template x-if="activeIcon === 'dots'"><svg class="w-7 h-7 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg></template>
            </div>
        </div>
    </div>

    <div class="px-4 -mt-4 relative z-10 space-y-4">

        {{-- ============ MEAL-TIME DROPDOWN (slide-down panel) ============ --}}
        <div class="relative animate-slide-up animate-delay-1">
            <button @click="dropdownOpen = !dropdownOpen"
                class="w-full bg-white rounded-2xl p-1.5 shadow-md border border-gray-100 flex items-center justify-between gap-2 transition-all">
                <span class="flex items-center gap-2 px-2 py-1.5">
                    <template x-if="activeIcon === 'sunrise'"><svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m-4.5 3.5L6 6m9 0l1.5-1.5M4 12H2m20 0h-2M6.343 17.657L4.929 19.071M19.071 19.071l-1.414-1.414M12 18a6 6 0 00-6-6 6 6 0 006 6 6 6 0 006-6 6 6 0 00-6 6z"/></svg></template>
                    <template x-if="activeIcon === 'sun'"><svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></template>
                    <template x-if="activeIcon === 'moon'"><svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></template>
                    <template x-if="activeIcon === 'cookie'"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15h18v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3zM3 15l2.5-7.5A2 2 0 017.4 6h9.2a2 2 0 011.9 1.5L21 15M9 15V11M15 15V11"/></svg></template>
                    <template x-if="activeIcon === 'dots'"><svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg></template>
                    <span class="text-sm font-bold text-gray-900" x-text="activeLabel"></span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700" x-text="activeSummary.customers + ' ' + '{{ __('orders') }}'"></span>
                </span>
                <svg class="w-5 h-5 text-gray-400 transition-transform flex-shrink-0 mr-2" :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>

        {{-- Slide-down panel --}}
        <div x-show="dropdownOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="-translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-2 opacity-0"
            @click.outside="dropdownOpen = false"
            class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden z-30"
            style="display: none;">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Select Category') }}</span>
                <button @click="dropdownOpen = false" class="w-6 h-6 rounded-full hover:bg-gray-200 flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="switchTab(cat.id); dropdownOpen = false"
                        class="w-full flex items-center justify-between px-4 py-3.5 text-sm font-semibold hover:bg-brand-50/30 transition-colors"
                        :class="activeTab === cat.id ? 'text-brand-700 bg-brand-50/50' : 'text-gray-700'">
                        <span class="flex items-center gap-3">
                            <span class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                                :class="activeTab === cat.id ? 'bg-brand-100' : 'bg-gray-100'">
                                <template x-if="cat.icon === 'sunrise'"><svg class="w-5 h-5" :class="activeTab === cat.id ? 'text-brand-700' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m-4.5 3.5L6 6m9 0l1.5-1.5M4 12H2m20 0h-2M6.343 17.657L4.929 19.071M19.071 19.071l-1.414-1.414M12 18a6 6 0 00-6-6 6 6 0 006 6 6 6 0 006-6 6 6 0 00-6 6z"/></svg></template>
                                <template x-if="cat.icon === 'sun'"><svg class="w-5 h-5" :class="activeTab === cat.id ? 'text-brand-700' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></template>
                                <template x-if="cat.icon === 'moon'"><svg class="w-5 h-5" :class="activeTab === cat.id ? 'text-brand-700' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></template>
                                <template x-if="cat.icon === 'cookie'"><svg class="w-5 h-5" :class="activeTab === cat.id ? 'text-brand-700' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15h18v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3zM3 15l2.5-7.5A2 2 0 017.4 6h9.2a2 2 0 011.9 1.5L21 15M9 15V11M15 15V11"/></svg></template>
                                <template x-if="cat.icon === 'dots'"><svg class="w-5 h-5" :class="activeTab === cat.id ? 'text-brand-700' : 'text-gray-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg></template>
                            </span>
                            <span x-text="cat.name"></span>
                        </span>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                            :class="cat.count > 0 ? 'bg-brand-50 text-brand-700' : 'bg-gray-100 text-gray-400'"
                            x-text="cat.count"></span>
                    </button>
                </template>
            </div>
        </div>

        {{-- ============ STAT CARDS ============ --}}
        <div class="grid grid-cols-2 gap-3 animate-slide-up animate-delay-2">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
                <div class="w-11 h-11 rounded-full bg-brand-50 flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 10-3-6.65"/></svg>
                </div>
                <p class="text-2xl font-extrabold text-gray-900" x-text="activeSummary.customers"></p>
                <p class="text-xs text-gray-400 font-semibold">{{ __('Customers') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
                <div class="w-11 h-11 rounded-full bg-brand-50 flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="text-2xl font-extrabold text-gray-900" x-text="activeSummary.total_meals"></p>
                <p class="text-xs text-gray-400 font-semibold">{{ __('Total Meals') }}</p>
            </div>
        </div>

        {{-- ============ KITCHEN QUEUE (item-level, per schedule) ============ --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-3">
            <div class="flex items-center justify-between mb-3 gap-2">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">{{ __('Kitchen Queue') }}</h2>
                    <p class="text-[10px] text-gray-400">{{ __('Only items for this schedule are transferred — not whole orders.') }}</p>
                </div>
                <button x-show="activeScheduleStats.pending > 0" @click="transferActiveSchedule()" :disabled="transferring"
                    class="btn-action px-3 py-2 rounded-xl bg-brand-700 text-white text-[11px] font-bold flex items-center gap-1.5 flex-shrink-0 disabled:opacity-50">
                    <svg x-show="!transferring" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    <svg x-show="transferring" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                    <span x-text="strings.transferLabel + ' (' + activeScheduleStats.pending + ')'"></span>
                </button>
            </div>

            <template x-if="activeProductionMeals.length === 0">
                <p class="text-xs text-gray-400 text-center py-4">{{ __('No items for this shift yet.') }}</p>
            </template>

            <div class="space-y-2">
                <template x-for="meal in activeProductionMeals" :key="meal.meal_id ?? meal.meal_name">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/40 overflow-hidden">
                        <button @click="toggleMeal(meal)" type="button" class="w-full p-3 text-left">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-9 h-9 rounded-lg bg-white border border-gray-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    <img x-show="meal.image_url" :src="meal.image_url" class="w-full h-full object-cover" alt="">
                                    <svg x-show="!meal.image_url" class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-sm font-bold text-gray-800 truncate block" x-text="meal.meal_name"></span>
                                    <span class="text-[10px] text-gray-400" x-text="(meal.customers?.length ?? 0) + ' {{ __('customers') }}'"></span>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-brand-700 text-white text-[11px] font-bold flex-shrink-0" x-text="'×' + meal.total_required"></span>
                                <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" :class="isMealOpen(meal) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span x-show="meal.pending" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-gray-200 text-gray-600" x-text="meal.pending + ' {{ __('pending') }}'"></span>
                                <span x-show="meal.sent_to_kitchen" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-amber-100 text-amber-700" x-text="meal.sent_to_kitchen + ' {{ __('sent') }}'"></span>
                                <span x-show="meal.preparing" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-blue-100 text-blue-700" x-text="meal.preparing + ' {{ __('preparing') }}'"></span>
                                <span x-show="meal.ready" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-green-100 text-green-700" x-text="meal.ready + ' {{ __('ready') }}'"></span>
                                <span x-show="meal.served" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-emerald-100 text-emerald-700" x-text="meal.served + ' {{ __('served') }}'"></span>
                            </div>
                        </button>

                        <div x-show="isMealOpen(meal)"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="px-3 pb-3" style="display: none;">
                            <div x-show="meal.ingredients?.length" class="flex flex-wrap items-center gap-1 mb-2">
                                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Ingredients') }}:</span>
                                <template x-for="ing in meal.ingredients" :key="ing">
                                    <span class="px-1.5 py-0.5 rounded-full bg-white border border-gray-200 text-[10px] text-gray-600" x-text="ing"></span>
                                </template>
                            </div>
                            <div x-show="meal.allergens?.length" class="flex flex-wrap items-center gap-1 mb-2">
                                <span class="text-[9px] font-bold text-red-400 uppercase tracking-wide">{{ __('Allergens') }}:</span>
                                <template x-for="a in meal.allergens" :key="a">
                                    <span class="px-1.5 py-0.5 rounded-full bg-red-50 border border-red-100 text-[10px] text-red-600" x-text="a"></span>
                                </template>
                            </div>

                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide mb-1.5">
                                {{ __('Customers') }} (<span x-text="meal.customers?.length ?? 0"></span>)
                            </p>
                            <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1 mb-3">
                                <template x-for="c in meal.customers" :key="c.order_id">
                                    <div class="flex items-center justify-between gap-2 bg-white rounded-lg px-2.5 py-1.5 border border-gray-100">
                                        <div class="min-w-0">
                                            <p class="text-[11px] font-bold text-gray-800 truncate" x-text="c.customer_name"></p>
                                            <p class="text-[9px] text-gray-400 truncate" x-text="(c.order_number || ('#' + c.order_id)) + (c.address ? (' · ' + c.address) : '')"></p>
                                        </div>
                                        <div class="flex items-center gap-1.5 flex-shrink-0">
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full capitalize" :class="itemStatusClass(c.item_status)" x-text="c.item_status?.replaceAll('_',' ')"></span>
                                            <span class="text-[11px] font-bold text-gray-700" x-text="'×' + c.quantity"></span>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="!meal.customers?.length" class="text-[11px] text-gray-400 text-center py-2">{{ __('No customers found.') }}</div>
                            </div>

                            <div class="flex gap-2">
                                <button x-show="meal.sent_to_kitchen > 0" @click="advanceMeal(meal, 'start_preparing')" class="btn-action flex-1 py-2 rounded-lg bg-blue-600 text-white text-[11px] font-bold">{{ __('Start Preparing') }}</button>
                                <button x-show="meal.preparing > 0" @click="advanceMeal(meal, 'mark_ready')" class="btn-action flex-1 py-2 rounded-lg bg-green-600 text-white text-[11px] font-bold">{{ __('Mark Ready') }}</button>
                                <button x-show="meal.ready > 0" @click="advanceMeal(meal, 'mark_served')" class="btn-action flex-1 py-2 rounded-lg bg-emerald-700 text-white text-[11px] font-bold">{{ __('Mark Served') }}</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ============ PROGRESS + REMAINING ============ --}}
        <div class="grid grid-cols-2 gap-3 animate-slide-up animate-delay-4">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <p class="text-[10px] text-gray-400 font-semibold mb-2">{{ __('Preparation Progress') }}</p>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-lg font-extrabold text-brand-700" x-text="progressPercent + '%'"></span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="progress-fill h-full bg-gradient-to-r from-brand-600 to-brand-700 rounded-full" :style="'width:' + progressPercent + '%'"></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                <p class="text-[10px] text-gray-400 font-semibold mb-2">{{ __('Remaining to Prepare') }}</p>
                <p class="text-lg font-extrabold text-gray-900"><span x-text="activeSummary.pending + activeSummary.preparing"></span> <span class="text-xs font-semibold text-gray-400">{{ __('of') }} <span x-text="activeSummary.customers"></span></span></p>
            </div>
        </div>

        {{-- ============ OPEN PREP LIST BUTTON ============ --}}
        <button @click="openWalkthrough()" class="btn-action w-full flex items-center justify-between gap-3 bg-gradient-to-l from-brand-700 to-brand-600 text-white rounded-2xl p-4 shadow-md shadow-brand-700/20 animate-slide-up animate-delay-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div class="text-right">
                    <p class="text-sm font-bold">{{ __('Detailed Prep List') }}</p>
                    <p class="text-[10px] text-white/70">{{ __('Tap to view preparation steps for each item') }}</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" transform="rotate(180 12 12)"/></svg>
        </button>

        {{-- ============ ALLERGY ALERTS ============ --}}
        @if(count($allergyCustomers) > 0)
        <div class="bg-red-50 rounded-2xl p-4 shadow-sm border border-red-100 animate-slide-up">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-sm font-bold text-red-900">{{ __('Allergy Alerts') }}</h2>
            </div>
            <div class="space-y-2">
                @foreach($allergyCustomers as $customer)
                <div class="flex items-start justify-between gap-2 p-2.5 rounded-xl bg-white border border-red-100">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-gray-900">{{ $customer['full_name'] ?? __('Unknown') }}</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($customer['allergies'] ?? [] as $allergy)
                            <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-[9px] font-bold">{{ $allergy }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ============ NOTIFICATIONS ============ --}}
        @if(count($notifications) > 0)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up">
            <h2 class="text-sm font-bold text-gray-900 mb-3">{{ __('Notifications') }}</h2>
            <div class="space-y-3">
                @foreach($notifications as $notification)
                <div class="flex items-start gap-3 p-3 rounded-xl {{ $notification['is_read'] ? 'bg-gray-50' : 'bg-brand-50 border border-brand-100' }}">
                    <div class="w-8 h-8 rounded-full {{ $notification['is_read'] ? 'bg-gray-100 text-gray-400' : 'bg-brand-700 text-white' }} flex items-center justify-center flex-shrink-0">
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

    {{-- ============ PREP WALKTHROUGH OVERLAY ============ --}}
    <div x-show="walkthrough.open" x-cloak class="fixed inset-0 z-50 bg-brand-50 overflow-y-auto" x-transition:enter="animate-fade-in">
        <div class="max-w-3xl mx-auto px-4 pt-5 pb-10">

            {{-- Overlay top bar --}}
            <div class="flex items-center gap-3 mb-4 animate-slide-up">
                <button @click="closeWalkthrough()" class="w-10 h-10 rounded-full bg-white shadow-md flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" transform="rotate(180 12 12)"/></svg>
                </button>
                <div class="flex-1 bg-white rounded-2xl px-4 py-2.5 shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-900"><span x-text="activeLabel"></span> · {{ __('Prep List') }}</p>
                    <template x-if="!walkthroughDone">
                        <p class="text-[10px] text-gray-400">{{ __('Order') }} <span x-text="walkthrough.index + 1"></span> {{ __('of') }} <span x-text="activeOrders.length"></span> · {{ __('Remaining') }} <span x-text="activeOrders.length - walkthrough.index - 1"></span></p>
                    </template>
                </div>
            </div>

            {{-- Progress bar --}}
            <template x-if="!walkthroughDone">
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden mb-5 animate-slide-up animate-delay-1">
                    <div class="progress-fill h-full bg-gradient-to-r from-brand-600 to-brand-700 rounded-full" :style="'width:' + walkthroughPercent + '%'"></div>
                </div>
            </template>

            {{-- Empty state --}}
            <template x-if="activeOrders.length === 0">
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm animate-slide-up">
                    <p class="text-sm text-gray-500">{{ __('No orders in this shift.') }}</p>
                </div>
            </template>

            {{-- Completed celebration --}}
            <template x-if="activeOrders.length > 0 && walkthroughDone">
                <div class="bg-white rounded-3xl p-8 text-center shadow-lg border border-gray-100 animate-pop-in">
                    <div class="w-20 h-20 rounded-full bg-brand-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-xl font-extrabold text-brand-700 mb-1">{{ __('Well done!') }} 🎉</h2>
                    <p class="text-sm text-gray-500 mb-6">{{ __('All meals for this shift have been prepared successfully.') }}</p>
                    <div class="grid grid-cols-3 gap-3 mb-6">
                        <div class="bg-brand-50 rounded-xl p-3">
                            <p class="text-lg font-extrabold text-gray-900" x-text="activeSummary.customers"></p>
                            <p class="text-[10px] text-gray-500">{{ __('Total') }}</p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-3">
                            <p class="text-lg font-extrabold text-green-600" x-text="readyCount"></p>
                            <p class="text-[10px] text-gray-500">{{ __('Ready') }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-3">
                            <p class="text-lg font-extrabold text-gray-400">0</p>
                            <p class="text-[10px] text-gray-500">{{ __('Remaining') }}</p>
                        </div>
                    </div>
                    <button @click="closeWalkthrough()" class="btn-action w-full py-3 rounded-xl bg-brand-700 text-white text-sm font-bold shadow-md">{{ __('Close') }}</button>
                </div>
            </template>

            {{-- Current order prep card --}}
            <template x-if="currentOrder">
                <div class="space-y-4 animate-pop-in" :key="currentOrder.id">
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-start justify-between mb-3">
                            <div class="min-w-0">
                                <p class="text-base font-extrabold text-gray-900 truncate" x-text="currentOrder.customer"></p>
                                <p class="text-[11px] text-gray-400 mt-0.5">#<span x-text="currentOrder.id"></span> &nbsp;|&nbsp; <span x-text="currentOrder.order_number"></span></p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-brand-50 flex items-center justify-center text-brand-700 font-bold flex-shrink-0" x-text="currentOrder.customer.charAt(0)"></div>
                        </div>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs text-gray-500">
                            <span class="flex items-center gap-1" x-show="currentOrder.delivery_address">
                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span x-text="currentOrder.delivery_address"></span>
                            </span>
                            <span class="flex items-center gap-1" x-show="currentOrder.customer_phone">
                                <svg class="w-3.5 h-3.5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span x-text="currentOrder.customer_phone"></span>
                            </span>
                        </div>
                        <template x-if="currentOrder.delivery_notes">
                            <div class="flex items-start gap-2 mt-3 bg-amber-50 p-2.5 rounded-xl border border-amber-100">
                                <svg class="w-4 h-4 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <p class="text-xs text-amber-800 italic" x-text="currentOrder.delivery_notes"></p>
                            </div>
                        </template>
                    </div>

                    {{-- Items to prepare --}}
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Items to Prepare') }}</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <template x-for="(item, idx) in currentOrder.items" :key="idx">
                                <div class="bg-brand-50/60 rounded-xl p-3 border border-brand-100 text-center">
                                    <div class="w-9 h-9 rounded-full bg-white flex items-center justify-center mx-auto mb-2 shadow-sm">
                                        <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <p class="text-xs font-bold text-gray-800 leading-tight" x-text="item.meal_name || item.name || strings.item"></p>
                                    <p class="text-[11px] text-brand-700 font-semibold mt-0.5" x-text="'× ' + (item.quantity || 1)"></p>
                                </div>
                            </template>
                            <template x-if="!currentOrder.items || currentOrder.items.length === 0">
                                <p class="col-span-2 text-xs text-gray-400 text-center py-2">{{ __('No item details for this order.') }}</p>
                            </template>
                        </div>
                    </div>

                    {{-- Status + Actions --}}
                    <div class="flex items-center justify-center gap-2">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold" :class="statusBadgeClass(currentOrder.status)" x-text="currentOrder.status_label"></span>
                    </div>

                    <div class="grid grid-cols-1 gap-2">
                        <template x-if="['pending','confirmed','scheduled'].includes(currentOrder.status)">
                            <button @click="doStartPreparing()" class="btn-action w-full py-3.5 rounded-2xl bg-gradient-to-l from-brand-700 to-brand-600 text-white text-sm font-bold shadow-md shadow-brand-700/20 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                {{ __('Start Preparing') }}
                            </button>
                        </template>
                        <template x-if="currentOrder.status === 'preparing'">
                            <button @click="doMarkReady()" class="btn-action w-full py-3.5 rounded-2xl bg-green-600 text-white text-sm font-bold shadow-md shadow-green-600/20 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Mark as Ready') }}
                            </button>
                        </template>
                        <template x-if="['ready_for_delivery','out_for_delivery','delivered'].includes(currentOrder.status)">
                            <button @click="goNext()" class="btn-action w-full py-3.5 rounded-2xl bg-gray-100 text-gray-700 text-sm font-bold flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ __('Already Ready') }} — {{ __('Next') }}
                            </button>
                        </template>
                    </div>

                    <p class="text-center text-[10px] text-gray-400">{{ __('Tap after weighing and packing to move to the next customer') }}</p>

                    {{-- Prev / Next --}}
                    <div class="flex items-center justify-between pt-2">
                        <button @click="goPrev()" :disabled="walkthrough.index === 0" class="flex items-center gap-1 text-xs font-bold text-gray-500 disabled:opacity-30 disabled:cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            {{ __('Previous') }}
                        </button>
                        <button @click="goNext()" class="flex items-center gap-1 text-xs font-bold text-brand-700">
                            {{ __('Next') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" transform="rotate(180 12 12)"/></svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function chefShift() {
    return {
        categories: @json($categories),
        tabSummaries: @json($tabSummaries),
        ordersByTab: @json($categorizedOrders),
        scheduleByTab: @json($scheduleByTab),
        scheduleDate: @json($today),
        activeTab: '',
        dropdownOpen: false,
        transferring: false,
        openMeals: [],
        walkthrough: { open: false, index: 0 },

        strings: {
            kitchenShift: @json(__('Kitchen Shift')),
            item: @json(__('Item')),
            preparingLabel: @json(__('Preparing')),
            readyForDeliveryLabel: @json(__('Ready for Delivery')),
            startPrepTitle: @json(__('Start preparing this order?')),
            startPrepText: @json(__('This will mark the order as being prepared in the kitchen.')),
            yesStart: @json(__('Yes, Start')),
            readyTitle: @json(__('Meal ready?')),
            readyText: @json(__('Make sure all items are weighed and packed before continuing.')),
            yesReady: @json(__('Yes, Ready')),
            transferLabel: @json(__('Transfer to Kitchen')),
            transferTitle: @json(__('Transfer to Kitchen?')),
            transferText: @json(__('This sends only the items in this schedule to the kitchen. The order stays active until every schedule is completed.')),
            yesTransfer: @json(__('Yes, transfer')),
            cancel: @json(__('Cancel')),
            errorTitle: @json(__('Error')),
            updated: @json(__('Updated')),
        },

        init() {
            if (this.categories.length > 0) {
                this.activeTab = this.autoSelectCategory();
            }
        },

        autoSelectCategory() {
            const hour = new Date().getHours();
            // 5:00–10:59 → breakfast, 11:00–15:59 → lunch, 16:00–21:59 → dinner, else → snacks/other
            let preferredIcon;
            if (hour >= 5 && hour < 11) preferredIcon = 'sunrise';
            else if (hour >= 11 && hour < 16) preferredIcon = 'sun';
            else if (hour >= 16 && hour < 22) preferredIcon = 'moon';
            else preferredIcon = 'cookie';

            // Try to find a category matching the preferred meal time
            const byIcon = this.categories.find(c => c.icon === preferredIcon);
            if (byIcon) return byIcon.id;

            // Fallback: first category with orders
            const withOrders = this.categories.find(c => c.count > 0);
            if (withOrders) return withOrders.id;

            // Final fallback: first category
            return this.categories[0].id;
        },

        switchTab(id) {
            this.activeTab = id;
        },

        get activeCategory() {
            return this.categories.find(c => c.id === this.activeTab) || null;
        },

        get activeLabel() {
            return this.activeCategory ? this.activeCategory.name : this.strings.kitchenShift;
        },

        get activeIcon() {
            return this.activeCategory ? this.activeCategory.icon : 'dot';
        },

        get activeSummary() {
            return this.tabSummaries[this.activeTab] || { customers: 0, total_meals: 0, ready: 0, preparing: 0, pending: 0, dishes: [] };
        },

        get activeOrders() {
            return this.ordersByTab[this.activeTab] || [];
        },

        get activeSchedule() {
            return this.scheduleByTab[this.activeTab] || { stats: {}, production: { meals: [] }, kitchen_queue: { meals: [] } };
        },

        get activeScheduleStats() {
            return this.activeSchedule.stats || { pending: 0, sent_to_kitchen: 0, preparing: 0, ready: 0, served: 0 };
        },

        get activeProductionMeals() {
            return this.activeSchedule.production?.meals || [];
        },

        mealKey(meal) {
            return meal.meal_id ?? meal.meal_name;
        },

        toggleMeal(meal) {
            const key = this.mealKey(meal);
            const idx = this.openMeals.indexOf(key);
            if (idx === -1) this.openMeals.push(key);
            else this.openMeals.splice(idx, 1);
        },

        isMealOpen(meal) {
            return this.openMeals.includes(this.mealKey(meal));
        },

        itemStatusClass(status) {
            return {
                'bg-gray-100 text-gray-500': status === 'pending',
                'bg-amber-100 text-amber-700': status === 'sent_to_kitchen',
                'bg-blue-100 text-blue-700': status === 'preparing',
                'bg-green-100 text-green-700': status === 'ready',
                'bg-emerald-100 text-emerald-700': status === 'served',
            };
        },

        get progressPercent() {
            const s = this.activeSummary;
            if (!s.customers) return 0;
            return Math.round((s.ready / s.customers) * 100);
        },

        get readyCount() {
            return this.activeOrders.filter(o => ['ready_for_delivery', 'out_for_delivery', 'delivered'].includes(o.status)).length;
        },

        openWalkthrough() {
            const firstPending = this.activeOrders.findIndex(o => !['ready_for_delivery', 'out_for_delivery', 'delivered'].includes(o.status));
            this.walkthrough.index = firstPending === -1 ? 0 : firstPending;
            this.walkthrough.open = true;
        },

        closeWalkthrough() {
            this.walkthrough.open = false;
        },

        get walkthroughDone() {
            return this.activeOrders.length > 0 && this.walkthrough.index >= this.activeOrders.length;
        },

        get walkthroughPercent() {
            if (this.activeOrders.length === 0) return 0;
            return Math.round((this.walkthrough.index / this.activeOrders.length) * 100);
        },

        get currentOrder() {
            if (this.walkthroughDone) return null;
            return this.activeOrders[this.walkthrough.index] || null;
        },

        goNext() {
            if (this.walkthrough.index < this.activeOrders.length) this.walkthrough.index++;
        },

        goPrev() {
            if (this.walkthrough.index > 0) this.walkthrough.index--;
        },

        statusBadgeClass(status) {
            return {
                'bg-blue-50 text-blue-700': ['pending', 'confirmed', 'scheduled'].includes(status),
                'bg-amber-50 text-amber-700': status === 'preparing',
                'bg-green-50 text-green-700': ['ready_for_delivery', 'out_for_delivery', 'delivered'].includes(status),
            };
        },

        async postJson(url, body) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify(body),
            });
            return res.json();
        },

        async transferActiveSchedule() {
            const confirmed = await Swal.fire({
                title: this.strings.transferTitle,
                text: this.strings.transferText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#173327',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: this.strings.yesTransfer,
                cancelButtonText: this.strings.cancel,
                reverseButtons: true,
                customClass: { popup: 'rounded-2xl' },
            });
            if (!confirmed.isConfirmed) return;

            this.transferring = true;
            try {
                const data = await this.postJson(`{{ url('chef/schedule/transfer') }}`, {
                    category_id: this.activeTab,
                    date: this.scheduleDate,
                });
                if (data.success) {
                    await this.reloadSchedule();
                } else {
                    Swal.fire({ title: this.strings.errorTitle, text: data.message, icon: 'error', customClass: { popup: 'rounded-2xl' } });
                }
            } finally {
                this.transferring = false;
            }
        },

        async advanceMeal(meal, action) {
            const data = await this.postJson(`{{ url('chef/schedule/advance') }}`, {
                category_id: this.activeTab,
                action: action,
                meal_id: meal.meal_id || null,
                date: this.scheduleDate,
            });
            if (data.success) {
                await this.reloadSchedule();
            } else {
                Swal.fire({ title: this.strings.errorTitle, text: data.message, icon: 'error', customClass: { popup: 'rounded-2xl' } });
            }
        },

        async reloadSchedule() {
            // Full reload keeps the server-rendered schedule/production
            // data (and every other tab's counts) in sync after a
            // transfer or status change.
            window.location.reload();
        },

        async doStartPreparing() {
            const order = this.currentOrder;
            if (!order) return;
            const ok = await chefAction(`{{ url('chef/orders') }}/${order.id}/start-preparing`, {
                title: this.strings.startPrepTitle,
                text: this.strings.startPrepText,
                confirmText: this.strings.yesStart,
                icon: 'question',
            });
            if (ok) {
                order.status = 'preparing';
                order.status_label = this.strings.preparingLabel;
            }
        },

        async doMarkReady() {
            const order = this.currentOrder;
            if (!order) return;
            const ok = await chefAction(`{{ url('chef/orders') }}/${order.id}/mark-ready`, {
                title: this.strings.readyTitle,
                text: this.strings.readyText,
                confirmText: this.strings.yesReady,
                icon: 'success',
                confirmColor: '#16a34a',
            });
            if (ok) {
                order.status = 'ready_for_delivery';
                order.status_label = this.strings.readyForDeliveryLabel;
                this.activeSummary.ready++;
                setTimeout(() => this.goNext(), 350);
            }
        },
    };
}
</script>
@endpush
