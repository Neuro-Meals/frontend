@extends('layouts.admin')

@section('title', __('Kitchen Schedule') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Kitchen Schedule'))

@section('content')
<div x-data="scheduleBoard()" x-init="init()" class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">{{ __('Today\'s Kitchen Plan by Category') }}</h2>
            <p class="text-sm text-gray-400 mt-1">{{ __('Shows Breakfast, Lunch, Dinner or other categories; each meal\'s total quantity; and every customer who needs it.') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <input type="date" x-model="date" @change="changeDate()"
                class="px-3 py-2 rounded-lg border border-gray-200 text-sm font-medium text-gray-700 focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
            <button @click="refresh()" :disabled="loading" class="p-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors disabled:opacity-50">
                <svg class="w-4 h-4" :class="loading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="col-span-2 sm:col-span-1 lg:col-span-2">
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('Search') }}</label>
                <input type="text" x-model="filters.search" @keydown.enter="applyFilters()" placeholder="{{ __('Customer, order #, email...') }}"
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('Status') }}</label>
                <select x-model="filters.status" @change="applyFilters()" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                    <option value="">{{ __('All') }}</option>
                    @foreach([
                        'scheduled' => 'Scheduled',
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'ready_for_delivery' => 'Ready for Delivery',
                        'out_for_delivery' => 'Out for Delivery',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ] as $st => $label)
                    <option value="{{ $st }}">{{ __($label) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('User ID') }} <span class="normal-case text-gray-300">({{ __('0 = all') }})</span></label>
                <input type="number" min="0" x-model.number="filters.user_id" @keydown.enter="applyFilters()"
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('Subscription ID') }} <span class="normal-case text-gray-300">({{ __('0 = all') }})</span></label>
                <input type="number" min="0" x-model.number="filters.subscription_id" @keydown.enter="applyFilters()"
                    class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
            </div>
            <div class="flex gap-2">
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('Page') }}</label>
                    <input type="number" min="1" x-model.number="filters.page" @keydown.enter="applyFilters()"
                        class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('Limit') }}</label>
                    <input type="number" min="1" x-model.number="filters.limit" @keydown.enter="applyFilters()"
                        class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between mt-3">
            <label class="flex items-center gap-2 text-xs font-medium text-gray-500">
                <input type="checkbox" x-model="includeCompleted" @change="applyFilters()" class="rounded border-gray-300 text-[#6E7A25] focus:ring-[#6E7A25]/20">
                {{ __('Include delivered and cancelled orders') }}
            </label>
            <div class="flex items-center gap-2">
                <button @click="clearFilters()" class="px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-500 hover:bg-gray-50 transition-colors">{{ __('Clear') }}</button>
                <button @click="applyFilters()" :disabled="loading" class="px-4 py-2 rounded-lg bg-[#173327] text-white text-xs font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-50 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                    {{ __('Search') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Summary stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 kpi-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('Customer Orders') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" x-text="summary.total_orders ?? 0"></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 kpi-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('Meal Categories') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" x-text="summary.category_count ?? 0"></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 kpi-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('Distinct Meals') }}</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1" x-text="summary.distinct_meals ?? 0"></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 kpi-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide">{{ __('Total Portions') }}</p>
                    <p class="text-3xl font-bold text-[#6E7A25] mt-1" x-text="summary.total_portions ?? 0"></p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-[#6E7A25]/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Category sections with meals --}}
    <template x-for="cat in allProduction" :key="cat.category_id">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Category header --}}
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-3 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                        :class="categoryIconClass(cat.category_name)">
                        <span x-text="categoryIcon(cat.category_name)" class="text-lg"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">
                            <span x-text="cat.category_name"></span>
                            <span x-show="cat.category_name_ar" class="text-gray-400 font-normal text-sm" x-text="' / ' + (cat.category_name_ar || '')"></span>
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <span x-text="cat.total_required"></span> {{ __('portions') }}
                            <span x-show="cat.order_count" class="ml-2">· <span x-text="cat.order_count"></span> {{ __('orders') }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span x-show="cat.pending > 0" class="text-[10px] font-bold rounded-full px-2 py-1 bg-orange-100 text-orange-600" x-text="cat.pending + ' {{ __('pending') }}'"></span>
                    <span x-show="cat.sent_to_kitchen > 0" class="text-[10px] font-bold rounded-full px-2 py-1 bg-amber-100 text-amber-700" x-text="cat.sent_to_kitchen + ' {{ __('sent') }}'"></span>
                    <span x-show="cat.preparing > 0" class="text-[10px] font-bold rounded-full px-2 py-1 bg-blue-100 text-blue-700" x-text="cat.preparing + ' {{ __('preparing') }}'"></span>
                    <span x-show="cat.ready > 0" class="text-[10px] font-bold rounded-full px-2 py-1 bg-green-100 text-green-700" x-text="cat.ready + ' {{ __('ready') }}'"></span>
                    <span x-show="cat.served > 0" class="text-[10px] font-bold rounded-full px-2 py-1 bg-emerald-100 text-emerald-700" x-text="cat.served + ' {{ __('served') }}'"></span>
                    <button @click="transferCategory(cat)" :disabled="transferringId === cat.category_id || cat.pending === 0"
                        class="px-3 py-1.5 rounded-lg bg-[#173327] text-white text-xs font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5 whitespace-nowrap">
                        <svg x-show="transferringId !== cat.category_id" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        <svg x-show="transferringId === cat.category_id" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        <span x-text="cat.pending === 0 ? '{{ __('All Sent') }}' : '{{ __('Transfer') }}'"></span>
                    </button>
                </div>
            </div>

            {{-- Meals list --}}
            <div class="divide-y divide-gray-50">
                <template x-for="meal in cat.meals" :key="meal.meal_id ?? meal.meal_name">
                    <div>
                        {{-- Meal header row (click to expand) --}}
                        <button @click="toggleMeal(cat.category_id, meal)" type="button"
                            class="w-full px-5 py-4 flex items-center gap-4 text-left hover:bg-gray-50/70 transition-colors">
                            <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden flex items-center justify-center">
                                <img x-show="meal.image_url" :src="meal.image_url" class="w-full h-full object-cover" alt="">
                                <svg x-show="!meal.image_url" class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-gray-800 truncate" x-text="meal.meal_name"></p>
                                <p x-show="meal.meal_name_ar" class="text-xs text-gray-400 truncate" x-text="meal.meal_name_ar"></p>
                                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                    <span x-show="meal.pending" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-gray-100 text-gray-500" x-text="meal.pending + ' {{ __('pending') }}'"></span>
                                    <span x-show="meal.sent_to_kitchen" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-amber-100 text-amber-700" x-text="meal.sent_to_kitchen + ' {{ __('sent') }}'"></span>
                                    <span x-show="meal.preparing" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-blue-100 text-blue-700" x-text="meal.preparing + ' {{ __('preparing') }}'"></span>
                                    <span x-show="meal.ready" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-green-100 text-green-700" x-text="meal.ready + ' {{ __('ready') }}'"></span>
                                    <span x-show="meal.served" class="text-[10px] font-bold rounded-full px-2 py-0.5 bg-emerald-100 text-emerald-700" x-text="meal.served + ' {{ __('served') }}'"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900" x-text="meal.total_required"></p>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">{{ __('portions') }}</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="isMealOpen(cat.category_id, meal) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </button>

                        {{-- Expanded meal detail --}}
                        <div x-show="isMealOpen(cat.category_id, meal)"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="px-5 pb-5 bg-gray-50/60" style="display: none;">

                            {{-- Detail grid: Total Quantity / Customers / Calories / Orders Ready --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                                <div class="bg-white rounded-xl border border-gray-100 p-3 text-center">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Total Quantity') }}</p>
                                    <p class="text-xl font-bold text-gray-900 mt-0.5" x-text="meal.total_required"></p>
                                </div>
                                <div class="bg-white rounded-xl border border-gray-100 p-3 text-center">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Customers') }}</p>
                                    <p class="text-xl font-bold text-blue-600 mt-0.5" x-text="meal.customers?.length ?? 0"></p>
                                </div>
                                <div class="bg-white rounded-xl border border-gray-100 p-3 text-center">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Calories / Portion') }}</p>
                                    <p class="text-xl font-bold text-amber-600 mt-0.5" x-text="(meal.calories ?? '—')"></p>
                                </div>
                                <div class="bg-white rounded-xl border border-gray-100 p-3 text-center">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ __('Orders Ready') }}</p>
                                    <p class="text-xl font-bold text-green-600 mt-0.5" x-text="meal.ready ?? 0"></p>
                                </div>
                            </div>

                            {{-- Ingredients --}}
                            <div x-show="meal.ingredients?.length" class="mb-3">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-1.5">{{ __('Ingredients') }}</p>
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <template x-for="ing in meal.ingredients" :key="ing">
                                        <span class="px-2.5 py-1 rounded-full bg-white border border-gray-200 text-[11px] text-gray-600" x-text="ing"></span>
                                    </template>
                                </div>
                            </div>

                            {{-- Allergens --}}
                            <div class="mb-3">
                                <p class="text-[10px] font-bold text-red-400 uppercase tracking-wide mb-1.5">{{ __('Meal allergens') }}</p>
                                <div x-show="meal.allergens?.length" class="flex flex-wrap items-center gap-1.5">
                                    <template x-for="a in meal.allergens" :key="a">
                                        <span class="px-2.5 py-1 rounded-full bg-red-50 border border-red-100 text-[11px] text-red-600" x-text="a"></span>
                                    </template>
                                </div>
                                <p x-show="!meal.allergens?.length" class="text-[11px] text-gray-400">{{ __('None') }}</p>
                            </div>

                            {{-- Customer orders list --}}
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide mb-2">
                                    {{ __('Customer Orders') }} (<span x-text="meal.customers?.length ?? 0"></span>)
                                </p>
                                <div class="space-y-1.5 max-h-80 overflow-y-auto pr-1">
                                    <template x-for="c in meal.customers" :key="c.order_id">
                                        <div class="flex items-center justify-between gap-2 bg-white rounded-lg px-3 py-2.5 border border-gray-100">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-bold text-gray-800 truncate" x-text="c.customer_name"></p>
                                                <p class="text-[10px] text-gray-400 truncate" x-text="(c.order_number || ('#' + c.order_id)) + (c.customer_phone ? (' · ' + c.customer_phone) : '')"></p>
                                                <p x-show="c.address" class="text-[10px] text-gray-300 truncate" x-text="c.address"></p>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full capitalize" :class="itemStatusClass(c.item_status)" x-text="c.item_status?.replaceAll('_',' ')"></span>
                                                <span class="text-xs font-bold text-gray-700" x-text="'×' + c.quantity"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="!meal.customers?.length" class="text-xs text-gray-400 text-center py-2">{{ __('No customers found.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="!cat.meals?.length" class="px-5 py-8 text-center text-sm text-gray-400">{{ __('No items scheduled for this category.') }}</div>
            </div>
        </div>
    </template>

    {{-- Empty state --}}
    <div x-show="!allProduction.length" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p class="text-sm font-bold text-gray-400">{{ __('No schedules found for this date.') }}</p>
        <p class="text-xs text-gray-300 mt-1">{{ __('Try changing the date or clearing filters.') }}</p>
    </div>

</div>

<script>
function scheduleBoard() {
    return {
        date: @json($date),
        allProduction: @json($allProduction),
        summary: @json($summary),
        filters: {
            search: @json($filters['search'] ?? ''),
            status: @json($filters['status'] ?? ''),
            user_id: @json($filters['user_id'] ?? 0),
            subscription_id: @json($filters['subscription_id'] ?? 0),
            page: @json($filters['page'] ?? 0),
            limit: @json($filters['limit'] ?? 0),
        },
        includeCompleted: false,
        loading: false,
        transferringId: null,
        openMeals: [],
        strings: {
            confirmTitle: @json(__('Transfer to Kitchen?')),
            confirmText: @json(__('This sends only the items in this schedule to the kitchen. The order will stay active until every schedule is completed.')),
            confirmButton: @json(__('Yes, transfer')),
            cancelButton: @json(__('Cancel')),
            successTitle: @json(__('Transferred!')),
            errorTitle: @json(__('Error')),
        },

        init() {
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

        categoryIcon(name) {
            const n = (name || '').toLowerCase();
            if (n.includes('breakfast') || n.includes('morning')) return '🌅';
            if (n.includes('lunch')) return '☀️';
            if (n.includes('dinner') || n.includes('evening') || n.includes('supper')) return '🌙';
            if (n.includes('snack')) return '🍪';
            return '🍽️';
        },

        categoryIconClass(name) {
            const n = (name || '').toLowerCase();
            if (n.includes('breakfast') || n.includes('morning')) return 'bg-orange-50';
            if (n.includes('lunch')) return 'bg-yellow-50';
            if (n.includes('dinner') || n.includes('evening') || n.includes('supper')) return 'bg-indigo-50';
            if (n.includes('snack')) return 'bg-pink-50';
            return 'bg-gray-50';
        },

        async changeDate() {
            await this.refresh();
        },

        applyFilters() {
            this.refresh();
        },

        clearFilters() {
            this.filters = { search: '', status: '', user_id: 0, subscription_id: 0, page: 0, limit: 0 };
            this.includeCompleted = false;
            this.refresh();
        },

        async refresh() {
            this.loading = true;
            try {
                const url = new URL('{{ route('admin.schedule.data') }}', window.location.origin);
                url.searchParams.set('date', this.date);
                if (this.filters.search) url.searchParams.set('search', this.filters.search);
                if (this.filters.status) url.searchParams.set('status', this.filters.status);
                if (this.filters.user_id) url.searchParams.set('user_id', this.filters.user_id);
                if (this.filters.subscription_id) url.searchParams.set('subscription_id', this.filters.subscription_id);
                if (this.filters.page) url.searchParams.set('page', this.filters.page);
                if (this.filters.limit) url.searchParams.set('limit', this.filters.limit);
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const data = await res.json();
                if (data.success) {
                    this.allProduction = data.allProduction;
                    this.summary = data.summary;
                }
            } finally {
                this.loading = false;
            }
        },

        async transferCategory(cat) {
            const confirmed = await Swal.fire({
                title: this.strings.confirmTitle,
                text: this.strings.confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6E7A25',
                confirmButtonText: this.strings.confirmButton,
                cancelButtonText: this.strings.cancelButton,
            });
            if (!confirmed.isConfirmed) return;

            this.transferringId = cat.category_id;
            try {
                const res = await fetch('{{ route('admin.schedule.transfer') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ date: this.date, category_id: cat.category_id }),
                });
                const data = await res.json();
                if (data.success) {
                    Swal.fire({ title: this.strings.successTitle, icon: 'success', timer: 1400, showConfirmButton: false });
                    await this.refresh();
                } else {
                    Swal.fire({ title: this.strings.errorTitle, text: data.message, icon: 'error' });
                }
            } catch (e) {
                Swal.fire({ title: this.strings.errorTitle, text: String(e), icon: 'error' });
            } finally {
                this.transferringId = null;
            }
        },
    };
}
</script>
@endsection
