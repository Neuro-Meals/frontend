@extends('layouts.admin')

@section('title', __('Orders') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Orders'))

@section('content')
<style>
[x-cloak] { display: none !important; }

.orders-scroll {
  scrollbar-width: thin;
  -webkit-overflow-scrolling: touch;
}

.orders-scroll::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}

.orders-scroll::-webkit-scrollbar-thumb {
  border-radius: 999px;
  background: #d1d5db;
}

@media print {
  .no-print {
    display: none !important;
  }
}
</style>

<div x-data="ordersApp()" x-init="init()" class="space-y-4">

  {{-- KPI Cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" x-show="!loading">
    <template x-for="(s, idx) in stats" :key="s.label">
      <div class="animate__animated animate__fadeInUp rounded-2xl p-5 text-white relative overflow-hidden shadow-lg" :class="`bg-gradient-to-br ${s.gradient}`" :style="`animation-delay: ${0.1 + idx * 0.05}s; shadow-color: rgba(110, 122, 37, 0.15);`">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="absolute inset-0 opacity-[0.05]" style="background-image: repeating-linear-gradient(45deg, white 0px, white 1px, transparent 1px, transparent 12px);"></div>
        <div class="relative z-10">
          <div class="flex items-center justify-between mb-3">
            <div class="w-11 h-11 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
              <template x-if="s.icon === 'clipboard'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></template>
              <template x-if="s.icon === 'fire'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2-6 1.5 1 2 4 2 6 2-1 2.657-2.657 2.657-2.657z"/></svg></template>
              <template x-if="s.icon === 'truck'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg></template>
              <template x-if="s.icon === 'check'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></template>
              <template x-if="s.icon === 'food'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg></template>
              <template x-if="s.icon === 'flame'"><svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2.993 4.706L3 14a2 2 0 002 2h10a2 2 0 002-2l.007-9.293a1 1 0 00-1.5-.867L13 6.5l-2.5-3a1 1 0 00-1.6 0L6.5 6.5 4.493 3.84a1 1 0 00-1.5.866zM11 10a1 1 0 11-2 0 1 1 0 012 0z"/></svg></template>
              <template x-if="s.icon === 'money'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg></template>
              <template x-if="s.icon === 'shopping'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg></template>
            </div>
          </div>
          <p class="text-xs text-white/60 font-medium mb-1" x-text="s.label"></p>
          <p class="text-2xl font-bold tracking-tight" x-text="s.value"></p>
        </div>
      </div>
    </template>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" x-show="loading">
    <template x-for="i in 8">
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm animate-pulse"><div class="h-3 bg-gray-100 rounded w-1/2 mb-2"></div><div class="h-6 bg-gray-100 rounded w-3/4"></div></div>
    </template>
  </div>

  {{-- Operational Toolbar --}}
  <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
    <div class="flex flex-col xl:flex-row xl:items-center gap-4">
      <div class="flex items-center gap-3 flex-1 min-w-0">
        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center shadow-sm flex-shrink-0">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>

        <div class="min-w-0">
          <div class="flex items-center gap-2 flex-wrap">
            <p class="text-sm font-bold text-gray-900">{{ __('Today\'s Generated Orders') }}</p>
            <span class="rounded-full bg-green-50 px-2 py-0.5 text-[9px] font-black uppercase tracking-wide text-green-700 border border-green-100">
              {{ __('Live Data') }}
            </span>
          </div>
          <p class="text-[10px] text-gray-400 mt-0.5" x-text="todayDate"></p>
          <p class="text-[10px] text-gray-400 mt-0.5">
            {{ __('Last refreshed') }}:
            <span class="font-semibold text-gray-600" x-text="lastUpdatedLabel"></span>
          </p>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 xl:flex xl:items-center gap-2 w-full xl:w-auto">
        <div class="relative sm:col-span-2 xl:w-64">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
               fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <input
            type="text"
            x-model.trim="search"
            placeholder="{{ __('Search customer, order, meal or driver...') }}"
            class="w-full rounded-xl border border-gray-200 bg-gray-50 py-2.5 pl-9 pr-3 text-xs outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
        </div>

        <select
          x-model="statusFilter"
          class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-xs text-gray-700 outline-none">
          <option value="">{{ __('All Statuses') }}</option>
          <option value="pending">{{ __('Pending') }}</option>
          <option value="confirmed">{{ __('Confirmed') }}</option>
          <option value="preparing">{{ __('Preparing') }}</option>
          <option value="ready_for_delivery">{{ __('Ready for Delivery') }}</option>
          <option value="out_for_delivery">{{ __('Out for Delivery') }}</option>
          <option value="delivered">{{ __('Delivered') }}</option>
          <option value="cancelled">{{ __('Cancelled') }}</option>
        </select>

        <button @click="fetchOrders()"
          :disabled="loading"
          class="px-3 py-2.5 text-xs font-bold text-white bg-[#6E7A25] rounded-xl hover:bg-[#5a6820] transition-all shadow-sm whitespace-nowrap flex items-center justify-center gap-1.5 disabled:opacity-60">
          <svg :class="loading ? 'animate-spin' : ''" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
          {{ __('Refresh') }}
        </button>

        {{-- Keep this manual fallback button. It is intentionally not removed. --}}
        <button @click="generateOrders()"
          :disabled="generating"
          class="px-3 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-xl hover:opacity-90 transition-all shadow-sm whitespace-nowrap flex items-center justify-center gap-1.5 disabled:opacity-60">
          <svg x-show="!generating" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
          </svg>
          <svg x-show="generating" x-cloak class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
          </svg>
          <span x-text="generating ? '{{ __('Generating...') }}' : '{{ __('Generate Orders') }}'"></span>
        </button>

        <button @click="toggleCompleted()"
          :class="includeCompleted ? 'bg-[#173327] text-white' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
          class="px-3 py-2.5 text-xs font-bold border border-gray-100 rounded-xl transition-all whitespace-nowrap flex items-center justify-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span x-text="includeCompleted ? '{{ __('Hide Completed') }}' : '{{ __('Show Completed') }}'"></span>
        </button>
      </div>
    </div>

    <div x-show="notice.message" x-cloak
      class="mt-4 rounded-xl border px-4 py-3 text-xs font-semibold"
      :class="notice.type === 'success'
        ? 'border-green-100 bg-green-50 text-green-700'
        : notice.type === 'error'
          ? 'border-red-100 bg-red-50 text-red-700'
          : 'border-blue-100 bg-blue-50 text-blue-700'">
      <div class="flex items-start justify-between gap-3">
        <span x-text="notice.message"></span>
        <button type="button" @click="notice.message = ''" class="opacity-60 hover:opacity-100">×</button>
      </div>
    </div>
  </div>

  {{-- Operational Snapshot --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
      <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">{{ __('Order Readiness') }}</p>
      <div class="mt-3 flex items-end justify-between">
        <div>
          <p class="text-2xl font-black text-gray-900" x-text="operationalSummary.ready + '/' + operationalSummary.total"></p>
          <p class="text-xs text-gray-500">{{ __('ready or beyond') }}</p>
        </div>
        <div class="h-12 w-12 rounded-xl bg-indigo-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5 13l4 4L19 7"/>
          </svg>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
      <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">{{ __('Driver Coverage') }}</p>
      <div class="mt-3 flex items-end justify-between">
        <div>
          <p class="text-2xl font-black text-gray-900" x-text="operationalSummary.assignedDrivers + '/' + operationalSummary.total"></p>
          <p class="text-xs text-gray-500">{{ __('orders with drivers') }}</p>
        </div>
        <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0zM13 16V6H4v10h1m8 0h2m-6 0h4"/>
          </svg>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
      <p class="text-[10px] font-black uppercase tracking-wider text-gray-400">{{ __('Packaging Quantity') }}</p>
      <div class="mt-3 flex items-end justify-between">
        <div>
          <p class="text-2xl font-black text-gray-900" x-text="operationalSummary.totalPortions"></p>
          <p class="text-xs text-gray-500">{{ __('total portions/packages') }}</p>
        </div>
        <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center">
          <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M20 7l-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
          </svg>
        </div>
      </div>
    </div>
  </div>

  {{-- Shopping List (All ingredients needed for today) --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-show="shoppingList.length > 0">
    <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-[#173327] to-[#6E7A25] flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center shadow-sm">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
        <div>
          <h3 class="text-sm font-bold text-white">{{ __('Shopping List — Ingredients for Today') }}</h3>
          <p class="text-[10px] text-white/60">{{ __('Everything the chef needs to prepare') }}</p>
        </div>
      </div>
      <span class="px-3 py-1.5 rounded-full text-[11px] font-bold bg-white/15 text-white border border-white/20" x-text="shoppingList.length + ' {{ __('items') }}'"></span>
    </div>
    <div class="p-5 max-h-80 overflow-y-auto">
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5">
        <template x-for="(ing, idx) in shoppingList" :key="idx">
          <div class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-gradient-to-br from-gray-50 to-gray-100/50 border border-gray-100 hover:border-[#6E7A25]/30 hover:shadow-md transition-all group">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center flex-shrink-0 shadow-sm">
              <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-bold text-gray-900 truncate" x-text="ing.name"></p>
              <p class="text-[9px] text-gray-400 truncate" x-show="ing.meals.length > 0" x-text="ing.meals.slice(0, 2).join(', ') + (ing.meals.length > 2 ? ' +' + (ing.meals.length - 2) : '')"></p>
            </div>
            <span class="text-[11px] font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-2.5 py-1 rounded-full flex-shrink-0 shadow-sm" x-text="'×' + ing.total"></span>
          </div>
        </template>
      </div>
    </div>
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
            x-text="cat.count + (cat.total_quantity ? ' · ' + cat.total_quantity + 'qty' : '')"></span>
        </button>
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
      <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-[#173327]/10 text-[#173327]"
        x-text="filteredActiveOrders.length + ' {{ __('orders') }}' + (activeCategoryQty ? ' · ' + activeCategoryQty + ' {{ __('qty') }}' : '')"></span>
    </div>

    <template x-if="loading">
      <div class="px-4 py-8"><div class="space-y-3 animate-pulse"><template x-for="i in 4"><div class="h-16 bg-gray-50 rounded-xl"></div></template></div></div>
    </template>

    <template x-if="!loading && filteredActiveOrders.length === 0">
      <div class="px-4 py-16 text-center">
        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-700 mb-1">{{ __('No orders in this category yet') }}</p>
        <p class="text-xs text-gray-400">{{ __('Orders will appear here once customers place them') }}</p>
      </div>
    </template>

    <div class="divide-y divide-gray-50" x-show="!loading && filteredActiveOrders.length > 0">
      <template x-for="order in filteredActiveOrders" :key="order.order_id">
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
          {{-- Summary row --}}
          <div class="flex items-center gap-3 flex-wrap">
            <div class="min-w-0 flex-1">
              <p class="text-xs font-semibold text-gray-700 truncate" x-text="order.meal_summary"></p>
              <p class="mt-1 text-[10px] text-gray-400 truncate" x-text="orderDescription(order)"></p>
            </div>
            <div class="flex items-center gap-2 ml-auto flex-shrink-0">
              <span x-show="order.total_quantity" class="text-[10px] font-bold text-[#173327] bg-[#173327]/10 px-2 py-0.5 rounded-full" x-text="order.total_quantity + ' {{ __('qty') }}'"></span>
              <span x-show="order.total_calories" class="text-[10px] font-bold text-[#6E7A25] bg-[#6E7A25]/10 px-2 py-0.5 rounded-full" x-text="order.total_calories + ' kcal'"></span>
              <span x-show="order.category_amount" class="text-[10px] font-bold text-gray-700" x-text="'SAR ' + order.category_amount"></span>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>

  {{-- Per-Category Delivery Assignment --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4" x-show="filteredActiveOrders.length > 0">
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
          <span x-show="selected?.total_quantity" class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-white/10" x-text="selected?.total_quantity + ' {{ __('qty') }}'"></span>
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
                <span x-show="selected?.total_quantity" class="text-[10px] font-bold text-[#173327] bg-[#173327]/10 px-2 py-0.5 rounded-full" x-text="selected?.total_quantity + ' {{ __('qty') }}'"></span>
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
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25] to-[#173327] flex-shrink-0 flex items-center justify-center shadow-sm">
                      <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-bold text-gray-900" x-text="item.meal_name || item.name || 'Item'"></p>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                          <span class="text-xs font-bold text-[#6E7A25] bg-[#6E7A25]/10 px-2 py-0.5 rounded-full"
                            x-text="'×' + (item.quantity || 1)"></span>
                          <span x-show="item.preparation_quantity"
                            class="text-xs font-bold text-[#173327] bg-[#173327]/10 px-2 py-0.5 rounded-full"
                            x-text="formatPreparation(item)"></span>
                        </div>
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
    shoppingList: @json($shoppingList),

    activeTab: @json($categories[0]['id'] ?? 0),
    selected: null,
    selectedStatus: '',

    loading: false,
    actionLoading: false,
    generating: false,
    includeCompleted: false,

    search: '',
    statusFilter: '',
    lastUpdatedAt: null,
    notice: {
      type: '',
      message: ''
    },

    assignDriverId: '',
    assignTime: '',
    categoryDriverId: '',
    categoryDeliveryTime: '',

    init() {
      this.normalizeCurrentState();
      this.fetchOrders();
    },

    normalizeCurrentState() {
      this.categories = Array.isArray(this.categories)
        ? this.categories.map(category => ({
            ...category,
            id: Number(category.id || 0),
            count: Number(category.count || 0),
            total_quantity: Number(category.total_quantity || 0)
          }))
        : [];

      const normalized = {};

      Object.entries(this.categorizedOrders || {}).forEach(([categoryId, orders]) => {
        normalized[Number(categoryId)] = Array.isArray(orders)
          ? orders.map(order => this.normalizeOrder(order))
          : [];
      });

      this.categorizedOrders = normalized;
      this.drivers = Array.isArray(this.drivers) ? this.drivers : [];
      this.shoppingList = Array.isArray(this.shoppingList) ? this.shoppingList : [];

      if (
        this.categories.length > 0 &&
        !this.categories.some(category => Number(category.id) === Number(this.activeTab))
      ) {
        this.activeTab = Number(this.categories[0].id);
      }
    },

    normalizeOrder(order) {
      const items = Array.isArray(order?.items)
        ? order.items.map(item => ({
            ...item,
            quantity: Number(item.quantity || 1),
            preparation_quantity:
              item.preparation_quantity !== null &&
              item.preparation_quantity !== undefined
                ? Number(item.preparation_quantity)
                : null,
            preparation_unit: item.preparation_unit || null
          }))
        : [];

      const customer =
        order?.customer ||
        order?.customer_name ||
        order?.user_name ||
        'Unknown Customer';

      const orderId = Number(
        order?.order_id ||
        order?.id ||
        0
      );

      return {
        ...order,
        order_id: orderId,
        id:
          order?.order_number ||
          order?.id ||
          (orderId ? `#${orderId}` : '—'),
        customer,
        status: String(order?.status || 'pending').toLowerCase(),
        driver:
          order?.driver ||
          order?.driver_name ||
          'Unassigned',
        driver_id:
          order?.driver_id ||
          order?.driver?.id ||
          '',
        time:
          order?.time ||
          order?.delivery_time ||
          order?.scheduled_at ||
          '--:--',
        meal_summary:
          order?.meal_summary ||
          items
            .map(item => item.meal_name || item.name)
            .filter(Boolean)
            .join(', ') ||
          'No meal description',
        items,
        total_quantity:
          Number(
            order?.total_quantity ??
            items.reduce(
              (sum, item) => sum + Number(item.quantity || 1),
              0
            )
          ),
        total_calories: Number(order?.total_calories || 0),
        category_amount: Number(order?.category_amount || order?.total_amount || 0),
        delivery_address:
          order?.delivery_address ||
          order?.address ||
          '—'
      };
    },

    statusClass(statusValue) {
      const status = String(statusValue || '').toLowerCase();

      const classes = {
        delivered: 'bg-green-50 text-green-700 border-green-200',
        out_for_delivery: 'bg-blue-50 text-blue-700 border-blue-200',
        ready_for_delivery: 'bg-indigo-50 text-indigo-700 border-indigo-200',
        ready_for_pickup: 'bg-indigo-50 text-indigo-700 border-indigo-200',
        preparing: 'bg-amber-50 text-amber-700 border-amber-200',
        confirmed: 'bg-cyan-50 text-cyan-700 border-cyan-200',
        scheduled: 'bg-purple-50 text-purple-700 border-purple-200',
        pending: 'bg-gray-50 text-gray-600 border-gray-200',
        cancelled: 'bg-red-50 text-red-600 border-red-200',
        failed: 'bg-red-50 text-red-600 border-red-200'
      };

      return classes[status] || classes.pending;
    },

    statusLabel(statusValue) {
      const status = String(statusValue || '').toLowerCase();

      const labels = {
        delivered: '{{ __('Delivered') }}',
        out_for_delivery: '{{ __('Out for Delivery') }}',
        ready_for_delivery: '{{ __('Ready for Delivery') }}',
        ready_for_pickup: '{{ __('Ready for Pickup') }}',
        preparing: '{{ __('Preparing') }}',
        confirmed: '{{ __('Confirmed') }}',
        scheduled: '{{ __('Scheduled') }}',
        pending: '{{ __('Pending') }}',
        cancelled: '{{ __('Cancelled') }}',
        failed: '{{ __('Failed') }}'
      };

      return labels[status] || status.replaceAll('_', ' ');
    },

    get activeOrders() {
      return this.categorizedOrders[Number(this.activeTab)] || [];
    },

    get filteredActiveOrders() {
      const needle = String(this.search || '').trim().toLowerCase();
      const status = String(this.statusFilter || '').trim().toLowerCase();

      return this.activeOrders.filter(order => {
        if (
          status &&
          String(order.status || '').toLowerCase() !== status
        ) {
          return false;
        }

        if (!needle) {
          return true;
        }

        const searchable = [
          order.id,
          order.order_number,
          order.customer,
          order.customer_email,
          order.customer_phone,
          order.driver,
          order.meal_summary,
          order.delivery_address,
          ...(order.items || []).flatMap(item => [
            item.meal_name,
            item.name,
            item.category_name,
            item.preparation_unit
          ])
        ]
          .filter(Boolean)
          .join(' ')
          .toLowerCase();

        return searchable.includes(needle);
      });
    },

    get allOrders() {
      return Object.values(this.categorizedOrders || {})
        .flat()
        .filter((order, index, array) =>
          array.findIndex(candidate =>
            Number(candidate.order_id) === Number(order.order_id)
          ) === index
        );
    },

    get operationalSummary() {
      const orders = this.allOrders;

      const readyStatuses = new Set([
        'ready_for_delivery',
        'ready_for_pickup',
        'out_for_delivery',
        'delivered'
      ]);

      return {
        total: orders.length,
        ready: orders.filter(order =>
          readyStatuses.has(String(order.status || '').toLowerCase())
        ).length,
        assignedDrivers: orders.filter(order =>
          Number(order.driver_id || 0) > 0 ||
          (
            order.driver &&
            String(order.driver).toLowerCase() !== 'unassigned'
          )
        ).length,
        totalPortions: orders.reduce(
          (sum, order) =>
            sum + Number(order.total_quantity || 0),
          0
        )
      };
    },

    get activeMeals() {
      return this.mealsByCategory[this.activeTab] || [];
    },

    get activeCategoryName() {
      const category = this.categories.find(
        item => Number(item.id) === Number(this.activeTab)
      );

      return category ? category.name : '';
    },

    get activeCategoryQty() {
      const category = this.categories.find(
        item => Number(item.id) === Number(this.activeTab)
      );

      return category
        ? Number(category.total_quantity || 0)
        : 0;
    },

    get activeIngredientTotals() {
      const totals = {};

      for (const order of this.filteredActiveOrders) {
        for (const item of order.items || []) {
          const quantity = Number(item.quantity || 1);

          for (const ingredient of item.ingredients || []) {
            const key = String(ingredient).trim().toLowerCase();

            if (!key) continue;

            if (!totals[key]) {
              totals[key] = {
                name: String(ingredient).trim(),
                total: 0
              };
            }

            totals[key].total += quantity;
          }
        }
      }

      return Object.values(totals)
        .sort((a, b) => b.total - a.total);
    },

    get lastUpdatedLabel() {
      if (!this.lastUpdatedAt) {
        return '{{ __('Not yet') }}';
      }

      return this.lastUpdatedAt.toLocaleTimeString(
        undefined,
        {
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit'
        }
      );
    },

    switchTab(categoryId) {
      this.activeTab = Number(categoryId);
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
      this.assignTime = this.timeInputValue(
        order.delivery_time ||
        order.scheduled_at ||
        order.time
      );
    },

    timeInputValue(value) {
      const match = String(value || '').match(/(\d{2}):(\d{2})/);
      return match ? `${match[1]}:${match[2]}` : '';
    },

    formatPreparation(item) {
      if (
        item?.preparation_quantity === null ||
        item?.preparation_quantity === undefined ||
        item?.preparation_quantity === ''
      ) {
        return '';
      }

      return `${Number(item.preparation_quantity)} ${item.preparation_unit || 'portion'}`;
    },

    orderDescription(order) {
      const parts = [];

      if (order.driver && order.driver !== 'Unassigned') {
        parts.push(`{{ __('Driver') }}: ${order.driver}`);
      } else {
        parts.push('{{ __('Driver not assigned') }}');
      }

      if (order.delivery_address && order.delivery_address !== '—') {
        parts.push(order.delivery_address);
      }

      const amounts = (order.items || [])
        .filter(item => item.preparation_quantity)
        .slice(0, 3)
        .map(item =>
          `${item.meal_name || item.name}: ${this.formatPreparation(item)}`
        );

      if (amounts.length > 0) {
        parts.push(amounts.join(', '));
      }

      return parts.join(' · ');
    },

    showNotice(type, message) {
      this.notice = {
        type,
        message
      };
    },

    async readJson(response) {
      const contentType = response.headers.get('content-type') || '';
      let data = {};

      if (contentType.includes('application/json')) {
        data = await response.json();
      } else {
        const body = await response.text();
        data = {
          message: body || `HTTP ${response.status}`
        };
      }

      if (!response.ok) {
        throw new Error(
          data.message ||
          data.detail ||
          `HTTP ${response.status}`
        );
      }

      return data;
    },

    async fetchOrders() {
      this.loading = true;

      try {
        const params = new URLSearchParams();

        if (this.includeCompleted) {
          params.set('include_completed', '1');
        }

        const response = await fetch(
          `{{ route('admin.orders') }}?${params.toString()}`,
          {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          }
        );

        const data = await this.readJson(response);

        this.categories = data.categories || [];
        this.categorizedOrders = data.categorizedOrders || {};
        this.mealsByCategory = data.mealsByCategory || {};
        this.stats = data.stats || [];
        this.drivers = data.drivers || [];
        this.shoppingList = data.shoppingList || [];
        this.todayDate = data.todayDate || this.todayDate;

        this.normalizeCurrentState();
        this.lastUpdatedAt = new Date();
      } catch (error) {
        console.error('Failed to fetch orders', error);

        this.showNotice(
          'error',
          error.message ||
          '{{ __('Unable to load today\'s generated orders.') }}'
        );
      } finally {
        this.loading = false;
      }
    },

    async generateOrders() {
      if (this.generating) return;

      this.generating = true;
      this.showNotice(
        'info',
        '{{ __('Checking today\'s meal assignments and generating any missing orders...') }}'
      );

      try {
        const response = await fetch(
          `{{ route('admin.orders.generate') }}`,
          {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN':
                document
                  .querySelector('meta[name="csrf-token"]')
                  ?.getAttribute('content') || ''
            },
            body: JSON.stringify({
              action: 'generate'
            })
          }
        );

        const data = await this.readJson(response);

        const result =
          data.data ||
          data.result ||
          data;

        const created = Number(
          result.orders_created ||
          result.created_count ||
          result.created ||
          0
        );

        const existing = Number(
          result.already_existing ||
          result.existing_count ||
          result.existing ||
          0
        );

        this.showNotice(
          'success',
          data.message ||
          `{{ __('Order generation completed.') }} ${created} {{ __('created') }}, ${existing} {{ __('already existed') }}.`
        );

        await this.fetchOrders();
      } catch (error) {
        console.error('Failed to generate orders', error);

        this.showNotice(
          'error',
          error.message ||
          '{{ __('Failed to generate today\'s orders.') }}'
        );
      } finally {
        this.generating = false;
      }
    },

    async updateStatus() {
      if (!this.selected?.order_id) return;

      this.actionLoading = true;

      try {
        const response = await fetch(
          `{{ route('admin.orders.approve', '__ID__') }}`.replace(
            '__ID__',
            this.selected.order_id
          ),
          {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN':
                document
                  .querySelector('meta[name="csrf-token"]')
                  ?.getAttribute('content') || ''
            },
            body: JSON.stringify({
              status: this.selectedStatus
            })
          }
        );

        const data = await this.readJson(response);

        if (data.success === false) {
          throw new Error(
            data.message ||
            '{{ __('Failed to update order status.') }}'
          );
        }

        this.selected.status = this.selectedStatus;
        this.showNotice(
          'success',
          data.message ||
          '{{ __('Order status updated successfully.') }}'
        );

        await this.fetchOrders();
      } catch (error) {
        console.error(error);
        this.showNotice('error', error.message);
      } finally {
        this.actionLoading = false;
      }
    },

    async assignDriver() {
      if (
        !this.selected?.order_id ||
        !this.assignDriverId
      ) {
        return;
      }

      this.actionLoading = true;

      try {
        const scheduledAt = this.assignTime
          ? `${new Date().toISOString().slice(0, 10)}T${this.assignTime}:00`
          : null;

        const response = await fetch(
          `{{ route('admin.orders.assign-driver', '__ID__') }}`.replace(
            '__ID__',
            this.selected.order_id
          ),
          {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN':
                document
                  .querySelector('meta[name="csrf-token"]')
                  ?.getAttribute('content') || ''
            },
            body: JSON.stringify({
              driver_id: Number(this.assignDriverId),
              scheduled_at: scheduledAt
            })
          }
        );

        const data = await this.readJson(response);

        if (data.success === false) {
          throw new Error(
            data.message ||
            '{{ __('Failed to assign driver.') }}'
          );
        }

        const driver = this.drivers.find(
          item => Number(item.id) === Number(this.assignDriverId)
        );

        this.selected.driver =
          driver?.name ||
          '{{ __('Assigned') }}';

        this.selected.driver_id =
          Number(this.assignDriverId);

        this.showNotice(
          'success',
          data.message ||
          '{{ __('Driver assigned successfully.') }}'
        );

        await this.fetchOrders();
      } catch (error) {
        console.error(error);
        this.showNotice('error', error.message);
      } finally {
        this.actionLoading = false;
      }
    },

    async assignCategoryDriver() {
      if (
        !this.categoryDriverId ||
        this.filteredActiveOrders.length === 0
      ) {
        return;
      }

      this.actionLoading = true;

      try {
        const orderIds = this.filteredActiveOrders
          .map(order => Number(order.order_id))
          .filter(orderId => orderId > 0);

        if (orderIds.length === 0) {
          throw new Error(
            '{{ __('No valid orders are available for bulk assignment.') }}'
          );
        }

        const response = await fetch(
          `{{ route('admin.deliveries.bulk-assign-driver') }}`,
          {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN':
                document
                  .querySelector('meta[name="csrf-token"]')
                  ?.getAttribute('content') || ''
            },
            body: JSON.stringify({
              driver_id: Number(this.categoryDriverId),
              order_ids: orderIds,
              delivery_time: this.categoryDeliveryTime || null
            })
          }
        );

        const data = await this.readJson(response);

        if (data.success === false) {
          throw new Error(
            data.message ||
            '{{ __('Failed to assign driver.') }}'
          );
        }

        this.showNotice(
          'success',
          data.message ||
          '{{ __('Driver assigned to all visible category orders.') }}'
        );

        await this.fetchOrders();
      } catch (error) {
        console.error(error);
        this.showNotice('error', error.message);
      } finally {
        this.actionLoading = false;
      }
    },

    async cancelOrder() {
      if (!this.selected?.order_id) return;

      if (
        !confirm(
          '{{ __('Are you sure you want to cancel this order?') }}'
        )
      ) {
        return;
      }

      this.actionLoading = true;

      try {
        const response = await fetch(
          `{{ route('admin.orders.approve', '__ID__') }}`.replace(
            '__ID__',
            this.selected.order_id
          ),
          {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN':
                document
                  .querySelector('meta[name="csrf-token"]')
                  ?.getAttribute('content') || ''
            },
            body: JSON.stringify({
              status: 'cancelled'
            })
          }
        );

        const data = await this.readJson(response);

        if (data.success === false) {
          throw new Error(
            data.message ||
            '{{ __('Failed to cancel order.') }}'
          );
        }

        this.selected.status = 'cancelled';
        this.showNotice(
          'success',
          data.message ||
          '{{ __('Order cancelled successfully.') }}'
        );

        await this.fetchOrders();
      } catch (error) {
        console.error(error);
        this.showNotice('error', error.message);
      } finally {
        this.actionLoading = false;
      }
    },

    printOrder() {
      const printWindow = window.open('', '_blank');

      if (!printWindow) {
        this.showNotice(
          'error',
          '{{ __('The browser blocked the print window.') }}'
        );
        return;
      }

      printWindow.document.write(`
        <html>
          <head>
            <title>Order ${this.selected?.id || ''}</title>
            <script src="https://cdn.tailwindcss.com"><\/script>
          </head>
          <body class="p-8 bg-gray-50">
            <div class="max-w-2xl mx-auto bg-white p-6 rounded-xl shadow-lg">
              ${document.getElementById('order-detail-content')?.innerHTML || ''}
            </div>
            <script>
              window.onload = () =>
                setTimeout(() => window.print(), 300);
            <\/script>
          </body>
        </html>
      `);

      printWindow.document.close();
    }
  };
}
</script>
@endpush
@endsection
