@extends('layouts.admin')

@section('title', __('Customers') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Customers'))

@section('content')
<style>
[x-cloak] { display: none !important; }

.assignment-modal-shell {
  width: min(100%, 92rem);
  height: min(94dvh, 980px);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.assignment-modal-body {
  min-height: 0;
  overflow-y: auto;
  overscroll-behavior: contain;
}

.assignment-day-tabs {
  scrollbar-width: thin;
  -webkit-overflow-scrolling: touch;
}

.assignment-day-tabs::-webkit-scrollbar {
  height: 5px;
}

.assignment-day-tabs::-webkit-scrollbar-thumb {
  border-radius: 999px;
  background: #d1d5db;
}

.assignment-meal-list {
  max-height: 18rem;
  overflow-y: auto;
  overscroll-behavior: contain;
}

.assignment-history-shell {
  width: min(100%, 74rem);
  height: min(92dvh, 900px);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

@media (max-width: 767px) {
  .assignment-modal-overlay {
    align-items: stretch !important;
    padding: 0 !important;
  }

  .assignment-modal-shell,
  .assignment-history-shell {
    width: 100%;
    height: 100dvh;
    max-height: 100dvh;
    border-radius: 0 !important;
  }

  .assignment-modal-header,
  .assignment-modal-footer {
    flex: 0 0 auto;
  }

  .assignment-modal-body {
    padding: 1rem !important;
  }

  .assignment-meal-list {
    max-height: 15rem;
  }
}

/* ============================================================
   CUSTOMER PAGE RESPONSIVE LAYOUT
   ============================================================ */
.customers-page,
.customers-page * {
  box-sizing: border-box;
}

.customers-page {
  width: 100%;
  min-width: 0;
  overflow-x: hidden;
}

.customers-tab-navigation {
  display: flex;
  gap: .5rem;
  max-width: 100%;
  overflow-x: auto;
  padding-bottom: .25rem;
  scrollbar-width: thin;
  -webkit-overflow-scrolling: touch;
}

.customers-tab-navigation > button {
  flex: 0 0 auto;
  white-space: nowrap;
}

.customers-filter-bar {
  display: grid;
  grid-template-columns: minmax(14rem, 1fr) repeat(2, minmax(9rem, auto)) auto;
  gap: .5rem;
  align-items: center;
}

.customers-table-scroll {
  width: 100%;
  overflow-x: auto;
  overscroll-behavior-inline: contain;
  -webkit-overflow-scrolling: touch;
}

.customers-table {
  width: 100%;
  min-width: 880px;
}

.customer-detail-panel {
  width: min(100%, 32rem);
  max-width: 100vw;
}

/*
 * The modal is inside the admin content layout. Keeping its shell at 100%
 * of the available parent width prevents it from extending underneath or
 * beyond the sidebar at normal browser zoom.
 */
.assignment-modal-overlay {
  position: fixed;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 0;
  overflow: hidden;
  padding: clamp(.65rem, 1.5vw, 1.25rem) !important;
}

.assignment-modal-shell {
  width: min(100%, 86rem);
  max-width: 100%;
  height: min(92dvh, 900px);
  max-height: calc(100dvh - 1.3rem);
  min-width: 0;
  margin: 0 auto;
  border-radius: 1.25rem;
}

.assignment-history-shell {
  width: min(100%, 74rem);
  max-width: 100%;
  height: min(92dvh, 900px);
  max-height: calc(100dvh - 1.3rem);
  min-width: 0;
  margin: 0 auto;
}

.assignment-modal-header {
  min-width: 0;
}

.assignment-modal-header > div:first-child {
  min-width: 0;
}

.assignment-modal-body {
  width: 100%;
  min-width: 0;
  min-height: 0;
  overflow-x: hidden;
  overflow-y: auto;
  padding-bottom: 1.25rem;
}

.assignment-modal-body form,
.assignment-modal-body section,
.assignment-modal-body article,
.assignment-modal-body .grid,
.assignment-modal-body .flex {
  min-width: 0;
}

.assignment-modal-footer {
  width: 100%;
  flex: 0 0 auto;
  box-shadow: 0 -8px 24px rgba(15, 23, 42, .05);
}

.assignment-modal-footer button {
  min-height: 2.75rem;
}

.assignment-meal-list {
  max-height: min(18rem, 36dvh);
}

/* Prevent long values from forcing modal sections beyond their columns. */
.assignment-modal-body p,
.assignment-modal-body span,
.assignment-modal-body div {
  overflow-wrap: anywhere;
}

@media (max-width: 1279px) {
  .customers-filter-bar {
    grid-template-columns: minmax(12rem, 1fr) repeat(2, minmax(8rem, 11rem)) auto;
  }

  .assignment-modal-shell {
    width: 100%;
    height: 94dvh;
  }

  .assignment-modal-body {
    padding-inline: 1rem !important;
  }
}

@media (max-width: 1023px) {
  .customers-filter-bar {
    grid-template-columns: 1fr 1fr;
  }

  .customers-filter-bar > :first-child {
    grid-column: 1 / -1;
  }

  .customers-filter-bar > button {
    width: 100%;
  }

  .assignment-modal-overlay {
    align-items: stretch !important;
    padding: .5rem !important;
  }

  .assignment-modal-shell,
  .assignment-history-shell {
    height: calc(100dvh - 1rem);
    max-height: calc(100dvh - 1rem);
    border-radius: 1rem !important;
  }

  .assignment-meal-list {
    max-height: 15rem;
  }
}

@media (max-width: 767px) {
  .customers-page {
    margin-inline: -.25rem;
  }

  .customers-tab-navigation {
    margin-inline: -.25rem;
    padding-inline: .25rem;
  }

  .customers-filter-bar {
    grid-template-columns: 1fr;
  }

  .customers-filter-bar > :first-child {
    grid-column: auto;
  }

  .customers-filter-bar select,
  .customers-filter-bar button,
  .customers-filter-bar > div {
    width: 100%;
    min-width: 0 !important;
  }

  .customers-table {
    min-width: 760px;
  }

  .customer-detail-panel {
    width: 100%;
    max-width: 100%;
  }

  .assignment-modal-overlay {
    padding: 0 !important;
  }

  .assignment-modal-shell,
  .assignment-history-shell {
    width: 100%;
    height: 100dvh;
    max-height: 100dvh;
    border-radius: 0 !important;
  }

  .assignment-modal-header {
    padding: .9rem 1rem !important;
  }

  .assignment-modal-header h3 {
    font-size: 1rem !important;
  }

  .assignment-modal-header p {
    font-size: .72rem !important;
  }

  .assignment-modal-body {
    padding: 1rem !important;
  }

  .assignment-modal-footer {
    padding: .75rem 1rem !important;
  }

  .assignment-modal-footer > div,
  .assignment-modal-footer {
    gap: .5rem !important;
  }

  .assignment-modal-footer button {
    width: 100%;
  }

  .assignment-meal-list {
    max-height: none;
  }
}

@media (max-width: 479px) {
  .customers-table {
    min-width: 700px;
  }

  .assignment-modal-body {
    padding-inline: .75rem !important;
  }

  .assignment-modal-footer {
    padding-inline: .75rem !important;
  }
}

</style>

<div x-data="customersApp()" x-init="init()" class="customers-page space-y-4">

  {{-- Overview KPI Cards --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" x-show="!loading">
    <template x-for="(s, i) in stats" :key="s.label">
      <div class="animate__animated animate__fadeInUp bg-gradient-to-br rounded-2xl p-5 text-white relative overflow-hidden shadow-lg" :class="s.bg" :style="`animation-delay: ${0.1 + i * 0.1}s`">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
        <div class="relative z-10">
          <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center mb-3">
            <template x-if="s.icon === 'users'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></template>
            <template x-if="s.icon === 'check'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></template>
            <template x-if="s.icon === 'shopping'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/></svg></template>
            <template x-if="s.icon === 'money'"><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></template>
          </div>
          <p class="text-xs text-white/60 font-medium mb-1" x-text="s.label"></p>
          <p class="text-2xl font-bold tracking-tight" x-text="s.value"></p>
        </div>
      </div>
    </template>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" x-show="loading">
    <template x-for="i in 4" :key="i">
      <div class="h-32 bg-gray-100 rounded-2xl animate-pulse"></div>
    </template>
  </div>

  {{-- Tab Navigation --}}
  <div class="customers-tab-navigation">
    <button type="button" @click="switchTab('all')" :class="activeTab === 'all' ? 'bg-[#6E7A25] text-white shadow-md' : 'bg-white text-gray-500 border border-gray-100'" class="px-4 py-2 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      {{ __('All Customers') }}
    </button>
    <button type="button" @click="switchTab('paid')" :class="activeTab === 'paid' ? 'bg-green-600 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-100'" class="px-4 py-2 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.062-.18-2.087-.514-3.044z"/></svg>
      {{ __('Waiting for Meals') }}
    </button>
    <button type="button" @click="switchTab('served')" :class="activeTab === 'served' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-500 border border-gray-100'" class="px-4 py-2 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
      {{ __('Meals Served') }}
    </button>
  </div>

  {{-- Filter Bar --}}
  <div class="customers-filter-bar bg-white rounded-xl border border-gray-100 p-3 shadow-sm">
    <div class="flex items-center bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100 flex-1 min-w-[160px]">
      <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" x-model="search" @input.debounce.300ms="page = 1; fetchCustomers()" placeholder="{{ __('Search customers...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400 w-20">
    </div>
    <select x-model="statusFilter" @change="page = 1; fetchCustomers()" class="text-xs border border-gray-100 rounded-lg px-2 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer">
      <option value="">{{ __('All Status') }}</option>
      <option value="active">{{ __('Active') }}</option>
      <option value="paused">{{ __('Paused') }}</option>
      <option value="cancelled">{{ __('Cancelled') }}</option>
      <option value="inactive">{{ __('Inactive') }}</option>
    </select>
    <select x-model="planFilter" @change="page = 1; fetchCustomers()" class="text-xs border border-gray-100 rounded-lg px-2 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer">
      <option value="">{{ __('All Plans') }}</option>
      <template x-for="p in plans" :key="p.id">
        <option :value="p.id" x-text="p.name"></option>
      </template>
    </select>
    <button @click="fetchCustomers()" class="px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-all shadow-sm whitespace-nowrap">
      <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
      {{ __('Refresh') }}
    </button>
  </div>

  {{-- Customers Table --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
    <div class="customers-table-scroll">
      <table class="customers-table text-sm">
        <thead>
          <tr class="text-left text-[10px] text-gray-400 bg-gray-50/50 border-b border-gray-100">
            <th class="px-4 py-3 font-medium">{{ __('Customer') }}</th>
            <th class="px-4 py-3 font-medium">{{ __('Contact') }}</th>
            <th class="px-4 py-3 font-medium">{{ __('Plan') }}</th>
            <th class="px-4 py-3 font-medium">{{ __('Orders') }}</th>
            <th class="px-4 py-3 font-medium">{{ __('Spent') }}</th>
            <th class="px-4 py-3 font-medium">{{ __('Status') }}</th>
            <th class="px-4 py-3 font-medium">{{ __('Joined') }}</th>
            <th class="px-4 py-3 font-medium text-right">{{ __('Actions') }}</th>
          </tr>
        </thead>
        <tbody>
          <template x-if="loading && customers.length === 0">
            <tr><td colspan="8" class="px-4 py-8"><div class="space-y-2 animate-pulse"><template x-for="i in 4" :key="i"><div class="h-10 bg-gray-50 rounded"></div></template></div></td></tr>
          </template>
          <template x-if="!loading && customers.length === 0">
            <tr><td colspan="8" class="px-4 py-12 text-center">
              <div class="flex flex-col items-center">
                <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mb-3">
                  <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p
                  class="text-xs font-medium text-gray-400"
                  x-text="
                    activeTab === 'served'
                      ? '{{ __('No customers with delivered meals found') }}'
                      : '{{ __('No customers found') }}'
                  ">
                </p>
                <p
                  class="text-[10px] text-gray-300 mt-0.5"
                  x-text="
                    activeTab === 'served'
                      ? '{{ __('Customers appear here after at least one order is marked Delivered.') }}'
                      : '{{ __('Customers will appear here once registered') }}'
                  ">
                </p>
              </div>
            </td></tr>
          </template>
          <template x-for="c in customers" :key="c.id">
            <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
              <td class="px-4 py-3">
                <div class="flex items-center gap-2.5">
                  <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-sm" x-text="c.name?.charAt(0)?.toUpperCase()"></div>
                  <div>
                    <p class="text-xs font-semibold text-gray-900" x-text="c.name"></p>
                    <p class="text-[10px] text-gray-400" x-text="c.id ? '#' + c.id : ''"></p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-3">
                <p class="text-xs text-gray-600" x-text="c.email"></p>
                <p class="text-[10px] text-gray-400" x-text="c.phone || '—'"></p>
              </td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border whitespace-nowrap"
                  :style="`background: ${c.plan_color}15; color: ${c.plan_color}; border-color: ${c.plan_color}30`">
                  <span class="w-1.5 h-1.5 rounded-full" :style="`background: ${c.plan_color}`"></span>
                  <span x-text="c.plan"></span>
                </span>
              </td>
              <td class="px-4 py-3">
                <div>
                  <span class="text-xs font-bold text-gray-900" x-text="c.orders"></span>
                  <p
                    x-show="Number(c.delivered_orders_count || 0) > 0"
                    class="mt-0.5 text-[9px] font-bold text-blue-600"
                    x-text="Number(c.delivered_orders_count || 0) + ' {{ __('served') }}'">
                  </p>
                </div>
              </td>
              <td class="px-4 py-3"><span class="text-xs font-bold text-[#173327]" x-text="'SAR ' + Number(c.spent || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"></span></td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border whitespace-nowrap" :class="statusClass(c.status)">
                  <span x-text="c.status?.charAt(0)?.toUpperCase() + c.status?.slice(1)"></span>
                </span>
              </td>
              <td class="px-4 py-3 text-xs text-gray-400" x-text="c.joined_formatted || c.joined"></td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-1">
                  <button @click.stop="showDetail(c)" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#6E7A25] hover:bg-[#6E7A25]/10 transition-all" title="{{ __('View') }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>
                  <button @click.stop="openEdit(c)" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="{{ __('Edit') }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button @click.stop="confirmDelete(c)" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all" title="{{ __('Delete') }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-50 flex items-center justify-between">
      <p class="text-[10px] text-gray-400" x-text="`{{ __('Showing') }} ${(this.page - 1) * 20 + 1}-${(this.page - 1) * 20 + customers.length} {{ __('of') }} ${totalCount} {{ __('customers') }}`"></p>
      <div class="flex items-center gap-1">
        <button @click="prevPage" x-show="page > 1" class="px-2.5 py-1 text-[10px] font-medium text-gray-500 rounded-lg hover:bg-gray-50 transition-colors">{{ __('Prev') }}</button>
        <span class="px-2 py-1 text-[10px] font-bold text-white bg-[#6E7A25] rounded-lg" x-text="page"></span>
        <button @click="nextPage" x-show="hasMore" class="px-2.5 py-1 text-[10px] font-medium text-gray-500 rounded-lg hover:bg-gray-50 transition-colors">{{ __('Next') }}</button>
      </div>
    </div>
  </div>
  {{-- Customer Detail Slide-Out Panel --}}
  <div x-show="selected" class="fixed inset-0 z-50 flex justify-end" style="display: none">
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="selected = null"></div>
    <div class="customer-detail-panel relative bg-white shadow-2xl h-full overflow-y-auto" @click.outside="selected = null">

      {{-- Header --}}
      <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] p-6 text-white sticky top-0 z-10">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-bold">{{ __('Customer Details') }}</h3>
          <button @click="selected = null" class="text-white/60 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div  >
        <div class="flex items-center gap-4">
          <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-lg" x-text="selected?.name?.charAt(0)?.toUpperCase()"></div>
          <div class="flex-1 min-w-0">
            <p class="text-lg font-bold truncate" x-text="selected?.name"></p>
            <p class="text-xs text-white/60 truncate" x-text="selected?.email"></p>
            <div class="flex items-center gap-2 mt-1.5">
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border border-white/20 bg-white/10" :class="statusClass(selected?.status)">
                <span x-text="selected?.status?.charAt(0)?.toUpperCase() + selected?.status?.slice(1)"></span>
              </span>
              <span class="text-[10px] text-white/50" x-text="'#' + (selected?.id || '')"></span>
            </div>
          </div>
        </div>
      </div>

      <div class="p-5 space-y-5">

        {{-- Loading --}}
        <div x-show="detailLoading" class="flex items-center justify-center py-8">
          <svg class="w-8 h-8 text-gray-200 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </div>

        {{-- Stats Mini Cards --}}
        <div x-show="!detailLoading" class="grid grid-cols-2 gap-3">
          <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="relative z-10">
              <p class="text-xs text-white/60 font-medium">{{ __('Total Spent') }}</p>
              <p class="text-xl font-bold mt-1" x-text="'SAR ' + Number(selected?.customerStats?.total_spent || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"></p>
            </div>
          </div>
          <div class="bg-gradient-to-br from-[#033133] to-[#025C5F] rounded-xl p-4 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="relative z-10">
              <p class="text-xs text-white/60 font-medium">{{ __('Total Orders') }}</p>
              <p class="text-xl font-bold mt-1" x-text="selected?.customerStats?.total_orders || 0"></p>
            </div>
          </div>
          <div class="bg-gradient-to-br from-[#6E7A25] to-[#949B50] rounded-xl p-4 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="relative z-10">
              <p class="text-xs text-white/60 font-medium">{{ __('Payments') }}</p>
              <p class="text-xl font-bold mt-1" x-text="(selected?.customerStats?.successful_payments || 0) + '/' + (selected?.customerStats?.total_payments || 0)"></p>
            </div>
          </div>
          <div class="bg-gradient-to-br from-[#173327] to-[#033133] rounded-xl p-4 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <div class="relative z-10">
              <p class="text-xs text-white/60 font-medium">{{ __('Subscriptions') }}</p>
              <p class="text-xl font-bold mt-1" x-text="(selected?.customerStats?.active_subscriptions || 0) + ' ' + '{{ __('active') }}'"></p>
            </div>
          </div>
        </div>

        {{-- Quick Actions --}}
        <div x-show="!detailLoading" class="space-y-2">
          <div class="flex gap-2">
            <button @click="openEdit(selected)" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
              <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              {{ __('Edit') }}
            </button>
          </div>
          <div x-show="selected?.customerStats?.successful_payments > 0" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <button
              x-show="customerHasAssignments(selected)"
              @click="openAssignedMeals(selected)"
              class="w-full px-3 py-2.5 text-xs font-bold rounded-lg border border-[#6E7A25]/25 bg-[#6E7A25]/10 text-[#59651f] hover:bg-[#6E7A25]/20 transition-all">
              <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
              </svg>
              {{ __('View Assigned Meals') }}
            </button>

            <button
              @click="openAssignMeal(selected)"
              class="w-full px-3 py-2.5 text-xs font-bold rounded-lg bg-gradient-to-r from-[#033133] to-[#025C5F] text-white hover:shadow-md transition-all"
              :class="customerHasAssignments(selected) ? '' : 'sm:col-span-2'">
              <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4v16m8-8H4"/>
              </svg>
              <span x-text="customerHasAssignments(selected)
                ? '{{ __('Assign More Meals') }}'
                : '{{ __('Assign Meal & Driver') }}'"></span>
            </button>
          </div>

          <div
            x-show="selected?.assignment_summary?.has_assignments"
            class="rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2.5 text-xs text-emerald-800">
            <span class="font-bold" x-text="selected?.assignment_summary?.total_assignments || 0"></span>
            {{ __('meal-category assignments') }}
            <span class="mx-1">·</span>
            <span class="font-bold" x-text="selected?.assignment_summary?.assigned_week_count || 0"></span>
            {{ __('weeks assigned') }}
          </div>
          <div x-show="!(selected?.customerStats?.successful_payments > 0)" class="flex gap-2">
            <div class="flex-1 px-3 py-2 text-xs font-medium rounded-lg bg-amber-50 border border-amber-100 text-amber-700 text-center">
              {{ __('Assign Meal & Driver available after payment.') }}
            </div>
          </div>
          <button @click="selected?.customerStats?.successful_payments > 0 && generateOrderForCustomer(selected)" :disabled="!(selected?.customerStats?.successful_payments > 0)" :class="selected?.customerStats?.successful_payments > 0 ? 'from-amber-500 to-orange-500 hover:shadow-md' : 'from-gray-300 to-gray-400 cursor-not-allowed'" class="w-full px-3 py-2 text-xs font-bold rounded-lg bg-gradient-to-r text-white transition-all disabled:opacity-60">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            {{ __('Generate Order') }}
          </button>
        </div>

        <div x-show="!detailLoading" class="border-t border-gray-50"></div>

        {{-- Profile Info --}}
        <div x-show="!detailLoading">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Profile') }}</h4>
          <div class="bg-gray-50/50 rounded-xl p-4 space-y-3">
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Email') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.email || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Phone') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.phone || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Location') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.location || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Address') }}</span><span class="text-xs font-semibold text-gray-900 text-right max-w-[200px] truncate" x-text="selected?.address || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Gender') }}</span><span class="text-xs font-semibold text-gray-900 capitalize" x-text="selected?.gender || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Age') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.age != null ? selected.age + ' yrs' : '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Height') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.height_cm != null ? selected.height_cm + ' cm' : '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Weight') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.weight_kg != null ? selected.weight_kg + ' kg' : '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Fitness Goal') }}</span><span class="text-xs font-semibold text-gray-900 capitalize text-right max-w-[200px]" x-text="selected?.fitness_goal ? selected.fitness_goal.replaceAll('_',' ') : '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Dietary Preference') }}</span><span class="text-xs font-semibold text-gray-900 capitalize text-right max-w-[200px] truncate" x-text="selected?.dietary_preference || '—'"></span></div>
            <div x-show="selected?.allergies && selected.allergies.length > 0" class="flex justify-between items-start"><span class="text-xs text-gray-400 flex-shrink-0">{{ __('Allergies') }}</span><div class="flex flex-wrap gap-1 justify-end max-w-[200px]"><template x-for="a in selected.allergies" :key="a"><span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-50 text-red-600 border border-red-100" x-text="a"></span></template></div></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Joined') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.joined_formatted || selected?.joined || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Verified') }}</span><span class="text-xs font-semibold" :class="selected?.is_verified ? 'text-green-600' : 'text-gray-400'" x-text="selected?.is_verified ? 'Yes' : 'No'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Active') }}</span><span class="text-xs font-semibold" :class="selected?.is_active ? 'text-green-600' : 'text-red-500'" x-text="selected?.is_active ? 'Active' : 'Inactive'"></span></div>
          </div>
        </div>

        <div x-show="!detailLoading" class="border-t border-gray-50"></div>

        {{-- Current Subscription --}}
        <div x-show="!detailLoading && selected?.subscription">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Current Subscription') }}</h4>
          <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="relative z-10 space-y-2.5">
              <div class="flex justify-between items-center"><span class="text-xs text-white/60">{{ __('Plan') }}</span><span class="text-sm font-bold" x-text="selected?.subscription?.plan_name || selected?.plan"></span></div>
              <div class="flex justify-between items-center"><span class="text-xs text-white/60">{{ __('Amount') }}</span><span class="text-sm font-bold" x-text="'SAR ' + Number(selected?.subscription?.amount || 0).toLocaleString()"></span></div>
              <div class="flex justify-between items-center"><span class="text-xs text-white/60">{{ __('Period') }}</span><span class="text-xs font-semibold" x-text="(selected?.subscription?.start_formatted || '—') + ' → ' + (selected?.subscription?.end_formatted || 'Ongoing')"></span></div>
              <div class="flex justify-between items-center"><span class="text-xs text-white/60">{{ __('Payment') }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border border-white/20 bg-white/10" :class="paymentStatusClass(selected?.subscription?.payment_status)">
                  <span x-text="selected?.subscription?.payment_status ? selected.subscription.payment_status.charAt(0).toUpperCase() + selected.subscription.payment_status.slice(1) : 'N/A'"></span>
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- All Subscriptions --}}
        <div x-show="!detailLoading && selected?.subscriptions && selected.subscriptions.length > 0">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('All Subscriptions') }} <span class="text-gray-300" x-text="'(' + (selected?.subscriptions?.length || 0) + ')'"></span></h4>
          <div class="space-y-2">
            <template x-for="sub in selected.subscriptions" :key="sub.id">
              <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-gray-50 to-white border border-gray-100 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-900" x-text="sub.plan_name || sub.plan || 'Plan'"></p>
                    <p class="text-[10px] text-gray-400" x-text="(sub.start_formatted || '—') + ' → ' + (sub.end_formatted || 'Ongoing')"></p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-xs font-bold text-gray-900" x-text="'SAR ' + Number(sub.amount || 0).toLocaleString()"></p>
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-semibold border" :class="statusClass(sub.status)">
                    <span x-text="sub.status?.charAt(0)?.toUpperCase() + sub.status?.slice(1)"></span>
                  </span>
                </div>
              </div>
            </template>
          </div>
        </div>

        <div class="border-t border-gray-50"></div>

        {{-- Payments --}}
        <div x-show="!detailLoading">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Payment History') }} <span class="text-gray-300" x-text="'(' + (selected?.payments?.length || 0) + ')'"></span></h4>
          <div x-show="selected?.payments && selected.payments.length > 0" class="space-y-2">
            <template x-for="p in selected.payments" :key="p.id">
              <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-gray-50 to-white border border-gray-100 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" :class="paymentStatusBg(p.status)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-900" x-text="p.id"></p>
                    <p class="text-[10px] text-gray-400" x-text="p.date"></p>
                    <p class="text-[10px] text-gray-300" x-show="p.provider" x-text="p.provider + (p.plan_name ? ' · ' + p.plan_name : '')"></p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-xs font-bold text-gray-900" x-text="(p.currency || 'SAR') + ' ' + Number(p.amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"></p>
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-semibold border" :class="paymentStatusClass(p.status)">
                    <span x-text="p.status?.charAt(0)?.toUpperCase() + p.status?.slice(1)"></span>
                  </span>
                </div>
              </div>
            </template>
          </div>
          <div x-show="!selected?.payments || selected.payments.length === 0" class="flex flex-col items-center justify-center py-6 text-center">
            <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xs text-gray-400">{{ __('No payments yet') }}</p>
          </div>
        </div>

        {{-- Orders --}}
        <div x-show="!detailLoading">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Order History') }} <span class="text-gray-300" x-text="'(' + (selected?.orders?.length || 0) + ')'"></span></h4>
          <div x-show="selected?.orders && selected.orders.length > 0" class="space-y-2">
            <template x-for="o in selected.orders" :key="o.id">
              <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-gray-50 to-white border border-gray-100 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#033133] to-[#025C5F] flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                  </div>
                  <div>
                    <p class="text-xs font-semibold text-gray-900" x-text="o.id"></p>
                    <p class="text-[10px] text-gray-400" x-text="o.date"></p>
                    <p class="text-[10px] text-gray-300" x-show="o.delivery_date && o.delivery_date !== '—'" x-text="'Delivery: ' + o.delivery_date"></p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-xs font-bold text-gray-900" x-text="'SAR ' + Number(o.amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"></p>
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-semibold border" :class="statusClass(o.status)">
                    <span x-text="o.status?.charAt(0)?.toUpperCase() + o.status?.slice(1)"></span>
                  </span>
                </div>
              </div>
            </template>
          </div>
          <div x-show="!selected?.orders || selected.orders.length === 0" class="flex flex-col items-center justify-center py-6 text-center">
            <svg class="w-10 h-10 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-xs text-gray-400">{{ __('No orders yet') }}</p>
          </div>
        </div>

      </div>{{-- end p-5 --}}
    </div>
  </div>


  {{-- Assigned Meals History Modal --}}
  <div
    x-show="showAssignedMeals"
    x-cloak
    class="assignment-modal-overlay fixed inset-0 z-[60] flex items-center justify-center p-3 md:p-5">

    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeAssignedMeals()"></div>

    <div class="assignment-history-shell relative bg-white rounded-2xl shadow-2xl">
      <div class="assignment-modal-header flex items-start justify-between gap-4 border-b border-gray-100 bg-gradient-to-r from-[#173327] to-[#025C5F] px-4 py-4 sm:px-6 text-white">
        <div class="min-w-0">
          <p class="text-[10px] uppercase tracking-wider text-white/60">{{ __('Assigned Meal Schedule') }}</p>
          <h3 class="mt-1 truncate text-base sm:text-lg font-bold" x-text="historyTarget?.name || '{{ __('Customer') }}'"></h3>
          <p class="mt-1 text-xs text-white/70" x-text="assignedHistoryPeriodLabel()"></p>
        </div>

        <button type="button" @click="closeAssignedMeals()" class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-white/10 hover:bg-white/20">
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="assignment-modal-body flex-1 overflow-y-auto p-4 sm:p-6">
        <div x-show="historyLoading" class="flex items-center justify-center py-20">
          <svg class="h-8 w-8 animate-spin text-[#6E7A25]" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.37 0 0 5.37 0 12h4Z"></path>
          </svg>
        </div>

        <div x-show="historyError" class="rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700" x-text="historyError"></div>

        <div x-show="!historyLoading && !historyError && assignedWeeks.length === 0" class="flex flex-col items-center justify-center py-16 text-center">
          <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-50">
            <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h10"/>
            </svg>
          </div>
          <p class="mt-4 text-sm font-bold text-gray-700">{{ __('No meals have been assigned yet.') }}</p>
        </div>

        <div x-show="!historyLoading && !historyError && assignedWeeks.length > 0" class="space-y-4">
          <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
              <p class="text-[10px] font-bold uppercase text-gray-400">{{ __('Assigned Weeks') }}</p>
              <p class="mt-1 text-xl font-black text-gray-900" x-text="assignmentSummary.assigned_week_count || assignedWeeks.length"></p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
              <p class="text-[10px] font-bold uppercase text-gray-400">{{ __('Assignments') }}</p>
              <p class="mt-1 text-xl font-black text-gray-900" x-text="assignmentSummary.total_assignments || 0"></p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
              <p class="text-[10px] font-bold uppercase text-gray-400">{{ __('Meals') }}</p>
              <p class="mt-1 text-xl font-black text-gray-900" x-text="assignmentSummary.total_meals || 0"></p>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-3">
              <p class="text-[10px] font-bold uppercase text-gray-400">{{ __('Next Week') }}</p>
              <p class="mt-1 text-xl font-black text-[#6E7A25]" x-text="assignmentSummary.next_available_week || '—'"></p>
            </div>
          </div>

          <template x-for="week in assignedWeeks" :key="'history-week-' + week.week_number">
            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
              <div class="flex flex-col gap-3 border-b border-gray-100 bg-gray-50 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <div class="flex items-center gap-2">
                    <h4 class="font-black text-gray-900" x-text="'{{ __('Week') }} ' + week.week_number"></h4>
                    <span
                      class="rounded-full px-2 py-0.5 text-[10px] font-bold"
                      :class="week.is_complete ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                      x-text="week.is_complete ? '{{ __('Complete') }}' : '{{ __('Partial') }}'"></span>
                  </div>
                  <p class="mt-1 text-xs text-gray-500" x-text="formatDateRange(week.start_date, week.end_date)"></p>
                </div>

                <button
                  type="button"
                  @click="editAssignedWeek(week.week_number)"
                  class="w-full sm:w-auto rounded-xl bg-[#173327] px-4 py-2 text-xs font-bold text-white hover:bg-[#6E7A25]">
                  {{ __('Edit This Week') }}
                </button>
              </div>

              <div class="divide-y divide-gray-100">
                <template x-for="day in (week.days || [])" :key="'history-day-' + week.week_number + '-' + (day.date || day.day_number)">
                  <div class="p-4">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                      <p class="text-sm font-bold text-gray-800" x-text="historyDayTitle(day)"></p>
                      <p class="text-xs text-gray-400" x-text="formatDate(day.date || day.delivery_date)"></p>
                    </div>

                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                      <template x-for="assignment in (day.assignments || [])" :key="'history-assignment-' + (assignment.id || assignment.assignment_id)">
                        <article class="rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                          <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                              <p class="text-xs font-black text-[#173327]" x-text="assignment.category_name || assignment.meal_category_name || '{{ __('Meal Category') }}'"></p>
                              <p class="mt-1 text-[10px] text-gray-400" x-text="historyAssignmentTime(assignment)"></p>
                            </div>
                            <span class="rounded-full bg-white px-2 py-0.5 text-[9px] font-bold text-gray-500" x-text="assignment.is_active === false ? '{{ __('Inactive') }}' : '{{ __('Active') }}'"></span>
                          </div>

                          <ul class="mt-2 space-y-1">
                            <template x-for="meal in (assignment.meals || [])" :key="'history-meal-' + (meal.id || meal.meal_id)">
                              <li class="flex items-center justify-between gap-2 text-xs text-gray-700">
                                <span class="truncate" x-text="meal.name || meal.name_en || meal.name_ar || ('{{ __('Meal') }} #' + (meal.id || meal.meal_id))"></span>
                                <span class="flex-shrink-0 text-[10px] font-bold text-[#59651f]" x-text="meal.preparation_quantity ? Number(meal.preparation_quantity) + ' ' + (meal.preparation_unit || 'portion') : '×' + Number(meal.quantity || 1)"></span>
                              </li>
                            </template>
                          </ul>

                          <div class="mt-3 space-y-1 border-t border-gray-200 pt-2 text-[10px] text-gray-500">
                            <p x-show="assignment.driver_name"><span class="font-bold">{{ __('Driver') }}:</span> <span x-text="assignment.driver_name"></span></p>
                            <p x-show="assignment.delivery_location"><span class="font-bold">{{ __('Delivery') }}:</span> <span x-text="assignment.delivery_location"></span></p>
                          </div>
                        </article>
                      </template>
                    </div>
                  </div>
                </template>
              </div>
            </section>
          </template>
        </div>
      </div>

      <div class="assignment-modal-footer flex flex-col-reverse gap-2 border-t border-gray-100 bg-white px-4 py-3 sm:flex-row sm:justify-end sm:px-6">
        <button type="button" @click="closeAssignedMeals()" class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-bold text-gray-600 hover:bg-gray-200">
          {{ __('Close') }}
        </button>
        {{-- <button
          type="button"
          x-show="historyTarget?.customerStats?.successful_payments > 0"
          @click="closeAssignedMeals(); openAssignMeal(historyTarget)"
          class="rounded-xl bg-gradient-to-r from-[#033133] to-[#025C5F] px-5 py-2.5 text-sm font-bold text-white">
          {{ __('Assign More Meals') }}
        </button> --}}
      </div>
    </div>
  </div>

  {{-- Assign Menu Schedule Modal --}}
  <div x-show="showAssignMeal" x-cloak class="assignment-modal-overlay fixed inset-0 z-50 flex items-center justify-center p-3 md:p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeAssignMealModal()"></div>

    <div class="assignment-modal-shell relative bg-white rounded-2xl shadow-2xl" @click.outside="closeAssignMealModal()">
      <div class="assignment-modal-header sticky top-0 z-20 flex flex-col sm:flex-row sm:items-start justify-between gap-3 sm:gap-4 border-b border-gray-100 bg-white px-4 py-4 sm:px-6">
        <div>
          <h3 class="text-lg font-bold text-gray-900">{{ __('Assign Customer Menu') }}</h3>
          <p class="text-sm text-gray-400 mt-1">
            {{ __('Choose how the menu should be generated, then assign meals to the required days.') }}
          </p>
        </div>

        <button type="button" @click="closeAssignMealModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="assignment-modal-body flex-1 overflow-y-auto px-4 py-4 sm:px-6 sm:py-5">
      <div x-show="mealLoading" class="flex items-center justify-center py-14">
        <svg class="w-7 h-7 text-gray-300 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>

      <form id="assign-menu-form" x-show="!mealLoading" @submit.prevent="submitAssignMeal()" class="space-y-5">
        <div x-show="!assignMealForm.subscription_id" class="text-sm text-amber-700 bg-amber-50 border border-amber-100 rounded-xl p-4">
          {{ __('This customer has no active subscription.') }}
        </div>

        <template x-if="assignMealForm.subscription_id">
          <div class="space-y-5">

            {{-- Customer and subscription summary --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Customer') }}</p>
                <p class="text-sm font-bold text-gray-800 mt-1" x-text="assignMealTarget?.name || '—'"></p>
                <p class="text-xs text-gray-500 mt-1" x-text="assignMealTarget?.email || ''"></p>
              </div>

              <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Subscription') }}</p>
                <p class="text-sm font-bold text-gray-800 mt-1" x-text="assignMealTarget?.subscription?.plan_name || assignMealTarget?.plan || '{{ __('Current Plan') }}'"></p>
                <p class="text-xs text-gray-500 mt-1" x-text="'#' + assignMealForm.subscription_id"></p>
              </div>

              <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Subscription Period') }}</p>
                <p class="text-sm font-bold text-gray-800 mt-1" x-text="subscriptionPeriodLabel()"></p>
                <p class="text-xs text-gray-500 mt-1" x-text="subscriptionDaysLabel()"></p>
              </div>
            </div>

            {{-- Assignment mode --}}
            <div>
              <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                  <h4 class="font-bold text-gray-900">{{ __('Menu Assignment Option') }}</h4>
                  <p class="text-xs text-gray-500 mt-1">{{ __('Select one of the three supported menu schedules.') }}</p>
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <label class="cursor-pointer rounded-2xl border p-4 transition-all"
                       :class="assignMealForm.mode === 'daily' ? 'border-[#6E7A25] bg-[#6E7A25]/5 ring-2 ring-[#6E7A25]/10' : 'border-gray-200 hover:border-gray-300'">
                  <div class="flex items-start gap-3">
                    <input type="radio" value="daily" x-model="assignMealForm.mode" @change="changeMealMode()" class="mt-1 text-[#6E7A25] focus:ring-[#6E7A25]/20">
                    <div>
                      <p class="text-sm font-bold text-gray-800">{{ __('1. Different Menu Every Day') }}</p>
                      <p class="text-xs text-gray-500 mt-1">{{ __('Assign one exact subscription day. The admin can return and assign the next day separately.') }}</p>
                    </div>
                  </div>
                </label>

                <label class="cursor-pointer rounded-2xl border p-4 transition-all"
                       :class="assignMealForm.mode === 'repeat_weekly' ? 'border-[#6E7A25] bg-[#6E7A25]/5 ring-2 ring-[#6E7A25]/10' : 'border-gray-200 hover:border-gray-300'">
                  <div class="flex items-start gap-3">
                    <input type="radio" value="repeat_weekly" x-model="assignMealForm.mode" @change="changeMealMode()" class="mt-1 text-[#6E7A25] focus:ring-[#6E7A25]/20">
                    <div>
                      <p class="text-sm font-bold text-gray-800">{{ __('2. Repeat One Full Week') }}</p>
                      <p class="text-xs text-gray-500 mt-1">{{ __('Create Days 1–7 once. The same weekly menu repeats automatically until the subscription ends.') }}</p>
                    </div>
                  </div>
                </label>

                <label class="cursor-pointer rounded-2xl border p-4 transition-all"
                       :class="assignMealForm.mode === 'weekly_rotation' ? 'border-[#6E7A25] bg-[#6E7A25]/5 ring-2 ring-[#6E7A25]/10' : 'border-gray-200 hover:border-gray-300'">
                  <div class="flex items-start gap-3">
                    <input type="radio" value="weekly_rotation" x-model="assignMealForm.mode" @change="changeMealMode()" class="mt-1 text-[#6E7A25] focus:ring-[#6E7A25]/20">
                    <div>
                      <p class="text-sm font-bold text-gray-800">{{ __('3. Different Menu Each Week') }}</p>
                      <p class="text-xs text-gray-500 mt-1">{{ __('Create a complete 7-day menu for Week 1, Week 2, Week 3 and continue until the subscription ends.') }}</p>
                    </div>
                  </div>
                </label>
              </div>
            </div>

            {{-- Mode controls --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div x-show="assignMealForm.mode === 'daily'">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 block">
                  {{ __('Subscription Day') }} <span class="text-red-500">*</span>
                </label>
                <input type="number" min="1" :max="subscriptionDurationDays() || null"
                       x-model.number="assignMealForm.day_number"
                       @change="activateDailyDay()"
                       class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
              </div>

              <div x-show="assignMealForm.mode === 'weekly_rotation'">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 block">
                  {{ __('Week Number') }} <span class="text-red-500">*</span>
                </label>
                <input type="number" min="1" :max="subscriptionTotalWeeks() || null"
                       x-model.number="assignMealForm.week_number"
                       @change="changeWeekNumber()"
                       class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
              </div>

              <div>
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 block">{{ __('Selected Date') }}</label>
                <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700" x-text="activeScheduledDateLabel() || '—'"></div>
              </div>

              <div x-show="assignMealForm.mode !== 'daily'">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 block">{{ __('Schedule Summary') }}</label>
                <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-700" x-text="menuModeSummary()"></div>
              </div>
            </div>

            {{-- Delivery information --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
              <p class="text-sm font-bold text-blue-800">{{ __('Customer Delivery Information') }}</p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 text-xs text-blue-700">
                <p>{{ __('Location') }}: <span class="font-semibold" x-text="assignMealTarget?.location || '—'"></span></p>
                <p>{{ __('Address') }}: <span class="font-semibold" x-text="assignMealTarget?.address || '—'"></span></p>
              </div>
            </div>

            {{-- Progressive weekly assignment overview --}}
            <div x-show="assignMealForm.mode === 'weekly_rotation'" class="rounded-2xl border border-gray-200 bg-white p-4">
              <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                  <h4 class="font-bold text-gray-900">{{ __('Subscription Weeks') }}</h4>
                  <p class="mt-1 text-xs text-gray-500">
                    {{ __('Review completed weeks and continue assigning the next incomplete week.') }}
                  </p>
                </div>

                <div class="rounded-full bg-[#6E7A25]/10 px-3 py-1.5 text-xs font-bold text-[#59651f]">
                  <span x-text="assignedWeekNumbers().length"></span>
                  /
                  <span x-text="subscriptionTotalWeeks() || '—'"></span>
                  {{ __('weeks assigned') }}
                </div>
              </div>

              <div class="assignment-day-tabs mt-4 flex gap-2 overflow-x-auto pb-2">
                <template x-for="weekNumber in availableSubscriptionWeeks()" :key="'subscription-week-' + weekNumber">
                  <button
                    type="button"
                    @click="selectWeekForEditing(weekNumber)"
                    class="min-w-[10.5rem] rounded-xl border p-3 text-left transition-all"
                    :class="weekCardClass(weekNumber)">
                    <div class="flex items-center justify-between gap-2">
                      <p class="text-xs font-black" x-text="'{{ __('Week') }} ' + weekNumber"></p>
                      <span class="rounded-full px-2 py-0.5 text-[9px] font-bold" :class="weekStatusBadgeClass(weekNumber)" x-text="weekStatusLabel(weekNumber)"></span>
                    </div>
                    <p class="mt-1 text-[10px] opacity-70" x-text="weekDateRangeLabel(weekNumber)"></p>
                    <p class="mt-2 text-[10px] font-bold" x-text="weekAssignmentCountLabel(weekNumber)"></p>
                  </button>
                </template>
              </div>

              <div x-show="isCurrentWeekAlreadyAssigned()" class="mt-3 rounded-xl border border-amber-100 bg-amber-50 px-3 py-2.5 text-xs text-amber-800">
                {{ __('This week already contains assigned meals. Saving will update the selected week rather than creating a duplicate week.') }}
              </div>
            </div>

            {{-- Seven-day navigation --}}
            <div x-show="assignMealForm.mode !== 'daily'" class="space-y-3">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                  <h4 class="font-bold text-gray-900">{{ __('Week Planner') }}</h4>
                  <p class="text-xs text-gray-500 mt-1">{{ __('Open each day and select meals from the available meal categories.') }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                  <button type="button" @click="copyPreviousDay()" :disabled="assignMealForm.active_day <= 1"
                          class="px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 disabled:opacity-40 disabled:cursor-not-allowed">
                    {{ __('Copy Previous Day') }}
                  </button>
                  <button type="button" @click="copyActiveDayToAll()"
                          class="px-3 py-2 text-xs font-bold rounded-lg bg-[#6E7A25]/10 text-[#6E7A25] hover:bg-[#6E7A25]/20">
                    {{ __('Copy This Day to All') }}
                  </button>
                  <button type="button" @click="clearWholeWeek()"
                          class="px-3 py-2 text-xs font-bold rounded-lg bg-red-50 text-red-600 hover:bg-red-100">
                    {{ __('Clear Week') }}
                  </button>
                </div>
              </div>

              <div class="assignment-day-tabs flex gap-2 overflow-x-auto pb-2 lg:grid lg:grid-cols-7 lg:overflow-visible">
                <template x-for="day in visiblePlannerDays()" :key="'planner-day-' + day">
                  <button type="button" @click="assignMealForm.active_day = day"
                          class="min-w-[9rem] lg:min-w-0 rounded-xl border px-3 py-3 text-left transition-all"
                          :class="assignMealForm.active_day === day ? 'border-[#6E7A25] bg-[#6E7A25] text-white shadow-md' : 'border-gray-200 bg-white text-gray-700 hover:border-[#6E7A25]/40'">
                    <p class="text-xs font-bold" x-text="plannerDayTitle(day)"></p>
                    <p class="text-[10px] mt-1 opacity-70" x-text="plannerDayDateLabel(day)"></p>
                    <p class="text-[10px] mt-1 font-semibold" x-text="daySelectedMealsCount(day) + ' {{ __('meals') }}'"></p>
                  </button>
                </template>
              </div>
            </div>

            {{-- Daily mode heading --}}
            <div x-show="assignMealForm.mode === 'daily'" class="flex items-center justify-between gap-3">
              <div>
                <h4 class="font-bold text-gray-900">{{ __('Meals for Subscription Day') }} <span x-text="assignMealForm.day_number"></span></h4>
                <p class="text-xs text-gray-500 mt-1">{{ __('Choose one or more meals in each required meal time.') }}</p>
              </div>
            </div>

            {{-- Active day editor --}}
            <div class="rounded-2xl border border-gray-200 bg-gray-50/40 p-4 md:p-5 space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                  <h4 class="font-bold text-gray-900" x-text="activeDayEditorTitle()"></h4>
                  <p class="text-xs text-gray-500 mt-1" x-text="activeDayEditorSubtitle()"></p>
                </div>

                <div class="relative w-full sm:w-72">
                  <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                  </svg>
                  <input type="text" x-model="mealSearch" placeholder="{{ __('Search meals...') }}"
                         class="w-full text-sm border border-gray-200 rounded-xl pl-9 pr-3 py-2.5 bg-white outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
                </div>
              </div>

              <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                <template x-for="slot in mealSlots" :key="'slot-' + slot.key + '-' + activePlannerDay()">
                  <section class="border border-gray-200 rounded-2xl overflow-hidden bg-white">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                      <div>
                        <p class="font-bold text-gray-800" x-text="slot.label"></p>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="selectedMealIds(slot.key).length + ' {{ __('selected') }}'"></p>
                      </div>

                      <button type="button" @click="clearMealSlot(slot.key)"
                              x-show="selectedMealIds(slot.key).length > 0"
                              class="text-xs font-semibold text-red-500 hover:text-red-700">
                        {{ __('Clear') }}
                      </button>
                    </div>

                    <div class="assignment-meal-list divide-y divide-gray-100">
                      <template x-for="meal in filteredMealsForSlot(slot.key)" :key="slot.key + '-' + activePlannerDay() + '-' + meal.id">
                        <div class="px-4 py-3 hover:bg-gray-50 transition-colors">
                          <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" :value="meal.id"
                              x-model="activeDayAssignments()[slot.key]"
                              @change="mealSelectionChanged(slot.key, meal.id)"
                              class="w-5 h-5 rounded border-gray-300 text-[#6E7A25] focus:ring-[#6E7A25]/20">
                            <div class="min-w-0 flex-1">
                              <p class="text-sm font-semibold text-gray-700 truncate" x-text="meal.name"></p>
                              <p x-show="meal.calories" class="text-xs text-gray-400 mt-0.5" x-text="meal.calories + ' kcal'"></p>
                            </div>
                          </label>

                          <div x-show="isMealSelected(slot.key, meal.id)" x-cloak
                            class="mt-3 ml-8 rounded-xl border border-[#6E7A25]/15 bg-[#6E7A25]/5 p-3">
                            <p class="mb-2 text-[10px] font-black uppercase tracking-wider text-[#59651f]">{{ __('Customer Meal Quantity') }}</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                              <div>
                                <label class="mb-1 block text-[10px] font-bold text-gray-500">{{ __('Package/Portions') }}</label>
                                <input type="number" min="1" max="20" step="1"
                                  x-model.number="mealItemDetail(slot.key, meal.id).quantity"
                                  class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
                              </div>
                              <div>
                                <label class="mb-1 block text-[10px] font-bold text-gray-500">{{ __('Actual Amount') }} <span class="text-red-500">*</span></label>
                                <input type="number" min="0.001" step="0.001" required
                                  x-model="mealItemDetail(slot.key, meal.id).preparation_quantity"
                                  class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-[#6E7A25]/20" placeholder="1">
                              </div>
                              <div>
                                <label class="mb-1 block text-[10px] font-bold text-gray-500">{{ __('Unit') }} <span class="text-red-500">*</span></label>
                                <select x-model="mealItemDetail(slot.key, meal.id).preparation_unit" required
                                  class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
                                  <option value="portion">{{ __('Portion') }}</option><option value="kg">{{ __('Kilogram (kg)') }}</option><option value="g">{{ __('Gram (g)') }}</option><option value="whole">{{ __('Whole') }}</option><option value="half">{{ __('Half') }}</option><option value="quarter">{{ __('Quarter') }}</option><option value="piece">{{ __('Piece') }}</option><option value="litre">{{ __('Litre') }}</option><option value="ml">{{ __('Millilitre (ml)') }}</option><option value="tray">{{ __('Tray') }}</option><option value="pack">{{ __('Pack') }}</option>
                                </select>
                              </div>
                            </div>
                            <div class="mt-2"><label class="mb-1 block text-[10px] font-bold text-gray-500">{{ __('Meal Notes') }}</label><input type="text" maxlength="500" x-model.trim="mealItemDetail(slot.key, meal.id).notes" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs outline-none focus:ring-2 focus:ring-[#6E7A25]/20" placeholder="{{ __('Example: no sauce, cut into small pieces') }}"></div>
                          </div>
                        </div>
                      </template>

                      <div x-show="filteredMealsForSlot(slot.key).length === 0" class="px-4 py-8 text-center text-sm text-gray-400">
                        {{ __('No matching meals available.') }}
                      </div>
                    </div>

                    <div class="border-t border-gray-100 bg-gray-50 px-4 py-4">
                      <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                          <p class="text-xs font-black text-gray-700">{{ __('Delivery Location, Time & Driver') }}</p>
                          <p class="mt-1 text-[10px] leading-4 text-gray-400">{{ __('This meal category can use its own saved location, delivery time and driver.') }}</p>
                        </div>
                        <span class="flex-shrink-0 rounded-full px-2 py-1 text-[9px] font-bold"
                          :class="categorySettingIsComplete(slot.key) ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                          x-text="categorySettingIsComplete(slot.key) ? '{{ __('Ready') }}' : '{{ __('Incomplete') }}'"></span>
                      </div>

                      <div class="mt-3 rounded-xl border border-blue-100 bg-blue-50 p-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-500">{{ __('Customer Saved Location') }}</p>
                        <template x-if="deliveryPreferenceFor(slot.key)">
                          <div class="mt-1">
                            <p class="text-xs font-semibold leading-5 text-blue-800" x-text="deliveryPreferenceLocationSummary(slot.key)"></p>
                            <p x-show="deliveryPreferenceFor(slot.key)?.delivery_note" class="mt-1 text-[10px] text-blue-600">
                              <span class="font-bold">{{ __('Note') }}:</span>
                              <span x-text="deliveryPreferenceFor(slot.key)?.delivery_note"></span>
                            </p>
                          </div>
                        </template>
                        <p x-show="!deliveryPreferenceFor(slot.key)" class="mt-1 text-xs font-semibold text-red-600">
                          {{ __('This customer has no saved delivery preference for this meal category.') }}
                        </p>
                      </div>

                      <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                          <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-gray-500">
                            {{ __('Delivery Time') }} <span class="text-red-500">*</span>
                          </label>
                          <input type="time" x-model="categorySetting(slot.key).delivery_time" required
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
                        </div>
                        <div>
                          <label class="mb-1.5 block text-[10px] font-bold uppercase tracking-wider text-gray-500">
                            {{ __('Driver') }} <span class="text-red-500">*</span>
                          </label>
                          <select x-model="categorySetting(slot.key).driver_id" required
                            class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
                            <option value="">{{ __('Select driver') }}</option>
                            <template x-for="driver in driversList" :key="'category-driver-' + slot.key + '-' + driver.id">
                              <option :value="driver.id" x-text="driver.name + (driver.phone ? ' · ' + driver.phone : '')"></option>
                            </template>
                          </select>
                        </div>
                      </div>

                      <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                        <button type="button" @click="useDefaultDriverForCategory(slot.key)"
                          class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-[10px] font-bold text-gray-600 hover:border-[#6E7A25]/40 hover:text-[#59651f]">
                          {{ __('Use Default Driver') }}
                        </button>
                        <button type="button" @click="copyCategoryOperationsToAll(slot.key)"
                          class="rounded-lg border border-[#6E7A25]/20 bg-[#6E7A25]/10 px-3 py-2 text-[10px] font-bold text-[#59651f] hover:bg-[#6E7A25]/20">
                          {{ __('Copy Time & Driver to All Categories') }}
                        </button>
                      </div>
                    </div>
                  </section>
                </template>
              </div>
            </div>

            {{-- Week completion status --}}
            <div x-show="assignMealForm.mode !== 'daily'" class="rounded-xl border border-gray-200 bg-white p-4">
              <div class="flex items-center justify-between gap-3">
                <div>
                  <p class="text-sm font-bold text-gray-800">{{ __('Week Completion') }}</p>
                  <p class="text-xs text-gray-500 mt-1" x-text="completedDaysCount() + ' / ' + visiblePlannerDays().length + ' {{ __('days contain meals') }}'"></p>
                </div>
                <div class="w-40 h-2 rounded-full bg-gray-100 overflow-hidden">
                  <div class="h-full bg-[#6E7A25] transition-all" :style="`width: ${weekCompletionPercent()}%`"></div>
                </div>
              </div>
            </div>

            {{-- Driver --}}
            <div class="border-t border-gray-100 pt-5">
              <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 block">
                {{ __('Default Driver for All Categories') }}
                <span class="text-gray-400 normal-case font-normal">({{ __('optional') }})</span>
              </label>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <select x-model="assignMealForm.driver_id" @change="applyDefaultDriverToEmptyCategories()" class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
                  <option value="">{{ __('Select a default driver') }}</option>
                  <template x-for="driver in driversList" :key="driver.id">
                    <option :value="driver.id" x-text="driver.name + (driver.phone ? ' · ' + driver.phone : '')"></option>
                  </template>
                </select>

                <input type="text" x-model="assignMealForm.assignment_reason"
                       placeholder="{{ __('Assignment reason') }}"
                       class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
              </div>

              <textarea x-show="assignMealForm.driver_id" x-model="assignMealForm.notes" rows="2"
                        placeholder="{{ __('Optional driver notes') }}"
                        class="mt-3 w-full text-sm border border-gray-200 rounded-xl px-4 py-3 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20"></textarea>
            </div>
          </div>
        </template>

        <div x-show="assignMealError" class="text-sm text-red-700 bg-red-50 border border-red-100 rounded-xl px-4 py-3" x-text="assignMealError"></div>
        <div x-show="assignMealSuccess" class="text-sm text-green-700 bg-green-50 border border-green-100 rounded-xl px-4 py-3" x-text="assignMealSuccess"></div>

      </form>
      </div>

      <div class="assignment-modal-footer sticky bottom-0 z-20 border-t border-gray-100 bg-white px-4 py-3 sm:px-6">
        <div class="flex flex-col-reverse sm:flex-row gap-3">
          <button type="button" @click="closeAssignMealModal()"
                  class="flex-1 px-4 py-3 text-sm font-bold rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
            {{ __('Cancel') }}
          </button>

          <button type="submit" form="assign-menu-form"
                  :disabled="assigningMeal || !canSubmitMealAssignment()"
                  class="flex-1 px-4 py-3 text-sm font-bold rounded-xl bg-gradient-to-r from-[#033133] to-[#025C5F] text-white hover:shadow-md transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                  x-text="assigningMeal ? '{{ __('Saving Menu...') }}' : saveMenuButtonLabel()">
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- Assign Driver Modal --}}
  <div x-show="showAssignDriver" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none">
    <div class="absolute inset-0 bg-black/40" @click="showAssignDriver = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8" @click.outside="showAssignDriver = false">
      <div class="flex items-center justify-between mb-5">
        <div>
          <h3 class="text-base font-bold text-gray-900">{{ __('Assign Dedicated Driver') }}</h3>
          <p class="text-sm text-gray-400 mt-1"
   x-text="`{{ __('Assign a driver to') }} ${assignDriverTarget?.name || ''}`" </p>
        </div>
        <button @click="showAssignDriver = false" class="text-gray-400 hover:text-gray-600 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-5">
        <div class="flex items-start gap-2">
          <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <div class="text-sm text-blue-700">
            <p>{{ __('Customer Location') }}: <span class="font-bold" x-text="assignDriverTarget?.location || '—'"></span></p>
            <p>{{ __('Customer Address') }}: <span class="font-bold" x-text="assignDriverTarget?.address || '—'"></span></p>
          </div>
        </div>
      </div>
      <form @submit.prevent="submitAssignDriver()" class="space-y-4">
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 block">{{ __('Select Driver') }} <span class="text-red-500">*</span></label>
          <select x-model="assignDriverForm.driver_id" required class="w-full text-sm border border-gray-200 rounded-lg px-4 py-3 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
            <option value="">{{ __('Choose a driver...') }}</option>
            <template x-for="d in driversList" :key="d.id">
              <option :value="d.id" x-text="d.name + (d.phone ? ' · ' + d.phone : '')"></option>
            </template>
          </select>
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 block">{{ __('Assignment Reason') }}</label>
          <input type="text" x-model="assignDriverForm.assignment_reason" class="w-full text-sm border border-gray-200 rounded-lg px-4 py-3 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20" :placeholder="assignDriverTarget?.location ? `{{ __('Same delivery zone: ') }}` + (assignDriverTarget?.location || '') : '{{ __('e.g. Same delivery zone') }}'">
        </div>
        <div>
          <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5 block">{{ __('Notes') }}</label>
          <textarea x-model="assignDriverForm.notes" rows="2" class="w-full text-sm border border-gray-200 rounded-lg px-4 py-3 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20" placeholder="{{ __('Optional notes') }}"></textarea>
        </div>
        <div x-show="assignDriverError" class="text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2" x-text="assignDriverError"></div>
        <div x-show="assignDriverSuccess" class="text-sm text-green-700 bg-green-50 rounded-lg px-3 py-2" x-text="assignDriverSuccess"></div>
        <div class="flex gap-3 pt-2">
          <button type="button" @click="showAssignDriver = false" class="flex-1 px-4 py-3 text-sm font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">{{ __('Cancel') }}</button>
          <button type="submit" :disabled="assigningDriver" class="flex-1 px-4 py-3 text-sm font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-md transition-all disabled:opacity-50" x-text="`{{ __('Assign a driver to') }} ${assignDriverTarget?.name || ''}`"></button>
        </div>
      </form>
    </div>
  </div>

  {{-- Assign Plan Modal --}}
  <div x-show="showAssignPlan" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none">
    <div class="absolute inset-0 bg-black/40" @click="showAssignPlan = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6" @click.outside="showAssignPlan = false">
      <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Assign Plan') }}</h3>

      <form @submit.prevent="submitAssignPlan">
        <select x-model="assignPlanId" class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2.5 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20 mb-4" required>
          <option value="">{{ __('Select a plan...') }}</option>
          <template x-for="p in plans" :key="p.id">
            <option :value="p.id" x-text="p.name + ' — SAR ' + p.price"></option>
          </template>
        </select>
        <div class="flex gap-2">
          <button type="button" @click="showAssignPlan = false" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">{{ __('Cancel') }}</button>
          <button type="submit" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-md transition-all" x-text="assigning ? '{{ __('Assigning...') }}' : '{{ __('Assign') }}'"></button>
        </div>
      </form>
    </div>
  </div>

  {{-- Edit Customer Modal --}}
  <div x-show="showEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none">
    <div class="absolute inset-0 bg-black/40" @click="showEdit = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" @click.outside="showEdit = false">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h3 class="text-sm font-bold text-gray-900">{{ __('Edit Customer') }}</h3>
          <p class="text-xs text-gray-400 mt-0.5" x-text="editTarget?.name"></p>
        </div>
        <button @click="showEdit = false" class="text-gray-400 hover:text-gray-600 transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      <form @submit.prevent="submitEdit" class="space-y-3 max-h-[70vh] overflow-y-auto pr-1">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('First Name') }}</label>
            <input type="text" x-model="editForm.first_name" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20" required>
          </div>
          <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Last Name') }}</label>
            <input type="text" x-model="editForm.last_name" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20" required>
          </div>
        </div>
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Email') }}</label>
          <input type="email" x-model="editForm.email" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20" required>
        </div>
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Phone') }}</label>
          <input type="text" x-model="editForm.phone" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Location') }}</label>
            <input type="text" x-model="editForm.location" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
          </div>
          <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Address') }}</label>
            <input type="text" x-model="editForm.address" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Gender') }}</label>
            <select x-model="editForm.gender" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
              <option value="">—</option>
              <option value="male">{{ __('Male') }}</option>
              <option value="female">{{ __('Female') }}</option>
              <option value="other">{{ __('Other') }}</option>
            </select>
          </div>
          <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Age') }}</label>
            <input type="number" x-model="editForm.age" min="0" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
          </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Height (cm)') }}</label>
            <input type="number" step="0.1" x-model="editForm.height_cm" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
          </div>
          <div>
            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Weight (kg)') }}</label>
            <input type="number" step="0.1" x-model="editForm.weight_kg" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
          </div>
        </div>
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Fitness Goal') }}</label>
          <select x-model="editForm.fitness_goal" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
            <option value="">—</option>
            <option value="weight_loss">{{ __('Weight Loss') }}</option>
            <option value="muscle_gain">{{ __('Muscle Gain') }}</option>
            <option value="maintenance">{{ __('Maintenance') }}</option>
            <option value="healthy_lifestyle">{{ __('Healthy Lifestyle') }}</option>
          </select>
        </div>
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Dietary Preference') }}</label>
          <input type="text" x-model="editForm.dietary_preference" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
        </div>
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Allergies') }}</label>
          <input type="text" x-model="editForm.allergies" placeholder="e.g. Peanuts, Lactose, Gluten" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
          <p class="text-[9px] text-gray-400 mt-1">{{ __('Separate with commas') }}</p>
        </div>
        <div class="flex gap-2 pt-2">
          <button type="button" @click="showEdit = false" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">{{ __('Cancel') }}</button>
          <button type="submit" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-md transition-all" x-text="saving ? '{{ __('Saving...') }}' : '{{ __('Save Changes') }}'"></button>
        </div>
      </form>
    </div>
  </div>

  {{-- Delete Confirmation Modal --}}
  <div x-show="showDelete" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none">
    <div class="absolute inset-0 bg-black/40" @click="showDelete = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6" @click.outside="showDelete = false">
      <div class="flex flex-col items-center text-center mb-4">
        <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mb-3">
          <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-sm font-bold text-gray-900">{{ __('Delete Customer') }}</h3>
        <p class="text-xs text-gray-400 mt-1" x-text="`${__('Are you sure you want to deactivate')} ${deleteTarget?.name}?`"></p>
      </div>
      <div class="flex gap-2">
        <button @click="showDelete = false" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">{{ __('Cancel') }}</button>
        <button @click="submitDelete" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-red-500 to-red-600 text-white hover:shadow-md transition-all" x-text="deleting ? '{{ __('Deleting...') }}' : '{{ __('Delete') }}'"></button>
      </div>
    </div>
  </div>

</div>

@push('scripts')
<script>
function customersApp() {
  return {
    customers: [],
    stats: [],
    plans: @json($plansList ?? []),
    selected: null,
    showAssignPlan: false,
    assignTarget: null,
    assignPlanId: '',
    assigning: false,
    showEdit: false,
    editTarget: null,
    editForm: { first_name: '', last_name: '', email: '', phone: '', location: '', address: '', gender: '', age: '', height_cm: '', weight_kg: '', fitness_goal: '', dietary_preference: '', allergies: '' },
    saving: false,
    showDelete: false,
    deleteTarget: null,
    deleting: false,
    detailLoading: false,
    search: '',
    statusFilter: '',
    planFilter: '',
    paidOnly: false,
    activeTab: 'all',
    workflow: '',
    page: 1,
    hasMore: false,
    totalCount: 0,
    loading: true,
    showAssignMeal: false,
    assignMealTarget: null,
    mealLoading: false,
    assigningMeal: false,
    assignMealError: '',
    assignMealSuccess: '',
    showAssignedMeals: false,
    historyTarget: null,
    historyLoading: false,
    historyError: '',
    assignmentHistory: [],
    assignedWeeks: [],
    assignmentSummary: {},
    existingAssignments: [],
    locale: '{{ app()->getLocale() }}',
    mealSlots: [],
    assignMealForm: {
      subscription_id: 0,
      mode: 'daily',
      day_number: 1,
      week_number: 1,
      active_day: 1,
      days: {},
      meal_item_details: {},
      category_settings: {},
      driver_id: '',
      assignment_reason: '',
      notes: ''
    },
    allMeals: [],
    mealSearch: '',
    showAssignDriver: false,
    assignDriverTarget: null,
    assigningDriver: false,
    assignDriverError: '',
    assignDriverSuccess: '',
    assignDriverForm: { driver_id: '', assignment_reason: '', notes: '' },
    driversList: [],

    customerHasAssignments(customer) {
      return Boolean(
        customer?.assignment_summary?.has_assignments ||
        Number(customer?.assignment_summary?.total_assignments || 0) > 0 ||
        Number(customer?.assigned_meals_count || 0) > 0
      );
    },

    applyAssignmentData(data, customer = null) {
      this.assignmentHistory = Array.isArray(data?.assignment_history)
        ? data.assignment_history
        : [];

      this.assignedWeeks = Array.isArray(data?.assigned_weeks)
        ? data.assigned_weeks
        : [];

      this.assignmentSummary = data?.assignment_summary || {
        has_assignments: this.assignmentHistory.length > 0,
        total_assignments: this.assignmentHistory.length,
        assigned_week_count: this.assignedWeeks.length,
        next_available_week: 1
      };

      this.existingAssignments = Array.isArray(data?.assignments)
        ? data.assignments
        : this.assignmentHistory;

      if (customer) {
        customer.assignment_summary = this.assignmentSummary;
        customer.assigned_weeks = this.assignedWeeks;
      }

      if (this.selected && customer && Number(this.selected.id) === Number(customer.id)) {
        this.selected.assignment_summary = this.assignmentSummary;
        this.selected.assigned_weeks = this.assignedWeeks;
      }
    },

    async fetchAssignmentData(customer, options = {}) {
      const target = customer || this.selected;
      const subscriptionId = Number(
        options.subscriptionId ||
        target?.subscription?.id ||
        target?.current_subscription?.id ||
        0
      );

      if (!target?.id || !subscriptionId) {
        this.applyAssignmentData({}, target);
        return {};
      }

      const response = await fetch(
        `{{ url('admin/customers') }}/${target.id}/meal-selections?subscription_id=${subscriptionId}`,
        {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        }
      );

      const data = await this.readJsonResponse(response);
      this.applyAssignmentData(data, target);
      return data;
    },

    async openAssignedMeals(customer) {
      this.historyTarget = customer;
      this.showAssignedMeals = true;
      this.historyLoading = true;
      this.historyError = '';
      document.body.classList.add('overflow-hidden');

      try {
        await this.fetchAssignmentData(customer);
      } catch (error) {
        console.error('Failed to load assigned meals', error);
        this.historyError =
          error.message ||
          '{{ __('Failed to load assigned meals.') }}';
      } finally {
        this.historyLoading = false;
      }
    },

    closeAssignedMeals() {
      this.showAssignedMeals = false;
      this.historyTarget = null;

      if (!this.showAssignMeal) {
        document.body.classList.remove('overflow-hidden');
      }
    },

    closeAssignMealModal() {
      if (this.assigningMeal) return;
      this.showAssignMeal = false;

      if (!this.showAssignedMeals) {
        document.body.classList.remove('overflow-hidden');
      }
    },

    editAssignedWeek(weekNumber) {
      const target = this.historyTarget || this.selected;
      this.closeAssignedMeals();
      this.openAssignMeal(target, {
        mode: 'weekly_rotation',
        weekNumber: Number(weekNumber || 1)
      });
    },

    assignedHistoryPeriodLabel() {
      const first = this.assignmentSummary?.first_assigned_date;
      const last = this.assignmentSummary?.last_assigned_date;

      if (!first && !last) return '{{ __('No assigned dates') }}';
      return this.formatDateRange(first, last);
    },

    formatDate(value) {
      if (!value) return '—';

      const date = new Date(`${String(value).slice(0, 10)}T00:00:00`);
      if (Number.isNaN(date.getTime())) return String(value);

      return date.toLocaleDateString(
        this.locale === 'ar' ? 'ar-SA' : 'en-US',
        { year: 'numeric', month: 'short', day: 'numeric' }
      );
    },

    formatDateRange(start, end) {
      if (!start && !end) return '—';
      if (!end || start === end) return this.formatDate(start || end);
      return `${this.formatDate(start)} → ${this.formatDate(end)}`;
    },

    historyDayTitle(day) {
      const number = day?.day_number || day?.week_day || '';
      return number
        ? `{{ __('Subscription Day') }} ${number}`
        : '{{ __('Assigned Day') }}';
    },

    historyAssignmentTime(assignment) {
      return [
        assignment?.delivery_date,
        assignment?.delivery_time
      ].filter(Boolean).join(' · ');
    },

    availableSubscriptionWeeks() {
      const count = this.subscriptionTotalWeeks();
      return Array.from({ length: Math.max(count, 1) }, (_, index) => index + 1);
    },

    assignedWeekNumbers() {
      return this.assignedWeeks
        .map(week => Number(week?.week_number || 0))
        .filter(Boolean);
    },

    assignedWeek(weekNumber) {
      return this.assignedWeeks.find(
        week => Number(week?.week_number || 0) === Number(weekNumber)
      ) || null;
    },

    weekStatusLabel(weekNumber) {
      const week = this.assignedWeek(weekNumber);
      if (!week) return '{{ __('Not Assigned') }}';
      return week.is_complete ? '{{ __('Complete') }}' : '{{ __('Partial') }}';
    },

    weekStatusBadgeClass(weekNumber) {
      const week = this.assignedWeek(weekNumber);
      if (!week) return 'bg-gray-100 text-gray-500';
      return week.is_complete
        ? 'bg-green-100 text-green-700'
        : 'bg-amber-100 text-amber-700';
    },

    weekCardClass(weekNumber) {
      if (Number(this.assignMealForm.week_number) === Number(weekNumber)) {
        return 'border-[#6E7A25] bg-[#6E7A25] text-white shadow-md';
      }

      if (this.assignedWeek(weekNumber)) {
        return 'border-green-200 bg-green-50 text-green-800 hover:border-green-300';
      }

      return 'border-gray-200 bg-white text-gray-700 hover:border-[#6E7A25]/50';
    },

    weekDateRangeLabel(weekNumber) {
      const startDay = ((Number(weekNumber) - 1) * 7) + 1;
      const lastDay = Math.min(
        startDay + 6,
        this.subscriptionDurationDays() || startDay + 6
      );

      const start = this.scheduledDateForAbsoluteDay(startDay);
      const end = this.scheduledDateForAbsoluteDay(lastDay);

      if (!start) return '—';

      const formatter = date => date.toLocaleDateString(
        this.locale === 'ar' ? 'ar-SA' : 'en-US',
        { month: 'short', day: 'numeric' }
      );

      return `${formatter(start)} → ${formatter(end || start)}`;
    },

    weekAssignmentCountLabel(weekNumber) {
      const week = this.assignedWeek(weekNumber);

      if (!week) {
        return '{{ __('Ready to assign') }}';
      }

      return `${Number(week.assignment_count || 0)} {{ __('assignments') }} · ${Number(week.meal_count || 0)} {{ __('meals') }}`;
    },

    isCurrentWeekAlreadyAssigned() {
      return Boolean(this.assignedWeek(this.assignMealForm.week_number));
    },

    selectWeekForEditing(weekNumber) {
      this.assignMealForm.mode = 'weekly_rotation';
      this.assignMealForm.week_number = Number(weekNumber || 1);
      this.assignMealForm.active_day = 1;
      this.resetPlannerDays();
      this.loadExistingAssignments(this.existingAssignments);
      this.sanitizeAllMealSelections();
      this.assignMealError = '';
      this.assignMealSuccess = '';
    },

    statusClass(s) {
      const m = { active:'bg-green-50 text-green-700 border-green-200', paused:'bg-amber-50 text-amber-700 border-amber-200', cancelled:'bg-red-50 text-red-600 border-red-200', inactive:'bg-gray-50 text-gray-600 border-gray-200' };
      return m[s] || 'bg-gray-50 text-gray-600 border-gray-200';
    },
    paymentStatusClass(s) {
      const m = { paid:'bg-green-50 text-green-700 border-green-200', unpaid:'bg-amber-50 text-amber-700 border-amber-200', pending:'bg-amber-50 text-amber-700 border-amber-200', failed:'bg-red-50 text-red-600 border-red-200', refunded:'bg-purple-50 text-purple-700 border-purple-200', captured:'bg-green-50 text-green-700 border-green-200' };
      return m[s] || 'bg-gray-50 text-gray-600 border-gray-200';
    },
    paymentStatusBg(s) {
      const m = { paid:'bg-green-100 text-green-600', unpaid:'bg-amber-100 text-amber-600', pending:'bg-amber-100 text-amber-600', failed:'bg-red-100 text-red-600', refunded:'bg-purple-100 text-purple-600', captured:'bg-green-100 text-green-600' };
      return m[s] || 'bg-gray-100 text-gray-500';
    },

    init() {
      this.fetchCustomers();
      this.fetchPlans();
      this.fetchDrivers();
    },

    async fetchDrivers() {
      try {
        const r = await fetch(`{{ route('admin.drivers') }}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const d = await r.json();
        this.driversList = (d.drivers || []).map(driver => ({
          id: driver.id,
          name: driver.name || (driver.first_name + ' ' + driver.last_name),
          phone: driver.phone || '',
          location: driver.location || '',
        }));
      } catch(e) { console.error('Failed to fetch drivers', e); }
    },

    emptyMealSlots() {
      return this.mealSlots.reduce((slots, slot) => {
        slots[slot.key] = [];
        return slots;
      }, {});
    },

    cloneMealSlots(value) {
      const source = value || {};

      return this.mealSlots.reduce((slots, slot) => {
        slots[slot.key] = [
          ...new Set(
            (source[slot.key] || [])
              .map(Number)
              .filter(Boolean)
          )
        ];

        return slots;
      }, {});
    },

    ensurePlannerDay(day) {
      const key = String(Number(day || 1));

      if (!this.assignMealForm.days[key]) {
        this.assignMealForm.days[key] = this.emptyMealSlots();
      }

      /*
       * When categories are loaded after the planner object was created,
       * add any missing dynamic category keys without deleting selections.
       */
      this.mealSlots.forEach(slot => {
        if (!Array.isArray(this.assignMealForm.days[key][slot.key])) {
          this.assignMealForm.days[key][slot.key] = [];
        }
      });

      return this.assignMealForm.days[key];
    },

    localizedValue(item, fallback = '') {
      if (!item || typeof item !== 'object') return fallback;

      if (this.locale === 'ar') {
        return (
          item.name_ar ||
          item.label_ar ||
          item.name ||
          item.name_en ||
          fallback
        );
      }

      return (
        item.name_en ||
        item.label_en ||
        item.name ||
        item.name_ar ||
        fallback
      );
    },

    mealTimeForCategory(categoryId, fallback = '') {
      /*
       * The Laravel validator accepts only these meal_time values:
       * breakfast, lunch, dinner and snack.
       *
       * Database category IDs:
       * 1 = Breakfast, 2 = Dinner, 3 = Lunch, 4 = Snacks.
       */
      const mealTimes = {
        1: 'breakfast',
        2: 'dinner',
        3: 'lunch',
        4: 'snack'
      };

      const mapped = mealTimes[Number(categoryId)];

      if (mapped) {
        return mapped;
      }

      const normalized = String(fallback || '')
        .trim()
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '');

      return ['breakfast', 'lunch', 'dinner', 'snack'].includes(normalized)
        ? normalized
        : '';
    },

    categoryFallbackName(categoryId) {
      const names = {
        1: { en: 'Breakfast', ar: 'الإفطار' },
        2: { en: 'Dinner', ar: 'العشاء' },
        3: { en: 'Lunch', ar: 'وجبة غداء' },
        4: { en: 'Snacks', ar: 'وجبات خفيفة' }
      };

      const category = names[Number(categoryId)];

      if (!category) {
        return `{{ __('Category') }} #${Number(categoryId)}`;
      }

      return this.locale === 'ar' ? category.ar : category.en;
    },

    normalizeCategoryCode(value, categoryId = 0) {
      const normalized = String(value || '')
        .trim()
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '_')
        .replace(/^_+|_+$/g, '');

      return normalized || `category_${Number(categoryId || 0)}`;
    },

    categoryDefaultTime(category, code = '') {
      const directTime = String(
        category?.default_time ||
        category?.delivery_time ||
        category?.preferred_delivery_time ||
        ''
      ).slice(0, 5);

      if (directTime) return directTime;

      /*
       * These are time fallbacks by stable semantic code, not database IDs.
       * A delivery preference or backend category time always takes priority.
       */
      const fallbackTimes = {
        breakfast: '07:00',
        lunch: '12:30',
        dinner: '19:00',
        snack: '16:00'
      };

      return fallbackTimes[code] || '';
    },

    buildMealSlots(data = {}) {
      const categoryMap = new Map();

      const registerCategory = rawCategory => {
        if (!rawCategory || typeof rawCategory !== 'object') return;

        const categoryId = Number(
          rawCategory.id ||
          rawCategory.category_id ||
          rawCategory.meal_category_id ||
          0
        );

        if (!categoryId) return;

        const existing = categoryMap.get(categoryId) || {};

        categoryMap.set(categoryId, {
          ...existing,
          ...rawCategory,
          id: categoryId
        });
      };

      const explicitCategories =
        data.meal_categories ||
        data.categories ||
        data.mealCategories ||
        [];

      if (Array.isArray(explicitCategories)) {
        explicitCategories.forEach(registerCategory);
      }

      /*
       * Derive category metadata from meals as a safe fallback.
       * This means category creation order and IDs do not matter.
       */
      (Array.isArray(data.meals) ? data.meals : []).forEach(meal => {
        const categoryObject =
          meal.meal_category ||
          meal.category ||
          {};

        const categoryId = Number(
          meal.meal_category_id ||
          meal.category_id ||
          categoryObject.id ||
          0
        );

        if (!categoryId) return;

        registerCategory({
          ...categoryObject,
          id: categoryId,
          code:
            categoryObject.code ||
            meal.category_code ||
            meal.meal_time ||
            '',
          name_en:
            categoryObject.name_en ||
            meal.category_name_en ||
            meal.category_name ||
            '',
          name_ar:
            categoryObject.name_ar ||
            meal.category_name_ar ||
            '',
          default_time:
            categoryObject.default_time ||
            meal.default_delivery_time ||
            ''
        });
      });

      /*
       * Delivery preferences may contain categories even when no meal
       * currently exists under that category.
       */
      const preferences =
        data.delivery_preferences ||
        this.assignMealTarget?.delivery_preferences ||
        [];

      const preferenceItems = Array.isArray(preferences)
        ? preferences
        : Object.values(preferences || {});

      preferenceItems.forEach(preference => {
        const categoryObject =
          preference?.meal_category ||
          preference?.category_details ||
          {};

        const categoryId = Number(
          preference?.meal_category_id ||
          preference?.category_id ||
          categoryObject?.id ||
          0
        );

        if (!categoryId) return;

        registerCategory({
          ...categoryObject,
          id: categoryId,
          code:
            categoryObject?.code ||
            preference?.category_code ||
            preference?.meal_time ||
            preference?.category ||
            '',
          name_en:
            categoryObject?.name_en ||
            preference?.category_name_en ||
            preference?.category_name ||
            '',
          name_ar:
            categoryObject?.name_ar ||
            preference?.category_name_ar ||
            '',
          default_time:
            preference?.preferred_delivery_time ||
            preference?.delivery_time ||
            categoryObject?.default_time ||
            ''
        });
      });

      this.mealSlots = [...categoryMap.values()]
        .map(category => {
          const categoryId = Number(category.id);
          const code = this.normalizeCategoryCode(
            category.code ||
            category.slug ||
            category.key ||
            category.name_en ||
            '',
            categoryId
          );

          return {
            /*
             * The state key is based on the real database category ID.
             * It remains stable in English and Arabic.
             */
            key: `category_${categoryId}`,
            code,
            category_id: categoryId,
            label: this.localizedValue(
              category,
              this.categoryFallbackName(categoryId)
            ),
            default_time: this.categoryDefaultTime(category, code),
            sort_order: Number(
              category.sort_order ||
              category.display_order ||
              category.position ||
              categoryId
            )
          };
        })
        .sort((a, b) => {
          if (a.sort_order !== b.sort_order) {
            return a.sort_order - b.sort_order;
          }

          return a.label.localeCompare(b.label);
        });
    },

    resetPlannerDays() {
      this.assignMealForm.days = {};
      this.assignMealForm.meal_item_details = {};
      const count = this.assignMealForm.mode === 'daily' ? 1 : 7;
      for (let day = 1; day <= count; day++) {
        this.assignMealForm.days[String(day)] = this.emptyMealSlots();
        this.assignMealForm.meal_item_details[String(day)] = {};
      }
    },

    ensureMealItemDay(day) {
      const dayKey = String(Number(day || 1));
      if (!this.assignMealForm.meal_item_details) this.assignMealForm.meal_item_details = {};
      if (!this.assignMealForm.meal_item_details[dayKey]) this.assignMealForm.meal_item_details[dayKey] = {};
      this.mealSlots.forEach(slot => {
        if (!this.assignMealForm.meal_item_details[dayKey][slot.key]) this.assignMealForm.meal_item_details[dayKey][slot.key] = {};
      });
      return this.assignMealForm.meal_item_details[dayKey];
    },

    mealItemDetail(slotKey, mealId, plannerDay = null) {
      const day = Number(plannerDay || this.activePlannerDay());
      const details = this.ensureMealItemDay(day);
      const mealKey = String(Number(mealId));
      if (!details[slotKey][mealKey]) details[slotKey][mealKey] = { quantity: 1, preparation_quantity: 1, preparation_unit: 'portion', notes: '' };
      return details[slotKey][mealKey];
    },

    isMealSelected(slotKey, mealId, plannerDay = null) {
      const day = Number(plannerDay || this.activePlannerDay());
      return (this.ensurePlannerDay(day)[slotKey] || []).map(Number).includes(Number(mealId));
    },

    mealSelectionChanged(slotKey, mealId) {
      const day = this.activePlannerDay();
      if (this.isMealSelected(slotKey, mealId, day)) { this.mealItemDetail(slotKey, mealId, day); return; }
      const details = this.ensureMealItemDay(day);
      delete details[slotKey][String(Number(mealId))];
    },

    cloneMealItemDay(day) {
      return JSON.parse(JSON.stringify(this.ensureMealItemDay(day) || {}));
    },

    async openAssignMeal(c, options = {}) {
      this.assignMealTarget = c;
      this.showAssignMeal = true;
      document.body.classList.add('overflow-hidden');
      this.mealLoading = true;
      this.assignMealError = '';
      this.assignMealSuccess = '';
      this.mealSearch = '';

      this.assignMealForm = {
        subscription_id: Number(c?.subscription?.id || c?.current_subscription?.id || 0),
        mode: String(
          c?.subscription?.menu_assignment_mode ||
          c?.subscription?.meal_assignment_mode ||
          'daily'
        ),
        day_number: 1,
        week_number: 1,
        active_day: 1,
        days: {},
        meal_item_details: {},
        category_settings: {},
        driver_id: '',
        assignment_reason: c?.location ? '{{ __('Same delivery zone: ') }}' + c.location : '',
        notes: ''
      };

      if (options.mode) {
        this.assignMealForm.mode = options.mode;
      }

      if (options.weekNumber) {
        this.assignMealForm.week_number = Number(options.weekNumber);
      }

      if (!['daily', 'repeat_weekly', 'weekly_rotation'].includes(this.assignMealForm.mode)) {
        this.assignMealForm.mode = 'daily';
      }

      this.mealSlots = [];
      this.resetPlannerDays();
      this.allMeals = [];

      try {
        const subId = this.assignMealForm.subscription_id;
        if (!subId) return;

        /*
         * The customer table response may contain only summary data.
         * Load the full customer record first so delivery preference IDs
         * are available when buildDayAssignments() creates the payload.
         */
        const detailsResponse = await fetch(
          `{{ url('admin/customers') }}/${c.id}/details`,
          {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          }
        );

        const detailsData = await this.readJsonResponse(detailsResponse);

        if (detailsData?.customer) {
          this.assignMealTarget = {
            ...c,
            ...detailsData.customer,
            delivery_preferences:
              detailsData.customer.delivery_preferences ||
              c?.delivery_preferences ||
              {},
            delivery_preferences_list:
              detailsData.customer.delivery_preferences_list ||
              c?.delivery_preferences_list ||
              []
          };

          /*
           * Keep the selected customer object synchronized as well.
           */
          Object.assign(c, this.assignMealTarget);
        }

        const response = await fetch(`{{ url('admin/customers') }}/${c.id}/meal-selections?subscription_id=${subId}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });

        const data = await this.readJsonResponse(response);
        this.applyAssignmentData(data, this.assignMealTarget);

        /*
         * Prefer any fresh delivery preferences returned by this endpoint.
         */
        if (
          data?.delivery_preferences ||
          data?.delivery_preferences_list
        ) {
          this.assignMealTarget = {
            ...this.assignMealTarget,
            delivery_preferences:
              data.delivery_preferences ||
              this.assignMealTarget?.delivery_preferences ||
              {},
            delivery_preferences_list:
              data.delivery_preferences_list ||
              this.assignMealTarget?.delivery_preferences_list ||
              []
          };
        }

        this.assignMealForm.subscription_id = Number(data.subscription_id || subId);

        if (!options.mode && (data.assignment_mode || data.menu_assignment_mode || data.meal_assignment_mode)) {
          const receivedMode = String(
            data.assignment_mode ||
            data.menu_assignment_mode ||
            data.meal_assignment_mode
          );

          if (['daily', 'repeat_weekly', 'weekly_rotation'].includes(receivedMode)) {
            this.assignMealForm.mode = receivedMode;
          }
        }

        if (this.assignMealForm.mode === 'weekly_rotation') {
          this.assignMealForm.week_number = Number(
            options.weekNumber ||
            this.assignmentSummary?.next_available_week ||
            this.assignMealForm.week_number ||
            1
          );
        }

        this.resetPlannerDays();

        this.buildMealSlots(data);
        this.initializeCategorySettings();

        if (this.mealSlots.length === 0) {
          throw new Error('{{ __('No meal categories were returned by the server.') }}');
        }

        /*
         * Rebuild planner state now that the real category IDs are known.
         */
        this.resetPlannerDays();

        this.allMeals = (data.meals || [])
          .map(meal => {
            const categoryObject =
              meal.meal_category ||
              meal.category ||
              {};

            return {
              id: Number(meal.id),

              name:
                this.locale === 'ar'
                  ? (
                      meal.name_ar ||
                      meal.name ||
                      meal.name_en ||
                      '{{ __('Meal') }}'
                    )
                  : (
                      meal.name_en ||
                      meal.name ||
                      meal.name_ar ||
                      '{{ __('Meal') }}'
                    ),

              category_id: Number(
                meal.meal_category_id ||
                meal.category_id ||
                categoryObject.id ||
                0
              ),

              category_code: this.normalizeCategoryCode(
                categoryObject.code ||
                meal.category_code ||
                meal.meal_time ||
                '',
                Number(
                  meal.meal_category_id ||
                  meal.category_id ||
                  categoryObject.id ||
                  0
                )
              ),

              calories:
                meal.calories !== null &&
                meal.calories !== undefined
                  ? Number(meal.calories)
                  : null
            };
          })
          .filter(meal => meal.id > 0 && meal.category_id > 0);

        const existing = Array.isArray(data.assignments)
          ? data.assignments
          : Array.isArray(data.assignment_history)
            ? data.assignment_history
            : Array.isArray(data.selections)
              ? data.selections
              : Array.isArray(data.items)
                ? data.items
                : [];

        this.existingAssignments = existing;
        this.loadExistingAssignments(this.existingAssignments);
        this.sanitizeAllMealSelections();
      } catch (error) {
        console.error('Failed to load menu assignments', error);
        this.assignMealError = error.message || '{{ __('Failed to load menu assignments.') }}';
      } finally {
        this.mealLoading = false;
      }
    },

    sanitizeAllMealSelections() {
      Object.keys(this.assignMealForm.days || {}).forEach(dayKey => {
        const daySlots = this.assignMealForm.days[dayKey];

        if (!daySlots) {
          return;
        }

        this.mealSlots.forEach(slot => {
          daySlots[slot.key] = this.validMealIdsForSlot(
            slot.key,
            daySlots[slot.key] || []
          );
        });
      });
    },

    loadExistingAssignments(existing) {
      if (!Array.isArray(existing)) return;

      existing.forEach(item => {
        const absoluteDay = this.assignmentAbsoluteDay(item);
        const weekDay = this.assignmentWeekDay(item, absoluteDay);
        const weekNumber = Number(item.week_number || Math.ceil(absoluteDay / 7) || 1);

        if (
          this.assignMealForm.mode === 'weekly_rotation' &&
          weekNumber !== Number(this.assignMealForm.week_number || 1)
        ) {
          return;
        }

        let plannerDay = 1;

        if (this.assignMealForm.mode === 'daily') {
          if (absoluteDay !== Number(this.assignMealForm.day_number || 1)) return;
          plannerDay = 1;
        } else {
          plannerDay = weekDay;
        }

        const slots = this.ensurePlannerDay(plannerDay);
        const slot = this.assignmentSlot(item);
        if (!slots[slot]) return;

        const ids = this.assignmentMealIds(item);
        slots[slot] = [...new Set([...slots[slot], ...ids])];

        const submittedItems = Array.isArray(item?.meals) ? item.meals : (Array.isArray(item?.items) ? item.items : []);
        ids.forEach(mealId => {
          const submittedItem = submittedItems.find(entry => Number(entry?.meal_id || entry?.id || entry?.meal?.id || entry) === Number(mealId));
          const detail = this.mealItemDetail(slot, mealId, plannerDay);
          detail.quantity = Number(submittedItem?.quantity || 1);
          detail.preparation_quantity = submittedItem?.preparation_quantity ?? submittedItem?.amount ?? submittedItem?.customer_quantity ?? submittedItem?.quantity ?? 1;
          detail.preparation_unit = String(submittedItem?.preparation_unit || submittedItem?.unit || 'portion').toLowerCase();
          detail.notes = submittedItem?.notes || submittedItem?.item_notes || '';
        });

        const setting = this.categorySetting(slot);
        setting.delivery_preference_id = Number(item?.delivery_preference_id || item?.delivery_preference?.id || setting.delivery_preference_id || 0);
        setting.driver_id = String(item?.driver_id || item?.driver?.id || setting.driver_id || '');
        setting.delivery_time = String(item?.delivery_time || setting.delivery_time || '').slice(0, 5);
      });
    },

    assignmentAbsoluteDay(item) {
      if (item?.day_number) return Number(item.day_number);

      const start = this.subscriptionStartDate();
      const delivery = item?.delivery_date || item?.scheduled_date;

      if (start && delivery) {
        const startDate = new Date(`${String(start).slice(0, 10)}T00:00:00`);
        const deliveryDate = new Date(`${String(delivery).slice(0, 10)}T00:00:00`);
        if (!Number.isNaN(startDate.getTime()) && !Number.isNaN(deliveryDate.getTime())) {
          return Math.floor((deliveryDate - startDate) / 86400000) + 1;
        }
      }

      return 1;
    },

    assignmentWeekDay(item, absoluteDay = 1) {
      if (item?.week_day) return Number(item.week_day);
      if (item?.day_of_week) return Number(item.day_of_week);
      return ((Math.max(Number(absoluteDay || 1), 1) - 1) % 7) + 1;
    },

    assignmentSlot(item) {
      const categoryId = Number(
        item?.meal_category_id ||
        item?.category_id ||
        item?.category?.id ||
        item?.meal_category?.id ||
        item?.meal?.category_id ||
        item?.meal?.meal_category_id ||
        0
      );

      if (categoryId > 0) {
        const slotById = this.mealSlots.find(
          slot => Number(slot.category_id) === categoryId
        );

        if (slotById) return slotById.key;
      }

      /*
       * Compatibility fallback for older assignment records that may only
       * contain a category code or meal_time.
       */
      const rawCode = this.normalizeCategoryCode(
        item?.category_code ||
        item?.meal_time ||
        item?.category?.code ||
        item?.meal_category?.code ||
        ''
      );

      const slotByCode = this.mealSlots.find(
        slot => slot.code === rawCode
      );

      return slotByCode?.key || '';
    },

    assignmentMealIds(item) {
      if (Array.isArray(item?.meal_ids)) {
        return item.meal_ids.map(Number).filter(Boolean);
      }

      if (Array.isArray(item?.meals)) {
        return item.meals.map(meal => Number(meal?.id || meal?.meal_id || meal)).filter(Boolean);
      }

      if (Array.isArray(item?.items)) {
        return item.items.map(entry => Number(entry?.meal_id || entry?.meal?.id)).filter(Boolean);
      }

      if (item?.meal_id) return [Number(item.meal_id)].filter(Boolean);

      return [];
    },

    changeMealMode() {
      this.assignMealForm.day_number = Math.max(Number(this.assignMealForm.day_number || 1), 1);

      if (this.assignMealForm.mode === 'weekly_rotation') {
        this.assignMealForm.week_number = Number(
          this.assignmentSummary?.next_available_week ||
          this.assignMealForm.week_number ||
          1
        );
      } else {
        this.assignMealForm.week_number = Math.max(Number(this.assignMealForm.week_number || 1), 1);
      }

      this.assignMealForm.active_day = 1;
      this.assignMealError = '';
      this.assignMealSuccess = '';
      this.resetPlannerDays();
      this.loadExistingAssignments(this.existingAssignments);
      this.sanitizeAllMealSelections();
    },

    activateDailyDay() {
      this.assignMealForm.day_number = Math.max(Number(this.assignMealForm.day_number || 1), 1);
      this.assignMealForm.active_day = 1;
      this.resetPlannerDays();
    },

    changeWeekNumber() {
      const totalWeeks = this.subscriptionTotalWeeks();
      this.assignMealForm.week_number = Math.max(
        1,
        Math.min(
          Number(this.assignMealForm.week_number || 1),
          totalWeeks || Number(this.assignMealForm.week_number || 1)
        )
      );
      this.assignMealForm.active_day = 1;
      this.resetPlannerDays();
      this.loadExistingAssignments(this.existingAssignments);
      this.sanitizeAllMealSelections();
    },

    visiblePlannerDays() {
      if (this.assignMealForm.mode === 'daily') {
        return [1];
      }

      if (this.assignMealForm.mode !== 'weekly_rotation') {
        return [1, 2, 3, 4, 5, 6, 7];
      }

      const duration = this.subscriptionDurationDays();

      if (!duration) {
        return [1, 2, 3, 4, 5, 6, 7];
      }

      const firstAbsoluteDay =
        ((Math.max(Number(this.assignMealForm.week_number || 1), 1) - 1) * 7) + 1;

      const remainingDays = Math.max(duration - firstAbsoluteDay + 1, 0);
      const count = Math.min(7, remainingDays);

      return Array.from({ length: Math.max(count, 1) }, (_, index) => index + 1);
    },

    activePlannerDay() {
      if (this.assignMealForm.mode === 'daily') {
        return 1;
      }

      const visible = this.visiblePlannerDays();
      const maximum = Math.max(...visible, 1);

      return Math.min(
        Math.max(Number(this.assignMealForm.active_day || 1), 1),
        maximum
      );
    },

    activeDayAssignments() {
      return this.ensurePlannerDay(this.activePlannerDay());
    },

    selectedMealIds(slot) {
      const slots = this.activeDayAssignments();
      return Array.isArray(slots?.[slot]) ? slots[slot] : [];
    },

    clearMealSlot(slot) {
      const day = this.activePlannerDay();
      const slots = this.activeDayAssignments();
      if (slots?.[slot]) slots[slot] = [];
      this.ensureMealItemDay(day)[slot] = {};
    },

    clearWholeWeek() {
      this.resetPlannerDays();
    },

    copyPreviousDay() {
      const current = this.activePlannerDay();
      if (current <= 1) return;
      this.assignMealForm.days[String(current)] = this.cloneMealSlots(this.ensurePlannerDay(current - 1));
      this.assignMealForm.meal_item_details[String(current)] = this.cloneMealItemDay(current - 1);
    },

    copyActiveDayToAll() {
      const activeDay = this.activePlannerDay();
      const source = this.cloneMealSlots(this.activeDayAssignments());
      const sourceDetails = this.cloneMealItemDay(activeDay);
      this.visiblePlannerDays().forEach(day => {
        this.assignMealForm.days[String(day)] = this.cloneMealSlots(source);
        this.assignMealForm.meal_item_details[String(day)] = JSON.parse(JSON.stringify(sourceDetails));
      });
    },

    daySelectedMealsCount(day) {
      const slots = this.ensurePlannerDay(day);
      return Object.values(slots).reduce((total, ids) => {
        return total + (Array.isArray(ids) ? ids.length : 0);
      }, 0);
    },

    completedDaysCount() {
      return this.visiblePlannerDays().filter(day => this.daySelectedMealsCount(day) > 0).length;
    },

    weekCompletionPercent() {
      const total = this.visiblePlannerDays().length || 1;
      return Math.round((this.completedDaysCount() / total) * 100);
    },

    filteredMealsForSlot(slotKey) {
      const term = String(this.mealSearch || '')
        .trim()
        .toLocaleLowerCase(this.locale || undefined);

      const slot = this.mealSlots.find(
        item => item.key === slotKey
      );

      const requiredCategoryId = Number(
        slot?.category_id || 0
      );

      if (!requiredCategoryId) return [];

      return this.allMeals.filter(meal => {
        const matchesSearch =
          !term ||
          String(meal.name || '')
            .toLocaleLowerCase(this.locale || undefined)
            .includes(term);

        return (
          matchesSearch &&
          Number(meal.category_id) === requiredCategoryId
        );
      });
    },

    validMealIdsForSlot(slot, mealIds = []) {
      const requiredCategoryId = Number(
        this.mealSlots.find(item => item.key === slot)?.category_id || 0
      );

      return [
        ...new Set(
          (Array.isArray(mealIds) ? mealIds : [])
            .map(Number)
            .filter(mealId => {
              if (!mealId) {
                return false;
              }

              const meal = this.allMeals.find(
                item => Number(item.id) === mealId
              );

              if (!meal) {
                return false;
              }

              return (
                requiredCategoryId > 0 &&
                Number(meal.category_id) === requiredCategoryId
              );
            })
        )
      ];
    },

    deliveryPreferenceFor(slotKey) {
      const slot = this.mealSlots.find(
        item => item.key === slotKey
      );

      if (!slot) {
        return null;
      }

      /*
       * Customer details can expose delivery preferences as:
       *
       * delivery_preferences:
       * {
       *   breakfast: {...},
       *   lunch: {...}
       * }
       *
       * or:
       *
       * delivery_preferences_list:
       * [
       *   {...},
       *   {...}
       * ]
       */
      const mappedPreferences =
        this.assignMealTarget?.delivery_preferences || {};

      const listedPreferences =
        this.assignMealTarget?.delivery_preferences_list || [];

      const preferences = [
        ...(
          Array.isArray(mappedPreferences)
            ? mappedPreferences
            : Object.entries(mappedPreferences).map(
                ([key, value]) => ({
                  ...(value || {}),
                  _source_key: key
                })
              )
        ),
        ...(
          Array.isArray(listedPreferences)
            ? listedPreferences
            : Object.values(listedPreferences || {})
        )
      ];

      /*
       * Keep only real delivery-preference database records and remove
       * duplicate copies returned in both response formats.
       */
      const preferenceItems = preferences.filter(
        (preference, index, items) => {
          const preferenceId = Number(
            preference?.id ||
            preference?.delivery_preference_id ||
            0
          );

          if (preferenceId < 1) {
            return false;
          }

          return index === items.findIndex(item => {
            const itemId = Number(
              item?.id ||
              item?.delivery_preference_id ||
              0
            );

            return itemId === preferenceId;
          });
        }
      );

      /*
       * First, match by the real meal-category ID.
       */
      const categoryIdMatch = preferenceItems.find(preference => {
        const preferenceCategoryId = Number(
          preference?.meal_category_id ||
          preference?.category_id ||
          preference?.meal_category?.id ||
          preference?.category_details?.id ||
          0
        );

        return (
          preferenceCategoryId > 0 &&
          preferenceCategoryId === Number(slot.category_id)
        );
      });

      if (categoryIdMatch) {
        return categoryIdMatch;
      }

      /*
       * Next, match by category code or name.
       */
      const codeMatch = preferenceItems.find(preference => {
        const preferenceCode = this.normalizeCategoryCode(
          preference?.category_code ||
          preference?.meal_time ||
          preference?.category ||
          (
            typeof preference?.meal_category === 'string'
              ? preference.meal_category
              : preference?.meal_category?.code
          ) ||
          preference?._source_key ||
          ''
        );

        return preferenceCode === slot.code;
      });

      if (codeMatch) {
        return codeMatch;
      }

      /*
       * A general/default preference can be used for all meal categories.
       */
      const generalPreference = preferenceItems.find(preference => {
        const preferenceCategoryId = Number(
          preference?.meal_category_id ||
          preference?.category_id ||
          preference?.meal_category?.id ||
          0
        );

        const preferenceCode = this.normalizeCategoryCode(
          preference?.category_code ||
          preference?.category ||
          (
            typeof preference?.meal_category === 'string'
              ? preference.meal_category
              : preference?.meal_category?.code
          ) ||
          preference?._source_key ||
          ''
        );

        return (
          preferenceCategoryId === 0 ||
          preferenceCode === 'general' ||
          preferenceCode === 'default'
        );
      });

      if (generalPreference) {
        return generalPreference;
      }

      /*
       * When the customer has only one active preference, use it as the
       * customer's common address for all selected meal categories.
       */
      const activePreferences = preferenceItems.filter(
        preference => preference?.is_active !== false
      );

      if (activePreferences.length === 1) {
        return activePreferences[0];
      }

      return null;
    },

    initializeCategorySettings() {
      if (!this.assignMealForm.category_settings) this.assignMealForm.category_settings = {};
      this.mealSlots.forEach(slot => {
        const preference = this.deliveryPreferenceFor(slot.key);
        const existing = this.assignMealForm.category_settings[slot.key] || {};
        this.assignMealForm.category_settings[slot.key] = {
          delivery_preference_id: Number(existing.delivery_preference_id || preference?.id || preference?.delivery_preference_id || 0),
          delivery_time: String(existing.delivery_time || preference?.preferred_delivery_time || preference?.delivery_time || this.defaultDeliveryTimeForSlot(slot) || '').slice(0, 5),
          driver_id: String(existing.driver_id || this.assignMealForm.driver_id || this.assignMealTarget?.current_driver?.id || preference?.driver_id || '')
        };
      });
    },

    categorySetting(slotKey) {
      if (!this.assignMealForm.category_settings) this.assignMealForm.category_settings = {};
      if (!this.assignMealForm.category_settings[slotKey]) {
        const slot = this.mealSlots.find(item => item.key === slotKey);
        const preference = this.deliveryPreferenceFor(slotKey);
        this.assignMealForm.category_settings[slotKey] = {
          delivery_preference_id: Number(preference?.id || preference?.delivery_preference_id || 0),
          delivery_time: slot ? this.defaultDeliveryTimeForSlot(slot) : '',
          driver_id: String(this.assignMealForm.driver_id || this.assignMealTarget?.current_driver?.id || preference?.driver_id || '')
        };
      }
      return this.assignMealForm.category_settings[slotKey];
    },

    categorySettingIsComplete(slotKey) {
      const setting = this.categorySetting(slotKey);
      return Boolean(Number(setting?.delivery_preference_id || 0) > 0 &&
        Number(setting?.driver_id || 0) > 0 &&
        /^\d{2}:\d{2}$/.test(String(setting?.delivery_time || '').slice(0, 5)));
    },

    applyDefaultDriverToEmptyCategories() {
      const id = String(this.assignMealForm.driver_id || '');
      if (!id) return;
      this.mealSlots.forEach(slot => {
        const setting = this.categorySetting(slot.key);
        if (!setting.driver_id) setting.driver_id = id;
      });
    },

    useDefaultDriverForCategory(slotKey) {
      const id = String(this.assignMealForm.driver_id || this.assignMealTarget?.current_driver?.id || '');
      if (!id) {
        this.assignMealError = '{{ __('Select a default driver first.') }}';
        return;
      }
      this.categorySetting(slotKey).driver_id = id;
      this.assignMealError = '';
    },

    copyCategoryOperationsToAll(sourceSlotKey) {
      const source = this.categorySetting(sourceSlotKey);
      this.mealSlots.forEach(slot => {
        const target = this.categorySetting(slot.key);
        target.delivery_time = source.delivery_time;
        target.driver_id = source.driver_id;
      });
      this.assignMealSuccess = '{{ __('The delivery time and driver were copied to all meal categories. Each category kept its own saved location.') }}';
    },

    deliveryPreferenceLocationSummary(slotKey) {
      const pref = this.deliveryPreferenceFor(slotKey);
      if (!pref) return '{{ __('No saved location') }}';
      return [pref.place_type, pref.place_name, pref.city, pref.delivery_area, pref.delivery_address].filter(Boolean).join(' · ');
    },

    defaultDeliveryTimeForSlot(slot) {
      const preference = this.deliveryPreferenceFor(slot.key);

      const configuredTime = String(
        preference?.preferred_delivery_time ||
        preference?.delivery_time ||
        slot?.default_time ||
        ''
      ).slice(0, 5);

      if (/^\d{2}:\d{2}$/.test(configuredTime)) {
        return configuredTime;
      }

      /*
       * Operational fallback times. These prevent empty delivery_time
       * values, while database/customer preferences still take priority.
       */
      const defaults = {
        breakfast: '08:00',
        lunch: '13:00',
        dinner: '19:00',
        snack: '16:00'
      };

      return defaults[slot?.code] || '12:00';
    },

    deliveryPreferenceSummary(slot) {
      const pref = this.deliveryPreferenceFor(slot);
      if (!pref) return '';

      return [
        pref.place_name,
        pref.city,
        pref.delivery_area,
        pref.delivery_address,
        pref.preferred_delivery_time
      ].filter(Boolean).join(' · ');
    },

    subscriptionStartDate() {
      return (
        this.assignMealTarget?.subscription?.start_date ||
        this.assignMealTarget?.current_subscription?.start_date ||
        ''
      );
    },

    subscriptionEndDate() {
      return (
        this.assignMealTarget?.subscription?.end_date ||
        this.assignMealTarget?.current_subscription?.end_date ||
        ''
      );
    },

    subscriptionDurationDays() {
      const explicit = Number(
        this.assignMealTarget?.subscription?.duration_days ||
        this.assignMealTarget?.current_subscription?.duration_days ||
        this.assignMealTarget?.subscription?.plan?.duration_days ||
        0
      );

      if (explicit > 0) return explicit;

      const start = this.subscriptionStartDate();
      const end = this.subscriptionEndDate();
      if (!start || !end) return 0;

      const startDate = new Date(`${String(start).slice(0, 10)}T00:00:00`);
      const endDate = new Date(`${String(end).slice(0, 10)}T00:00:00`);
      if (Number.isNaN(startDate.getTime()) || Number.isNaN(endDate.getTime())) return 0;

      return Math.max(Math.floor((endDate - startDate) / 86400000) + 1, 0);
    },

    subscriptionTotalWeeks() {
      const days = this.subscriptionDurationDays();
      return days > 0 ? Math.ceil(days / 7) : 0;
    },

    subscriptionPeriodLabel() {
      const start = this.subscriptionStartDate();
      const end = this.subscriptionEndDate();

      const format = value => {
        if (!value) return '—';
        const date = new Date(`${String(value).slice(0, 10)}T00:00:00`);
        return Number.isNaN(date.getTime())
          ? String(value)
          : date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
      };

      return `${format(start)} → ${end ? format(end) : '{{ __('Ongoing') }}'}`;
    },

    subscriptionDaysLabel() {
      const days = this.subscriptionDurationDays();
      const weeks = this.subscriptionTotalWeeks();

      if (!days) return '{{ __('Duration not available') }}';
      return `${days} {{ __('days') }} · ${weeks} {{ __('weeks') }}`;
    },

    absoluteDayForPlannerDay(plannerDay) {
      const day = Number(plannerDay || 1);

      if (this.assignMealForm.mode === 'daily') {
        return Math.max(Number(this.assignMealForm.day_number || 1), 1);
      }

      if (this.assignMealForm.mode === 'weekly_rotation') {
        return ((Math.max(Number(this.assignMealForm.week_number || 1), 1) - 1) * 7) + day;
      }

      return day;
    },

    scheduledDateForAbsoluteDay(absoluteDay) {
      const start = this.subscriptionStartDate();
      const day = Number(absoluteDay || 1);

      if (!start || day < 1) return '';

      const date = new Date(`${String(start).slice(0, 10)}T00:00:00`);
      if (Number.isNaN(date.getTime())) return '';

      date.setDate(date.getDate() + day - 1);
      return date;
    },

    scheduledDateIsoForPlannerDay(plannerDay) {
      const date = this.scheduledDateForAbsoluteDay(
        this.absoluteDayForPlannerDay(plannerDay)
      );

      if (!date) return '';

      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    },

    plannerDayDateLabel(plannerDay) {
      const date = this.scheduledDateForAbsoluteDay(
        this.absoluteDayForPlannerDay(plannerDay)
      );

      return date
        ? date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
        : '—';
    },

    activeScheduledDateLabel() {
      const date = this.scheduledDateForAbsoluteDay(
        this.absoluteDayForPlannerDay(this.activePlannerDay())
      );

      return date
        ? date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
        : '';
    },

    plannerDayTitle(plannerDay) {
      if (this.assignMealForm.mode === 'weekly_rotation') {
        return `{{ __('Week') }} ${this.assignMealForm.week_number} · {{ __('Day') }} ${plannerDay}`;
      }

      return `{{ __('Day') }} ${plannerDay}`;
    },

    activeDayEditorTitle() {
      if (this.assignMealForm.mode === 'daily') {
        return `{{ __('Subscription Day') }} ${this.assignMealForm.day_number}`;
      }

      if (this.assignMealForm.mode === 'weekly_rotation') {
        return `{{ __('Week') }} ${this.assignMealForm.week_number} · {{ __('Day') }} ${this.activePlannerDay()}`;
      }

      return `{{ __('Repeating Week') }} · {{ __('Day') }} ${this.activePlannerDay()}`;
    },

    activeDayEditorSubtitle() {
      const date = this.activeScheduledDateLabel();

      if (this.assignMealForm.mode === 'repeat_weekly') {
        return `{{ __('This weekday template repeats until the subscription ends.') }} ${date ? '· ' + date : ''}`;
      }

      return date;
    },

    menuModeSummary() {
      if (this.assignMealForm.mode === 'repeat_weekly') {
        return '{{ __('Days 1–7 will repeat until the subscription ends.') }}';
      }

      if (this.assignMealForm.mode === 'weekly_rotation') {
        return `{{ __('Editing Week') }} ${this.assignMealForm.week_number} {{ __('of') }} ${this.subscriptionTotalWeeks() || '—'}`;
      }

      return '{{ __('One exact subscription day will be saved.') }}';
    },

    saveMenuButtonLabel() {
      if (this.assignMealForm.mode === 'daily') {
        return '{{ __('Save Daily Menu') }}';
      }

      if (this.assignMealForm.mode === 'repeat_weekly') {
        return '{{ __('Save Repeating Week') }}';
      }

      return `{{ __('Save Week') }} ${this.assignMealForm.week_number}`;
    },

    canSubmitMealAssignment() {
      if (!this.assignMealForm.subscription_id) return false;

      const selectedKeys = new Set();
      this.visiblePlannerDays().forEach(day => {
        const slots = this.ensurePlannerDay(day);
        this.mealSlots.forEach(slot => {
          if (this.validMealIdsForSlot(slot.key, slots[slot.key] || []).length > 0) selectedKeys.add(slot.key);
        });
      });

      if (selectedKeys.size === 0) return false;
      if (![...selectedKeys].every(key => this.categorySettingIsComplete(key))) return false;

      if (this.assignMealForm.mode === 'daily') {
        return Number(this.assignMealForm.day_number) >= 1 && this.daySelectedMealsCount(1) > 0;
      }

      if (this.assignMealForm.mode === 'weekly_rotation' && Number(this.assignMealForm.week_number) < 1) return false;

      return this.visiblePlannerDays().every(day => this.daySelectedMealsCount(day) > 0);
    },

    buildDayAssignments(plannerDay) {
      const slots = this.ensurePlannerDay(plannerDay);

      return this.mealSlots.map(slot => {
        const mealIds = this.validMealIdsForSlot(slot.key, slots[slot.key] || []);
        if (mealIds.length === 0) return null;

        const setting = this.categorySetting(slot.key);
        const preference = this.deliveryPreferenceFor(slot.key);

        const meals = mealIds.map(mealId => {
          const detail = this.mealItemDetail(slot.key, mealId, plannerDay);
          return {
            meal_id: Number(mealId),
            quantity: Math.max(1, Math.min(Number(detail.quantity || 1), 20)),
            preparation_quantity: Number(detail.preparation_quantity || 0),
            preparation_unit: String(detail.preparation_unit || 'portion').toLowerCase(),
            notes: String(detail.notes || '').trim() || null
          };
        });

        return {
          meal_time: this.mealTimeForCategory(slot.category_id, slot.code),
          meal_category_id: Number(slot.category_id || 0),
          delivery_preference_id: Number(setting.delivery_preference_id || preference?.id || preference?.delivery_preference_id || 0),
          driver_id: Number(setting.driver_id || 0),
          delivery_time: String(setting.delivery_time || '').slice(0, 5),
          meal_ids: mealIds,
          meals
        };
      }).filter(Boolean);
    },

    buildMenuAssignmentPayload() {
      const mode = this.assignMealForm.mode;
      const plannerDays = this.visiblePlannerDays();

      const days = plannerDays.map(plannerDay => ({
        planner_day: Number(plannerDay),
        day_number: this.absoluteDayForPlannerDay(plannerDay),
        week_number: mode === 'weekly_rotation'
          ? Number(this.assignMealForm.week_number)
          : Math.ceil(this.absoluteDayForPlannerDay(plannerDay) / 7),
        week_day: mode === 'daily'
          ? ((this.absoluteDayForPlannerDay(plannerDay) - 1) % 7) + 1
          : Number(plannerDay),
        scheduled_date: this.scheduledDateIsoForPlannerDay(plannerDay),
        assignments: this.buildDayAssignments(plannerDay)
      }));

      const payload = {
        subscription_id: Number(this.assignMealForm.subscription_id),
        assignment_mode: mode,
        menu_assignment_mode: mode,
        repeat_until_subscription_end: mode === 'repeat_weekly',
        week_number: mode === 'weekly_rotation'
          ? Number(this.assignMealForm.week_number)
          : null,
        day_number: mode === 'daily'
          ? Number(this.assignMealForm.day_number)
          : null,
        days
      };

      /*
       * Backward-compatible fields for the existing one-day controller.
       * The updated backend can use `days`; the previous controller can
       * still read `day_number` and `assignments` for daily mode.
       */
      if (mode === 'daily') {
        payload.assignments = days[0]?.assignments || [];
      }

      return payload;
    },

    async readJsonResponse(response) {
      let data = {};

      try {
        data = await response.json();
      } catch (_) {}

      if (!response.ok) {
        let validation = '';

        if (Array.isArray(data?.detail)) {
          validation = data.detail.map(item => item?.msg || JSON.stringify(item)).join(' ');
        } else if (data?.errors) {
          validation = Object.values(data.errors).flat().join(' ');
        }

        throw new Error(
          validation ||
          data?.detail ||
          data?.message ||
          data?.error ||
          '{{ __('Request failed.') }}'
        );
      }

      return data;
    },

    validateMealAssignmentPayload(payload) {
      const errors = [];

      if (Number(payload?.subscription_id || 0) < 1) {
        errors.push(
          '{{ __('The customer does not have a valid subscription.') }}'
        );
      }

      const days = Array.isArray(payload?.days)
        ? payload.days
        : [];

      days.forEach((day, dayIndex) => {
        const assignments = Array.isArray(day?.assignments)
          ? day.assignments
          : [];

        assignments.forEach((assignment, assignmentIndex) => {
          const position =
            `${dayIndex + 1}.${assignmentIndex + 1}`;

          if (Number(assignment?.meal_category_id || 0) < 1) {
            errors.push(
              `Assignment ${position}: meal category is missing.`
            );
          }

          if (
            Number(
              assignment?.delivery_preference_id || 0
            ) < 1
          ) {
            errors.push(
              `Assignment ${position}: customer delivery preference is missing.`
            );
          }

          if (Number(assignment?.driver_id || 0) < 1) {
            errors.push(
              `Assignment ${position}: select a driver.`
            );
          }

          if (
            !/^\d{2}:\d{2}$/.test(
              String(assignment?.delivery_time || '')
            )
          ) {
            errors.push(
              `Assignment ${position}: delivery time is missing or invalid.`
            );
          }

          if (!Array.isArray(assignment?.meal_ids) || assignment.meal_ids.length === 0) {
            errors.push(`Assignment ${position}: select at least one meal.`);
          }

          const meals = Array.isArray(assignment?.meals) ? assignment.meals : [];
          if (meals.length !== assignment.meal_ids.length) errors.push(`Assignment ${position}: meal quantity details are incomplete.`);
          meals.forEach((meal, mealIndex) => {
            const mealPosition = `${position}.${mealIndex + 1}`;
            if (Number(meal?.meal_id || 0) < 1) errors.push(`Meal ${mealPosition}: meal ID is missing.`);
            if (Number(meal?.quantity || 0) < 1 || Number(meal?.quantity || 0) > 20) errors.push(`Meal ${mealPosition}: portions must be between 1 and 20.`);
            if (!Number.isFinite(Number(meal?.preparation_quantity)) || Number(meal?.preparation_quantity) <= 0) errors.push(`Meal ${mealPosition}: enter an actual quantity greater than zero.`);
            if (!String(meal?.preparation_unit || '').trim()) errors.push(`Meal ${mealPosition}: select a preparation unit.`);
          });
        });
      });

      return [...new Set(errors)];
    },

    async submitAssignMeal() {
      if (!this.canSubmitMealAssignment()) {
        this.assignMealError =
          this.assignMealForm.mode === 'daily'
            ? '{{ __('Select at least one meal for the selected day.') }}'
            : '{{ __('Every day in the week must contain at least one meal.') }}';

        return;
      }

      const payload = this.buildMenuAssignmentPayload();

      const validationErrors =
        this.validateMealAssignmentPayload(payload);

      if (validationErrors.length > 0) {
        this.assignMealError =
          validationErrors.join(' ');

        return;
      }

      this.assigningMeal = true;
      this.assignMealError = '';
      this.assignMealSuccess = '';

      try {
        const response = await fetch(
          `{{ url('admin/customers') }}/${this.assignMealTarget.id}/assign-meal`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          }
        );

        const data =
          await this.readJsonResponse(response);

        let message =
          data.message ||
          '{{ __('Menu assigned successfully!') }}';

        /*
         * Keep the separate customer-driver assignment endpoint for the
         * existing business flow. The same driver ID is already included
         * in every meal assignment sent above.
         */
        if (this.assignMealForm.driver_id) {
          const driverResponse = await fetch(
            `{{ url('admin/customers') }}/${this.assignMealTarget.id}/assign-driver`,
            {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
              },
              body: JSON.stringify({
                driver_id: Number(
                  this.assignMealForm.driver_id
                ),
                assignment_reason:
                  this.assignMealForm.assignment_reason,
                notes:
                  this.assignMealForm.notes
              })
            }
          );

          const driverData =
            await this.readJsonResponse(driverResponse);

          message += ' ' + (
            driverData.message ||
            '{{ __('Driver assigned successfully!') }}'
          );
        }

        this.assignMealSuccess = message;

        await this.fetchCustomers();

        const refreshedData = await this.fetchAssignmentData(
          this.assignMealTarget,
          { subscriptionId: this.assignMealForm.subscription_id }
        );

        if (
          this.selected?.id ===
          this.assignMealTarget?.id
        ) {
          this.selected.assignment_summary = this.assignmentSummary;
          this.selected.assigned_weeks = this.assignedWeeks;
        }

        if (this.assignMealForm.mode === 'weekly_rotation') {
          const nextWeek = Number(
            refreshedData?.assignment_summary?.next_available_week ||
            this.assignmentSummary?.next_available_week ||
            0
          );

          if (
            nextWeek > 0 &&
            nextWeek <= (this.subscriptionTotalWeeks() || nextWeek) &&
            nextWeek !== Number(this.assignMealForm.week_number)
          ) {
            this.assignMealForm.week_number = nextWeek;
            this.assignMealForm.active_day = 1;
            this.resetPlannerDays();
            this.loadExistingAssignments(this.existingAssignments);
            this.assignMealSuccess +=
              ' {{ __('You can now continue with the next available week.') }}';
          }
        } else {
          setTimeout(() => {
            this.closeAssignMealModal();
          }, 1500);
        }
      } catch (error) {
        console.error(
          'Failed to assign menu',
          error
        );

        this.assignMealError =
          error.message ||
          '{{ __('Failed to assign menu.') }}';
      } finally {
        this.assigningMeal = false;
      }
    },

    openAssignDriver(c) {
      this.assignDriverTarget = c;
      this.assignDriverForm = { driver_id: '', assignment_reason: c.location ? '{{ __('Same delivery zone: ') }}' + c.location : '', notes: '' };
      this.assignDriverError = '';
      this.assignDriverSuccess = '';
      this.showAssignDriver = true;
    },

    async submitAssignDriver() {
      if (!this.assignDriverForm.driver_id) return;
      this.assigningDriver = true;
      this.assignDriverError = '';
      this.assignDriverSuccess = '';
      try {
        const r = await fetch(`{{ url('admin/customers') }}/${this.assignDriverTarget.id}/assign-driver`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
          body: JSON.stringify(this.assignDriverForm)
        });
        const d = await r.json();
        if (d.success) {
          this.assignDriverSuccess = d.message || '{{ __('Driver assigned successfully!') }}';
          setTimeout(() => { this.showAssignDriver = false; }, 1500);
        } else {
          this.assignDriverError = d.message || d.error || '{{ __('Failed to assign driver.') }}';
        }
      } catch(e) { console.error('Failed to assign driver', e); this.assignDriverError = '{{ __('Failed to assign driver.') }}'; }
      finally { this.assigningDriver = false; }
    },

    generateOrderForCustomer(c) {
      window.location.href = `{{ route('admin.orders') }}`;
    },

    async fetchCustomers() {
      this.loading = true;
      try {
        const p = new URLSearchParams({ page: this.page, limit: 20 });
        if (this.statusFilter) p.set('status', this.statusFilter);
        if (this.planFilter) p.set('plan_id', this.planFilter);
        if (this.search) p.set('search', this.search);
        if (this.workflow) p.set('workflow', this.workflow);
        const r = await fetch(`{{ route('admin.customers') }}?${p.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const d = await r.json();
        this.customers = d.customers || [];
        this.stats = d.stats || [];
        if (d.plans) this.plans = d.plans;
        this.hasMore = d.has_more || false;
        this.totalCount = d.total || 0;
      } catch(e) { console.error('Failed to fetch customers', e); }
      finally { this.loading = false; }
    },

    async fetchPlans() {
      try {
        const r = await fetch(`{{ route('admin.plans') }}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const d = await r.json();
        this.plans = d.plans || [];
      } catch(e) { console.error('Failed to fetch plans', e); }
    },

    async showDetail(c) {
      this.selected = c;
      this.detailLoading = true;

      try {
        const r = await fetch(
          `{{ url('admin/customers') }}/${c.id}/details`,
          {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          }
        );

        const d = await this.readJsonResponse(r);

        if (d.customer) {
          Object.assign(this.selected, d.customer);
        }

        const subscriptionId = Number(
          this.selected?.subscription?.id ||
          this.selected?.current_subscription?.id ||
          0
        );

        if (subscriptionId) {
          await this.fetchAssignmentData(
            this.selected,
            { subscriptionId }
          );
        }
      } catch(e) {
        console.error('Failed to fetch customer details', e);
      } finally {
        this.detailLoading = false;
      }
    },

    assignPlan(c) {
      this.assignTarget = c;
      this.assignPlanId = '';
      this.showAssignPlan = true;
    },

    async submitAssignPlan() {
      if (!this.assignPlanId) return;
      this.assigning = true;
      try {
        const r = await fetch(`{{ url('admin/customers') }}/${this.assignTarget.id}/assign-plan`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
          body: JSON.stringify({ plan_id: this.assignPlanId })
        });
        const d = await r.json();
        if (d.success) {
          this.showAssignPlan = false;
          this.fetchCustomers();
          alert('{{ __('Plan assigned successfully!') }}');
        } else {
          alert(d.error || '{{ __('Failed to assign plan.') }}');
        }
      } catch(e) { console.error('Failed to assign plan', e); alert('{{ __('Failed to assign plan.') }}'); }
      finally { this.assigning = false; }
    },

    viewPayments(c) { window.location.href = `{{ url('admin/payments') }}?user_id=${c.id}`; },

    openEdit(c) {
      this.editTarget = c;
      this.editForm = {
        first_name: c.first_name || c.name?.split(' ')[0] || '',
        last_name: c.last_name || c.name?.split(' ').slice(1).join(' ') || '',
        email: c.email || '',
        phone: c.phone || '',
        location: c.location || '',
        address: c.address || '',
        gender: c.gender || '',
        age: c.age ?? '',
        height_cm: c.height_cm ?? '',
        weight_kg: c.weight_kg ?? '',
        fitness_goal: c.fitness_goal || '',
        dietary_preference: c.dietary_preference || '',
        allergies: Array.isArray(c.allergies) ? c.allergies.join(', ') : (c.allergies || ''),
      };
      this.showEdit = true;
    },

    async submitEdit() {
      this.saving = true;
      try {
        const payload = { ...this.editForm };
        if (payload.allergies && typeof payload.allergies === 'string') {
          payload.allergies = payload.allergies.split(',').map(s => s.trim()).filter(s => s.length > 0);
        }
        if (payload.age === '') payload.age = null;
        if (payload.height_cm === '') payload.height_cm = null;
        if (payload.weight_kg === '') payload.weight_kg = null;
        if (payload.gender === '') payload.gender = null;
        if (payload.fitness_goal === '') payload.fitness_goal = null;
        const r = await fetch(`{{ url('admin/customers') }}/${this.editTarget.id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
          body: JSON.stringify(payload)
        });
        const d = await r.json();
        if (d.success) {
          this.showEdit = false;
          this.fetchCustomers();
        } else {
          alert(d.error || '{{ __('Failed to update customer.') }}');
        }
      } catch(e) { console.error('Failed to update customer', e); alert('{{ __('Failed to update customer.') }}'); }
      finally { this.saving = false; }
    },

    confirmDelete(c) {
      this.deleteTarget = c;
      this.showDelete = true;
    },

    async submitDelete() {
      this.deleting = true;
      try {
        const r = await fetch(`{{ url('admin/customers') }}/${this.deleteTarget.id}`, {
          method: 'DELETE',
          headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        });
        const d = await r.json();
        if (d.success) {
          this.showDelete = false;
          this.fetchCustomers();
        } else {
          alert(d.error || '{{ __('Failed to delete customer.') }}');
        }
      } catch(e) { console.error('Failed to delete customer', e); alert('{{ __('Failed to delete customer.') }}'); }
      finally { this.deleting = false; }
    },

    switchTab(tab) {
      if (this.activeTab === tab) return;
      this.activeTab = tab;
      if (tab === 'paid') {
        this.workflow = 'paid_without_meals';
      } else if (tab === 'served') {
        this.workflow = 'meals_served';
      } else {
        this.workflow = '';
      }
      this.page = 1;
      this.fetchCustomers();
    },

    prevPage() { if (this.page > 1) { this.page--; this.fetchCustomers(); } },
    nextPage() { if (this.hasMore) { this.page++; this.fetchCustomers(); } }
  }
}
</script>
@endpush
@endsection
