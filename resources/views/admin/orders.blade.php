@extends('layouts.admin')

@section('title', __('Orders') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Orders'))

@section('content')
<div x-data="ordersApp()" x-init="init()" class="space-y-4">

  {{-- Stats Row --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="!loading">
    <template x-for="s in stats" :key="s.label">
      <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm hover:shadow-md transition-shadow">
        <p class="text-[10px] text-gray-400 mb-1 font-medium" x-text="s.label"></p>
        <p class="text-xl font-extrabold" :class="s.color" x-text="s.value"></p>
      </div>
    </template>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="loading">
    <template x-for="i in 4">
      <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm animate-pulse"><div class="h-3 bg-gray-100 rounded w-1/2 mb-2"></div><div class="h-6 bg-gray-100 rounded w-3/4"></div></div>
    </template>
  </div>

  {{-- Filter Bar --}}
  <div class="bg-white rounded-2xl border border-gray-100 p-3 shadow-sm flex flex-wrap items-center gap-2">
    <div class="flex items-center gap-2 mr-auto">
      <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center shadow-sm">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <div>
        <p class="text-sm font-bold text-gray-900">{{ __('Today\'s Orders') }}</p>
        <p class="text-[10px] text-gray-400" x-text="todayDate"></p>
      </div>
    </div>
    <button @click="fetchOrders()" class="px-3 py-2 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-all shadow-sm whitespace-nowrap flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
      {{ __('Refresh') }}
    </button>
    <button @click="toggleCompleted()"
      :class="includeCompleted ? 'bg-[#173327] text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
      class="px-3 py-2 text-xs font-bold border border-gray-100 rounded-lg transition-all whitespace-nowrap flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ __('Show Completed') }}
      <span x-show="includeCompleted" class="ml-1">×</span>
    </button>
  </div>

  {{-- Category Tabs --}}
  <div class="bg-white rounded-2xl border border-gray-100 p-2 shadow-sm">
    <div class="flex items-center gap-1.5 overflow-x-auto">
      <template x-for="cat in categories" :key="cat.id">
        <button @click="switchTab(cat.id)"
          :class="activeTab === cat.id ? 'bg-gradient-to-r from-[#6E7A25] to-[#173327] text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
          class="flex items-center gap-2 px-4 py-2.5 text-xs font-bold rounded-xl transition-all whitespace-nowrap flex-shrink-0">
          <template x-if="cat.icon === 'sunrise'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m-4.5 3.5L6 6m9 0l1.5-1.5M4 12H2m20 0h-2M6.343 17.657L4.929 19.071M19.071 19.071l-1.414-1.414M12 18a6 6 0 00-6-6 6 6 0 006 6 6 6 0 006-6 6 6 0 00-6 6z"/></svg></template>
          <template x-if="cat.icon === 'sun'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></template>
          <template x-if="cat.icon === 'moon'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></template>
          <template x-if="cat.icon === 'cookie'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15h18v3a3 3 0 01-3 3H6a3 3 0 01-3-3v-3zM3 15l2.5-7.5A2 2 0 017.4 6h9.2a2 2 0 011.9 1.5L21 15M9 15V11M15 15V11"/></svg></template>
          <template x-if="cat.icon === 'dots'"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01"/></svg></template>
          <span x-text="cat.name"></span>
          <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
            :class="activeTab === cat.id ? 'bg-white/20 text-white' : (cat.count > 0 ? 'bg-[#6E7A25]/10 text-[#6E7A25]' : 'bg-gray-100 text-gray-400')"
            x-text="cat.count"></span>
        </button>
      </template>
    </div>
  </div>

  {{-- Meals in Active Category --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-show="activeMeals.length > 0">
    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-[#6E7A25]/5 to-transparent flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-[#6E7A25]/10 flex items-center justify-center">
          <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <h3 class="text-sm font-bold text-gray-900">{{ __('Meals & Ingredients') }}</h3>
      </div>
      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#6E7A25]/10 text-[#6E7A25]" x-text="activeMeals.length + ' {{ __('meals') }}'"></span>
    </div>
    <div class="divide-y divide-gray-50 max-h-[32rem] overflow-y-auto">
      <template x-for="meal in activeMeals" :key="meal.id">
        <div class="px-4 py-4 hover:bg-gray-50/30 transition-colors">
          <div class="flex items-start gap-3">
            <div class="w-14 h-14 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden flex items-center justify-center shadow-sm">
              <img x-show="meal.image_url" :src="meal.image_url" class="w-full h-full object-cover" alt="">
              <svg x-show="!meal.image_url" class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between gap-2 mb-1">
                <p class="text-sm font-extrabold text-gray-900" x-text="meal.name"></p>
                <div class="flex items-center gap-1.5 flex-shrink-0">
                  <span x-show="meal.calories" class="text-[10px] font-bold text-[#6E7A25] bg-[#6E7A25]/10 px-2 py-0.5 rounded-full" x-text="meal.calories + ' kcal'"></span>
                  <span x-show="!meal.is_available" class="text-[9px] font-bold text-red-500 bg-red-50 px-1.5 py-0.5 rounded-full">{{ __('Unavailable') }}</span>
                </div>
              </div>
              {{-- Nutrition badges --}}
              <div class="flex flex-wrap items-center gap-1.5 mb-2" x-show="meal.protein_g || meal.carbs_g || meal.fat_g">
                <span x-show="meal.protein_g" class="text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-full" x-text="'P ' + meal.protein_g + 'g'"></span>
                <span x-show="meal.carbs_g" class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded-full" x-text="'C ' + meal.carbs_g + 'g'"></span>
                <span x-show="meal.fat_g" class="text-[9px] font-bold text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded-full" x-text="'F ' + meal.fat_g + 'g'"></span>
                <span x-show="meal.price" class="text-[9px] font-bold text-gray-600 bg-gray-100 px-1.5 py-0.5 rounded-full" x-text="'SAR ' + meal.price"></span>
              </div>
              {{-- Ingredients --}}
              <div x-show="meal.ingredients?.length" class="mb-2">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('Ingredients') }}</p>
                <div class="flex flex-wrap items-center gap-1">
                  <template x-for="(ing, idx) in meal.ingredients" :key="idx">
                    <span class="px-2 py-0.5 rounded-lg bg-[#6E7A25]/5 border border-[#6E7A25]/10 text-[10px] font-medium text-gray-700" x-text="ing"></span>
                  </template>
                </div>
              </div>
              {{-- Allergens --}}
              <div x-show="meal.allergens?.length">
                <p class="text-[9px] font-bold text-red-400 uppercase tracking-wide mb-1">{{ __('Allergens') }}</p>
                <div class="flex flex-wrap items-center gap-1">
                  <template x-for="(a, idx) in meal.allergens" :key="idx">
                    <span class="px-2 py-0.5 rounded-lg bg-red-50 border border-red-100 text-[10px] font-medium text-red-600" x-text="a"></span>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>

  {{-- Orders for Active Category --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-[#173327]/5 to-transparent flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-lg bg-[#173327]/10 flex items-center justify-center">
          <svg class="w-4 h-4 text-[#173327]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
        </div>
        <h3 class="text-sm font-bold text-gray-900" x-text="activeCategoryName + ' ' + '{{ __('Orders') }}'"></h3>
      </div>
      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#173327]/10 text-[#173327]" x-text="activeOrders.length + ' {{ __('orders') }}'"></span>
    </div>

    <template x-if="loading">
      <div class="px-4 py-8"><div class="space-y-3 animate-pulse"><template x-for="i in 4"><div class="h-16 bg-gray-50 rounded-xl"></div></template></div></div>
    </template>

    <template x-if="!loading && activeOrders.length === 0">
      <div class="px-4 py-12 text-center">
        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
        <p class="text-sm text-gray-400">{{ __('No orders in this category.') }}</p>
      </div>
    </template>

    <div class="divide-y divide-gray-50" x-show="!loading && activeOrders.length > 0">
      <template x-for="order in activeOrders" :key="order.order_id">
        <div class="px-4 py-3.5 hover:bg-gray-50/30 transition-colors cursor-pointer" @click="showDetail(order)">
          <div class="flex items-center justify-between gap-3 mb-2">
            <div class="flex items-center gap-2.5 min-w-0">
              <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-sm" x-text="order.customer?.charAt(0)?.toUpperCase()"></div>
              <div class="min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate" x-text="order.customer"></p>
                <p class="text-[10px] text-gray-400" x-text="order.id + ' · ' + (order.time || '--:--')"></p>
              </div>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border whitespace-nowrap flex-shrink-0" :class="statusClass(order.status)">
              <span x-text="statusLabel(order.status)"></span>
            </span>
          </div>
          {{-- Item thumbnails --}}
          <div class="flex items-center gap-1.5 mb-2" x-show="order.items?.length">
            <template x-for="(item, i) in order.items.slice(0, 5)" :key="i">
              <div class="w-8 h-8 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0 border border-gray-100">
                <img x-show="item.image_url" :src="item.image_url" class="w-full h-full object-cover" alt="">
                <div x-show="!item.image_url" class="w-full h-full flex items-center justify-center">
                  <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
              </div>
            </template>
            <span x-show="order.items.length > 5" class="text-[10px] text-gray-400 font-medium" x-text="'+' + (order.items.length - 5) + ' more'"></span>
          </div>
          {{-- Summary row --}}
          <div class="flex items-center gap-3 flex-wrap">
            <span class="text-[10px] text-gray-500 truncate" x-text="order.meal_summary"></span>
            <div class="flex items-center gap-2 ml-auto flex-shrink-0">
              <span x-show="order.total_calories" class="text-[10px] font-bold text-[#6E7A25] bg-[#6E7A25]/10 px-2 py-0.5 rounded-full" x-text="order.total_calories + ' kcal'"></span>
              <span x-show="order.category_amount" class="text-[10px] font-bold text-gray-700" x-text="'SAR ' + order.category_amount"></span>
              <span class="text-[10px] text-gray-400" x-text="order.meal_count + ' items'"></span>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>

  {{-- Per-Category Delivery Assignment --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4" x-show="activeOrders.length > 0">
    <div class="flex items-center gap-2 mb-3">
      <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
      </div>
      <h3 class="text-sm font-bold text-gray-900">{{ __('Delivery for this category') }}</h3>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <select x-model="categoryDriverId" class="text-xs border border-gray-200 rounded-lg px-3 py-2.5 bg-gray-50 text-gray-700 outline-none cursor-pointer flex-1 min-w-[160px]">
        <option value="">{{ __('Select Driver') }}</option>
        <template x-for="driver in drivers" :key="driver.id">
          <option :value="driver.id" x-text="driver.name + (driver.is_active ? '' : ' (inactive)')" :disabled="!driver.is_active"></option>
        </template>
      </select>
      <input type="time" x-model="categoryDeliveryTime" class="text-xs border border-gray-200 rounded-lg px-3 py-2.5 bg-gray-50 text-gray-700 outline-none">
      <button @click="assignCategoryDriver()" :disabled="actionLoading || !categoryDriverId"
        class="px-4 py-2.5 text-xs font-bold text-white bg-[#173327] rounded-lg hover:bg-[#1a4a3a] transition-all disabled:opacity-60 whitespace-nowrap flex items-center gap-1.5">
        <svg x-show="!actionLoading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="actionLoading" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
        {{ __('Assign to All') }}
      </button>
    </div>
    <p class="text-[10px] text-gray-400 mt-2 flex items-center gap-1">
      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      {{ __('Assigns a driver for all orders in this category.') }}
    </p>
  </div>

  {{-- Order Detail Slide-Out Panel --}}
  <div x-show="selected" class="fixed inset-0 z-50 flex justify-end" style="display: none"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="selected = null"></div>
    <div class="relative w-full max-w-lg bg-white shadow-2xl h-full overflow-y-auto"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="translate-x-full"
      x-transition:enter-end="translate-x-0"
      @click.outside="selected = null">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] p-5 text-white sticky top-0 z-10">
        <div class="flex items-center justify-between mb-3">
          <div>
            <h3 class="text-base font-bold">{{ __('Order Details') }}</h3>
            <p class="text-xs text-white/70" x-text="selected?.id"></p>
          </div>
          <button @click="selected = null" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border border-white/20 bg-white/10" :class="statusClass(selected?.status)">
            <span x-text="statusLabel(selected?.status)"></span>
          </span>
          <span x-show="selected?.delivery_status" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border border-white/20 bg-white/10 capitalize" x-text="selected?.delivery_status?.replaceAll('_',' ')"></span>
          <span x-show="selected?.total_calories" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/10" x-text="selected?.total_calories + ' kcal'"></span>
        </div>
      </div>

      <div id="order-detail-content" class="p-5 space-y-5">
        {{-- Customer Info --}}
        <div class="bg-gray-50 rounded-2xl p-4">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Customer') }}</h4>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-base flex-shrink-0 shadow-md" x-text="selected?.customer?.charAt(0)?.toUpperCase()"></div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-gray-900" x-text="selected?.customer"></p>
              <p class="text-xs text-gray-500" x-text="selected?.customer_email || ''"></p>
              <p class="text-xs text-gray-400 mt-0.5" x-text="selected?.customer_phone || ''"></p>
            </div>
          </div>
          <div x-show="selected?.customer_phone" class="grid grid-cols-2 gap-2 mt-4">
            <a :href="'tel:' + selected?.customer_phone" class="flex items-center justify-center gap-2 px-3 py-2.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              {{ __('Call') }}
            </a>
            <a :href="'https://wa.me/' + (selected?.customer_phone || '').replace(/\D/g, '')" target="_blank" class="flex items-center justify-center gap-2 px-3 py-2.5 text-xs font-bold text-white bg-[#25D366] rounded-lg hover:bg-[#1da851] transition-colors">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.955L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
              {{ __('WhatsApp') }}
            </a>
          </div>
        </div>

        {{-- Delivery Info --}}
        <div class="bg-gray-50 rounded-2xl p-4">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Delivery') }}</h4>
          <div class="space-y-3">
            <div class="flex items-start gap-2">
              <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <div>
                <p class="text-xs text-gray-500">{{ __('Address') }}</p>
                <p class="text-xs font-medium text-gray-900 mt-0.5" x-text="selected?.delivery_address || '—'"></p>
              </div>
            </div>
            <div class="flex items-center gap-2" x-show="selected?.delivery_notes">
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <div>
                <p class="text-xs text-gray-500">{{ __('Notes') }}</p>
                <p class="text-xs font-medium text-gray-900 mt-0.5" x-text="selected?.delivery_notes || '—'"></p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <div>
                <p class="text-xs text-gray-500">{{ __('Time') }}</p>
                <p class="text-xs font-medium text-gray-900 mt-0.5" x-text="selected?.time || '--:--'"></p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <div>
                <p class="text-xs text-gray-500">{{ __('Driver') }}</p>
                <p class="text-xs font-medium mt-0.5" :class="selected?.driver && selected?.driver !== 'Unassigned' ? 'text-gray-900' : 'text-red-500'" x-text="selected?.driver || 'Unassigned'"></p>
              </div>
            </div>
          </div>
        </div>

        {{-- Items with full details (filtered by active category) --}}
        <template x-if="selected?.items && selected.items.length > 0">
          <div>
            <div class="flex items-center justify-between mb-3">
              <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider" x-text="activeCategoryName + ' {{ __('Items') }}'"></h4>
              <div class="flex items-center gap-2">
                <span x-show="selected?.total_calories" class="text-[10px] font-bold text-[#6E7A25] bg-[#6E7A25]/10 px-2 py-0.5 rounded-full" x-text="selected?.total_calories + ' kcal'"></span>
                <span class="text-[10px] font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded-full" x-text="selected?.meal_count + ' items'"></span>
              </div>
            </div>
            {{-- Nutrition summary --}}
            <div class="grid grid-cols-3 gap-2 mb-3" x-show="selected?.total_protein_g || selected?.total_carbs_g || selected?.total_fat_g">
              <div class="bg-blue-50 rounded-lg p-2 text-center">
                <p class="text-[9px] font-bold text-blue-400 uppercase">{{ __('Protein') }}</p>
                <p class="text-sm font-bold text-blue-600" x-text="selected?.total_protein_g + 'g'"></p>
              </div>
              <div class="bg-amber-50 rounded-lg p-2 text-center">
                <p class="text-[9px] font-bold text-amber-400 uppercase">{{ __('Carbs') }}</p>
                <p class="text-sm font-bold text-amber-600" x-text="selected?.total_carbs_g + 'g'"></p>
              </div>
              <div class="bg-purple-50 rounded-lg p-2 text-center">
                <p class="text-[9px] font-bold text-purple-400 uppercase">{{ __('Fat') }}</p>
                <p class="text-sm font-bold text-purple-600" x-text="selected?.total_fat_g + 'g'"></p>
              </div>
            </div>
            <div class="space-y-3">
              <template x-for="(item, i) in selected.items" :key="i">
                <div class="bg-gray-50 rounded-2xl p-3.5 border border-gray-100">
                  <div class="flex items-start gap-3 mb-2">
                    <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 flex-shrink-0 overflow-hidden flex items-center justify-center shadow-sm">
                      <img x-show="item.image_url" :src="item.image_url" class="w-full h-full object-cover" alt="">
                      <svg x-show="!item.image_url" class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-gray-900" x-text="item.meal_name || item.name || 'Item'"></p>
                        <span class="text-xs font-bold text-[#6E7A25] bg-[#6E7A25]/10 px-2 py-0.5 rounded-full flex-shrink-0" x-text="'×' + (item.quantity || 1)"></span>
                      </div>
                      <p x-show="item.category_name" class="text-[10px] text-gray-400 mt-0.5" x-text="item.category_name"></p>
                      <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                        <span x-show="item.calories" class="text-[9px] font-bold text-[#6E7A25] bg-[#6E7A25]/10 px-1.5 py-0.5 rounded-full" x-text="item.calories + ' kcal'"></span>
                        <span x-show="item.protein_g" class="text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-full" x-text="'P ' + item.protein_g + 'g'"></span>
                        <span x-show="item.carbs_g" class="text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded-full" x-text="'C ' + item.carbs_g + 'g'"></span>
                        <span x-show="item.fat_g" class="text-[9px] font-bold text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded-full" x-text="'F ' + item.fat_g + 'g'"></span>
                        <span x-show="item.unit_price" class="text-[9px] font-bold text-gray-600 bg-gray-100 px-1.5 py-0.5 rounded-full" x-text="'SAR ' + item.unit_price"></span>
                      </div>
                    </div>
                  </div>
                  {{-- Ingredients --}}
                  <div x-show="item.ingredients?.length" class="mb-2">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wide mb-1">{{ __('Ingredients') }}</p>
                    <div class="flex flex-wrap items-center gap-1">
                      <template x-for="(ing, idx) in item.ingredients" :key="idx">
                        <span class="px-2 py-0.5 rounded-lg bg-white border border-gray-200 text-[10px] font-medium text-gray-700" x-text="ing"></span>
                      </template>
                    </div>
                  </div>
                  {{-- Allergens --}}
                  <div x-show="item.allergens?.length">
                    <p class="text-[9px] font-bold text-red-400 uppercase tracking-wide mb-1">{{ __('Allergens') }}</p>
                    <div class="flex flex-wrap items-center gap-1">
                      <template x-for="(a, idx) in item.allergens" :key="idx">
                        <span class="px-2 py-0.5 rounded-lg bg-red-50 border border-red-100 text-[10px] font-medium text-red-600" x-text="a"></span>
                      </template>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </template>

        {{-- Management Actions --}}
        <div class="space-y-3 pt-2">
          <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Update Status') }}</h4>
            <div class="flex gap-2">
              <select x-model="selectedStatus" class="flex-1 text-xs border border-gray-200 rounded-lg px-3 py-2.5 bg-white text-gray-700 outline-none">
                <option value="preparing">{{ __('Preparing') }}</option>
                <option value="ready_for_delivery">{{ __('Ready for Delivery') }}</option>
                <option value="out_for_delivery">{{ __('Out for Delivery') }}</option>
                <option value="delivered">{{ __('Delivered') }}</option>
              </select>
              <button @click="updateStatus()" :disabled="actionLoading"
                class="px-4 py-2.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-all disabled:opacity-60 flex items-center gap-1.5">
                <svg x-show="!actionLoading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <svg x-show="actionLoading" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                {{ __('Update') }}
              </button>
            </div>
          </div>

          <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Assign Driver') }}</h4>
            <div class="space-y-2">
              <select x-model="assignDriverId" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2.5 bg-white text-gray-700 outline-none">
                <option value="">{{ __('Select Driver') }}</option>
                <template x-for="driver in drivers" :key="driver.id">
                  <option :value="driver.id" x-text="driver.name + (driver.is_active ? '' : ' (inactive)')" :disabled="!driver.is_active"></option>
                </template>
              </select>
              <div class="flex gap-2">
                <input type="time" x-model="assignTime" class="flex-1 text-xs border border-gray-200 rounded-lg px-3 py-2.5 bg-white text-gray-700 outline-none">
                <button @click="assignDriver()" :disabled="actionLoading || !assignDriverId"
                  class="px-4 py-2.5 text-xs font-bold text-white bg-[#173327] rounded-lg hover:bg-[#1a4a3a] transition-all disabled:opacity-60 flex items-center gap-1.5">
                  <svg x-show="!actionLoading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  <svg x-show="actionLoading" class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                  {{ __('Assign') }}
                </button>
              </div>
            </div>
          </div>

          <div class="flex gap-2">
            <button @click="printOrder" class="flex-1 px-4 py-3 text-xs font-bold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
              {{ __('Print') }}
            </button>
            <button @click="cancelOrder()" :disabled="actionLoading || selected?.status === 'cancelled' || selected?.status === 'delivered'"
              class="flex-1 px-4 py-3 text-xs font-bold text-red-700 bg-red-50 rounded-xl hover:bg-red-100 transition-colors flex items-center justify-center gap-2 disabled:opacity-60">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              {{ __('Cancel') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function ordersApp() {
  return {
    categories: @json($categories),
    categorizedOrders: @json($categorizedOrders),
    mealsByCategory: @json($mealsByCategory),
    stats: @json($stats),
    drivers: @json($drivers),
    todayDate: @json($todayDate),
    activeTab: @json($categories[0]['id'] ?? 0),
    selected: null,
    selectedStatus: '',
    loading: false,
    actionLoading: false,
    includeCompleted: false,
    assignDriverId: '',
    assignTime: '',
    categoryDriverId: '',
    categoryDeliveryTime: '',

    statusClass(s) {
      const m = { delivered:'bg-green-50 text-green-700 border-green-200', out_for_delivery:'bg-blue-50 text-blue-700 border-blue-200', ready_for_delivery:'bg-indigo-50 text-indigo-700 border-indigo-200', preparing:'bg-amber-50 text-amber-700 border-amber-200', pending:'bg-gray-50 text-gray-600 border-gray-200', cancelled:'bg-red-50 text-red-600 border-red-200' };
      return m[s] || 'bg-gray-50 text-gray-600 border-gray-200';
    },
    statusLabel(s) {
      const m = { delivered:'Delivered', out_for_delivery:'Out for Delivery', ready_for_delivery:'Ready for Delivery', preparing:'Preparing', pending:'Pending', cancelled:'Cancelled' };
      return m[s] || s;
    },

    get activeOrders() {
      return this.categorizedOrders[this.activeTab] || [];
    },
    get activeMeals() {
      return this.mealsByCategory[this.activeTab] || [];
    },
    get activeCategoryName() {
      const cat = this.categories.find(c => c.id === this.activeTab);
      return cat ? cat.name : '';
    },

    init() {
      this.fetchOrders();
    },

    switchTab(catId) {
      this.activeTab = catId;
      this.categoryDriverId = '';
      this.categoryDeliveryTime = '';
    },

    toggleCompleted() {
      this.includeCompleted = !this.includeCompleted;
      this.fetchOrders();
    },

    showDetail(order) {
      this.selected = order;
      this.selectedStatus = order.status || 'preparing';
      this.assignDriverId = order.driver_id || '';
      this.assignTime = order.scheduled_at || '';
    },

    async fetchOrders() {
      this.loading = true;
      try {
        const p = new URLSearchParams();
        if (this.includeCompleted) p.set('include_completed', '1');
        const r = await fetch(`{{ route('admin.orders') }}?${p.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const d = await r.json();
        this.categories = d.categories || this.categories;
        this.categorizedOrders = d.categorizedOrders || this.categorizedOrders;
        this.mealsByCategory = d.mealsByCategory || this.mealsByCategory;
        this.stats = d.stats || this.stats;
        this.drivers = d.drivers || this.drivers;
        if (this.categories.length > 0 && !this.categories.find(c => c.id === this.activeTab)) {
          this.activeTab = this.categories[0].id;
        }
      } catch(e) { console.error('Failed to fetch orders', e); }
      finally { this.loading = false; }
    },

    async updateStatus() {
      if (!this.selected?.order_id) return;
      this.actionLoading = true;
      try {
        const r = await fetch(`{{ route('admin.orders.approve', '__ID__') }}`.replace('__ID__', this.selected.order_id), {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify({ status: this.selectedStatus })
        });
        const d = await r.json();
        if (d.success) {
          this.selected.status = this.selectedStatus;
          this.fetchOrders();
        } else {
          alert(d.message || 'Failed to update order status.');
        }
      } catch(e) { console.error(e); alert('Network error.'); }
      finally { this.actionLoading = false; }
    },

    async assignDriver() {
      if (!this.selected?.order_id || !this.assignDriverId) return;
      this.actionLoading = true;
      try {
        const scheduledAt = this.assignTime ? `{{ date('Y-m-d') }}T${this.assignTime}:00` : null;
        const r = await fetch(`{{ route('admin.orders.assign-driver', '__ID__') }}`.replace('__ID__', this.selected.order_id), {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify({ driver_id: this.assignDriverId, scheduled_at: scheduledAt })
        });
        const d = await r.json();
        if (d.success) {
          const driver = this.drivers.find(drv => drv.id == this.assignDriverId);
          this.selected.driver = driver?.name || 'Assigned';
          this.selected.driver_id = this.assignDriverId;
          this.selected.scheduled_at = this.assignTime || null;
          this.fetchOrders();
        } else {
          alert(d.message || 'Failed to assign driver.');
        }
      } catch(e) { console.error(e); alert('Network error.'); }
      finally { this.actionLoading = false; }
    },

    async assignCategoryDriver() {
      if (!this.categoryDriverId || this.activeOrders.length === 0) return;
      this.actionLoading = true;
      try {
        const orderIds = this.activeOrders.map(o => o.order_id).filter(id => id > 0);
        if (orderIds.length === 0) {
          alert('No valid orders to assign.');
          return;
        }
        const r = await fetch(`{{ route('admin.deliveries.bulk-assign-driver') }}`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify({ driver_id: parseInt(this.categoryDriverId), order_ids: orderIds })
        });
        const d = await r.json();
        if (d.success) {
          this.fetchOrders();
        } else {
          alert(d.message || 'Failed to assign driver.');
        }
      } catch(e) { console.error(e); alert('Network error.'); }
      finally { this.actionLoading = false; }
    },

    async cancelOrder() {
      if (!this.selected?.order_id) return;
      if (!confirm('{{ __('Are you sure you want to cancel this order?') }}')) return;
      this.actionLoading = true;
      try {
        const r = await fetch(`{{ route('admin.orders.approve', '__ID__') }}`.replace('__ID__', this.selected.order_id), {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
          },
          body: JSON.stringify({ status: 'cancelled' })
        });
        const d = await r.json();
        if (d.success) {
          this.selected.status = 'cancelled';
          this.fetchOrders();
        } else {
          alert(d.message || 'Failed to cancel order.');
        }
      } catch(e) { console.error(e); alert('Network error.'); }
      finally { this.actionLoading = false; }
    },

    printOrder() {
      const w = window.open('', '_blank');
      w.document.write(`<html><head><title>Order ${this.selected?.id}</title><script src="https://cdn.tailwindcss.com"><\/script></head><body class="p-8"><div class="max-w-md mx-auto bg-white p-6 rounded-xl shadow-lg">${document.getElementById('order-detail-content')?.innerHTML || ''}</div><script>window.onload=()=>setTimeout(()=>window.print(),300)<\/script></body></html>`);
      w.document.close();
    }
  }
}
</script>
@endpush
@endsection
