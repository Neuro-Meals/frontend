@extends('layouts.chef')

@section('title', __('Kitchen Schedule') . ' - ' . __('Nutrio Meals'))

@section('content')
<div x-data="chefSchedule()" x-init="init()" x-cloak class="pb-10">

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
                <div class="w-10 h-10 rounded-full bg-white/10 border border-white/15 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>

        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-white/70 text-xs mb-1 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('Kitchen Schedule') }}
                </p>
                <h1 class="text-2xl font-extrabold" x-text="headerDate"></h1>
                <div class="flex items-center gap-2 mt-2">
                    <button @click="setDate(todayStr())"
                        :class="scheduleDate === todayStr() ? 'bg-white/20 text-white' : 'bg-white/10 text-white/70'"
                        class="px-3 py-1 rounded-lg text-[10px] font-bold transition-all">{{ __('Today') }}</button>
                    <button @click="setDate(tomorrowStr())"
                        :class="scheduleDate === tomorrowStr() ? 'bg-white/20 text-white' : 'bg-white/10 text-white/70'"
                        class="px-3 py-1 rounded-lg text-[10px] font-bold transition-all">{{ __('Tomorrow') }}</button>
                </div>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center animate-float">
                <svg class="w-7 h-7 text-brand-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </div>

    <div class="px-4 -mt-4 relative z-10 space-y-4">

        {{-- ============ SUMMARY STAT CARDS ============ --}}
        <div class="grid grid-cols-2 gap-3 animate-slide-up animate-delay-1">
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
                <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <p class="text-2xl font-extrabold text-gray-900" x-text="summary.total_orders ?? 0"></p>
                <p class="text-xs text-gray-400 font-semibold">{{ __('Customer Orders') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
                <div class="w-11 h-11 rounded-full bg-purple-50 flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
                <p class="text-2xl font-extrabold text-gray-900" x-text="summary.category_count ?? 0"></p>
                <p class="text-xs text-gray-400 font-semibold">{{ __('Categories') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
                <div class="w-11 h-11 rounded-full bg-amber-50 flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <p class="text-2xl font-extrabold text-gray-900" x-text="summary.distinct_meals ?? 0"></p>
                <p class="text-xs text-gray-400 font-semibold">{{ __('Distinct Meals') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
                <div class="w-11 h-11 rounded-full bg-brand-50 flex items-center justify-center mb-2">
                    <svg class="w-5 h-5 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <p class="text-2xl font-extrabold text-brand-700" x-text="summary.total_portions ?? 0"></p>
                <p class="text-xs text-gray-400 font-semibold">{{ __('Total Portions') }}</p>
            </div>
        </div>

        {{-- ============ PRODUCTION SUMMARY ============ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up animate-delay-2" x-show="allProduction.length">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Production Summary') }}</h3>
            </div>
            <div class="divide-y divide-gray-50">
                <template x-for="cat in allProduction" :key="cat.category_id">
                    <div class="px-4 py-2.5 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" :class="iconBgClass(cat.icon)">
                                <svg x-show="cat.icon === 'sunrise'" class="w-3.5 h-3.5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m-4.5 3.5L6 6m9 0l1.5-1.5M4 12H2m20 0h-2M6.343 17.657L4.929 19.071M19.071 19.071l-1.414-1.414M12 18a6 6 0 00-6-6 6 6 0 006 6 6 6 0 006-6 6 6 0 00-6 6z"/></svg>
                                <svg x-show="cat.icon === 'sun'" class="w-3.5 h-3.5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <svg x-show="cat.icon === 'moon'" class="w-3.5 h-3.5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                <svg x-show="cat.icon === 'cookie'" class="w-3.5 h-3.5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15h18v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3zM3 15l2.5-7.5A2 2 0 017.4 6h9.2a2 2 0 011.9 1.5L21 15M9 15V11M15 15V11"/></svg>
                                <svg x-show="cat.icon === 'dots'" class="w-3.5 h-3.5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg>
                            </div>
                            <span class="text-xs font-bold text-gray-800 truncate" x-text="cat.category_name"></span>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <span class="text-sm font-extrabold text-gray-900" x-text="cat.total_required"></span>
                            <span class="text-[9px] text-gray-400 font-bold uppercase">{{ __('portions') }}</span>
                            <span x-show="cat.pending > 0" class="text-[9px] font-bold rounded-full px-1.5 py-0.5 bg-orange-100 text-orange-600" x-text="cat.pending + 'P'"></span>
                            <span x-show="cat.preparing > 0" class="text-[9px] font-bold rounded-full px-1.5 py-0.5 bg-blue-100 text-blue-700" x-text="cat.preparing + '!'"></span>
                            <span x-show="cat.ready > 0" class="text-[9px] font-bold rounded-full px-1.5 py-0.5 bg-green-100 text-green-700" x-text="cat.ready + 'R'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ============ CATEGORY SECTIONS ============ --}}
        <template x-for="cat in allProduction" :key="cat.category_id">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up">

                {{-- Category header --}}
                <div class="px-4 py-3.5 border-b border-gray-100 flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                            :class="iconBgClass(cat.icon)">
                            <template x-if="cat.icon === 'sunrise'"><svg class="w-5 h-5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m-4.5 3.5L6 6m9 0l1.5-1.5M4 12H2m20 0h-2M6.343 17.657L4.929 19.071M19.071 19.071l-1.414-1.414M12 18a6 6 0 00-6-6 6 6 0 006 6 6 6 0 006-6 6 6 0 00-6 6z"/></svg></template>
                            <template x-if="cat.icon === 'sun'"><svg class="w-5 h-5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></template>
                            <template x-if="cat.icon === 'moon'"><svg class="w-5 h-5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></template>
                            <template x-if="cat.icon === 'cookie'"><svg class="w-5 h-5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15h18v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3zM3 15l2.5-7.5A2 2 0 017.4 6h9.2a2 2 0 011.9 1.5L21 15M9 15V11M15 15V11"/></svg></template>
                            <template x-if="cat.icon === 'dots'"><svg class="w-5 h-5" :class="iconTextClass(cat.icon)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg></template>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-gray-900">
                                <span x-text="cat.category_name"></span>
                                <span x-show="cat.category_name_ar" class="text-gray-400 font-normal text-xs" x-text="' / ' + (cat.category_name_ar || '')"></span>
                            </h3>
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                <span x-text="cat.total_required"></span> {{ __('portions') }}
                                <span x-show="cat.order_count" class="ml-1">· <span x-text="cat.order_count"></span> {{ __('orders') }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span x-show="cat.pending > 0" class="text-[9px] font-bold rounded-full px-2 py-0.5 bg-orange-100 text-orange-600" x-text="cat.pending + ' {{ __('pending') }}'"></span>
                        <span x-show="cat.sent_to_kitchen > 0" class="text-[9px] font-bold rounded-full px-2 py-0.5 bg-amber-100 text-amber-700" x-text="cat.sent_to_kitchen + ' {{ __('sent') }}'"></span>
                        <span x-show="cat.preparing > 0" class="text-[9px] font-bold rounded-full px-2 py-0.5 bg-blue-100 text-blue-700" x-text="cat.preparing + ' {{ __('prep') }}'"></span>
                        <span x-show="cat.ready > 0" class="text-[9px] font-bold rounded-full px-2 py-0.5 bg-green-100 text-green-700" x-text="cat.ready + ' {{ __('ready') }}'"></span>
                        <span x-show="cat.served > 0" class="text-[9px] font-bold rounded-full px-2 py-0.5 bg-emerald-100 text-emerald-700" x-text="cat.served + ' {{ __('served') }}'"></span>
                        <button @click="transferCategory(cat)" :disabled="transferringId === cat.category_id || cat.pending === 0"
                            class="btn-action px-3 py-1.5 rounded-lg bg-brand-700 text-white text-[10px] font-bold flex items-center gap-1 flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                            <svg x-show="transferringId !== cat.category_id" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            <svg x-show="transferringId === cat.category_id" class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                            <span x-text="cat.pending === 0 ? '{{ __('Done') }}' : '{{ __('Transfer') }}'"></span>
                        </button>
                    </div>
                </div>

                {{-- Meals list --}}
                <div class="divide-y divide-gray-50">
                    <template x-for="meal in cat.meals" :key="meal.meal_id ?? meal.meal_name">
                        <div>
                            {{-- Meal header (click to expand) --}}
                            <button @click="toggleMeal(cat.category_id, meal)" type="button"
                                class="w-full px-4 py-3.5 flex items-center gap-3 text-left hover:bg-gray-50/70 transition-colors">
                                <div class="w-11 h-11 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                    <img x-show="meal.image_url" :src="meal.image_url" class="w-full h-full object-cover" alt="">
                                    <svg x-show="!meal.image_url" class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-gray-800 truncate" x-text="meal.meal_name"></p>
                                    <p x-show="meal.meal_name_ar" class="text-[10px] text-gray-400 truncate" x-text="meal.meal_name_ar"></p>
                                    <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                                        <span x-show="meal.pending" class="text-[9px] font-bold rounded-full px-1.5 py-0.5 bg-gray-100 text-gray-500" x-text="meal.pending + ' {{ __('pend') }}'"></span>
                                        <span x-show="meal.sent_to_kitchen" class="text-[9px] font-bold rounded-full px-1.5 py-0.5 bg-amber-100 text-amber-700" x-text="meal.sent_to_kitchen + ' {{ __('sent') }}'"></span>
                                        <span x-show="meal.preparing" class="text-[9px] font-bold rounded-full px-1.5 py-0.5 bg-blue-100 text-blue-700" x-text="meal.preparing + ' {{ __('prep') }}'"></span>
                                        <span x-show="meal.ready" class="text-[9px] font-bold rounded-full px-1.5 py-0.5 bg-green-100 text-green-700" x-text="meal.ready + ' {{ __('rdy') }}'"></span>
                                        <span x-show="meal.served" class="text-[9px] font-bold rounded-full px-1.5 py-0.5 bg-emerald-100 text-emerald-700" x-text="meal.served + ' {{ __('srv') }}'"></span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <div class="text-right">
                                        <p class="text-xl font-extrabold text-gray-900" x-text="meal.total_required"></p>
                                        <p class="text-[8px] text-gray-400 uppercase font-bold">{{ __('portions') }}</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="isMealOpen(cat.category_id, meal) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </button>

                            {{-- Expanded meal detail --}}
                            <div x-show="isMealOpen(cat.category_id, meal)"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="px-4 pb-4 bg-gray-50/60" style="display: none;">

                                {{-- Detail grid --}}
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-3">
                                    <div class="bg-white rounded-xl border border-gray-100 p-2.5 text-center">
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Total Qty') }}</p>
                                        <p class="text-lg font-extrabold text-gray-900 mt-0.5" x-text="meal.total_required"></p>
                                    </div>
                                    <div class="bg-white rounded-xl border border-gray-100 p-2.5 text-center">
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Customers') }}</p>
                                        <p class="text-lg font-extrabold text-blue-600 mt-0.5" x-text="meal.customers?.length ?? 0"></p>
                                    </div>
                                    <div class="bg-white rounded-xl border border-gray-100 p-2.5 text-center">
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Calories') }}</p>
                                        <p class="text-lg font-extrabold text-amber-600 mt-0.5" x-text="(meal.calories ?? '—')"></p>
                                    </div>
                                    <div class="bg-white rounded-xl border border-gray-100 p-2.5 text-center">
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Ready') }}</p>
                                        <p class="text-lg font-extrabold text-green-600 mt-0.5" x-text="meal.ready ?? 0"></p>
                                    </div>
                                </div>

                                {{-- Ingredients --}}
                                <div x-show="meal.ingredients?.length" class="mb-3">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide mb-1.5">{{ __('Ingredients') }}</p>
                                    <div class="flex flex-wrap items-center gap-1">
                                        <template x-for="ing in meal.ingredients" :key="ing">
                                            <span class="px-2 py-0.5 rounded-full bg-white border border-gray-200 text-[10px] text-gray-600" x-text="ing"></span>
                                        </template>
                                    </div>
                                </div>

                                {{-- Allergens --}}
                                <div class="mb-3">
                                    <p class="text-[9px] font-bold text-red-400 uppercase tracking-wide mb-1.5">{{ __('Meal allergens') }}</p>
                                    <div x-show="meal.allergens?.length" class="flex flex-wrap items-center gap-1">
                                        <template x-for="a in meal.allergens" :key="a">
                                            <span class="px-2 py-0.5 rounded-full bg-red-50 border border-red-100 text-[10px] text-red-600" x-text="a"></span>
                                        </template>
                                    </div>
                                    <p x-show="!meal.allergens?.length" class="text-[10px] text-gray-400">{{ __('None') }}</p>
                                </div>

                                {{-- Customer orders list --}}
                                <div class="mb-3">
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide mb-1.5">
                                        {{ __('Customer Orders') }} (<span x-text="meal.customers?.length ?? 0"></span>)
                                    </p>
                                    <div class="space-y-1.5 max-h-64 overflow-y-auto pr-1">
                                        <template x-for="c in meal.customers" :key="c.order_id">
                                            <div class="flex items-center justify-between gap-2 bg-white rounded-lg px-2.5 py-2 border border-gray-100">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-[11px] font-bold text-gray-800 truncate" x-text="c.customer_name"></p>
                                                    <p class="text-[9px] text-gray-400 truncate" x-text="(c.order_number || ('#' + c.order_id)) + (c.customer_phone ? (' · ' + c.customer_phone) : '')"></p>
                                                    <p x-show="c.address" class="text-[9px] text-gray-300 truncate" x-text="c.address"></p>
                                                </div>
                                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full capitalize" :class="itemStatusClass(c.item_status)" x-text="c.item_status?.replaceAll('_',' ')"></span>
                                                    <span class="text-[11px] font-bold text-gray-700" x-text="'×' + c.quantity"></span>
                                                </div>
                                            </div>
                                        </template>
                                        <div x-show="!meal.customers?.length" class="text-[11px] text-gray-400 text-center py-2">{{ __('No customers found.') }}</div>
                                    </div>
                                </div>

                                {{-- Action buttons --}}
                                <div class="flex gap-2">
                                    <button x-show="meal.sent_to_kitchen > 0" @click="advanceMeal(cat, meal, 'start_preparing')" class="btn-action flex-1 py-2 rounded-lg bg-blue-600 text-white text-[11px] font-bold flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        {{ __('Start Preparing') }}
                                    </button>
                                    <button x-show="meal.preparing > 0" @click="advanceMeal(cat, meal, 'mark_ready')" class="btn-action flex-1 py-2 rounded-lg bg-green-600 text-white text-[11px] font-bold flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ __('Mark Ready') }}
                                    </button>
                                    <button x-show="meal.ready > 0" @click="advanceMeal(cat, meal, 'mark_served')" class="btn-action flex-1 py-2 rounded-lg bg-emerald-700 text-white text-[11px] font-bold flex items-center justify-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ __('Mark Served') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="!cat.meals?.length" class="px-4 py-6 text-center text-xs text-gray-400">{{ __('No items scheduled for this category.') }}</div>
                </div>
            </div>
        </template>

        {{-- Empty state --}}
        <div x-show="!allProduction.length" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center animate-slide-up">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-sm font-bold text-gray-400">{{ __('No schedules found for today.') }}</p>
            <p class="text-xs text-gray-300 mt-1">{{ __('Check back later or contact admin.') }}</p>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function chefSchedule() {
    return {
        allProduction: @json($allProduction),
        summary: @json($summary),
        scheduleDate: @json($today),
        transferringId: null,
        openMeals: [],

        strings: {
            transferTitle: @json(__('Transfer to Kitchen?')),
            transferText: @json(__('This sends only the items in this schedule to the kitchen. The order stays active until every schedule is completed.')),
            yesTransfer: @json(__('Yes, transfer')),
            cancel: @json(__('Cancel')),
            errorTitle: @json(__('Error')),
        },

        init() {
        },

        todayStr() {
            return new Date().toISOString().split('T')[0];
        },

        tomorrowStr() {
            const d = new Date();
            d.setDate(d.getDate() + 1);
            return d.toISOString().split('T')[0];
        },

        setDate(d) {
            this.scheduleDate = d;
            window.location.href = '{{ route('chef.schedule') }}?date=' + d;
        },

        get headerDate() {
            const d = new Date(this.scheduleDate + 'T00:00:00');
            return d.toLocaleDateString(undefined, { weekday: 'short', month: 'short', day: 'numeric' });
        },

        mealKey(catId, meal) {
            return catId + '-' + (meal.meal_id ?? meal.meal_name);
        },

        toggleMeal(catId, meal) {
            const key = this.mealKey(catId, meal);
            const idx = this.openMeals.indexOf(key);
            if (idx === -1) this.openMeals.push(key);
            else this.openMeals.splice(idx, 1);
        },

        isMealOpen(catId, meal) {
            return this.openMeals.includes(this.mealKey(catId, meal));
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

        iconBgClass(icon) {
            return {
                'sunrise': 'bg-orange-50',
                'sun': 'bg-yellow-50',
                'moon': 'bg-indigo-50',
                'cookie': 'bg-pink-50',
                'dots': 'bg-gray-50',
            }[icon] || 'bg-gray-50';
        },

        iconTextClass(icon) {
            return {
                'sunrise': 'text-orange-500',
                'sun': 'text-amber-500',
                'moon': 'text-indigo-500',
                'cookie': 'text-pink-500',
                'dots': 'text-gray-400',
            }[icon] || 'text-gray-400';
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

        async transferCategory(cat) {
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

            this.transferringId = cat.category_id;
            try {
                const data = await this.postJson(`{{ url('chef/schedule/transfer') }}`, {
                    category_id: cat.category_id,
                    date: this.scheduleDate,
                });
                if (data.success) {
                    window.location.reload();
                } else {
                    Swal.fire({ title: this.strings.errorTitle, text: data.message, icon: 'error', customClass: { popup: 'rounded-2xl' } });
                }
            } finally {
                this.transferringId = null;
            }
        },

        async advanceMeal(cat, meal, action) {
            const data = await this.postJson(`{{ url('chef/schedule/advance') }}`, {
                category_id: cat.category_id,
                action: action,
                meal_id: meal.meal_id || null,
                date: this.scheduleDate,
            });
            if (data.success) {
                window.location.reload();
            } else {
                Swal.fire({ title: this.strings.errorTitle, text: data.message, icon: 'error', customClass: { popup: 'rounded-2xl' } });
            }
        },
    };
}
</script>
@endpush
