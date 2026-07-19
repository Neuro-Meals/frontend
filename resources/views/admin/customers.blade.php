@extends('layouts.admin')

@section('title', __('Customers') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Customers'))

@section('content')
<div x-data="customersApp()" x-init="init()" class="space-y-4">

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

  {{-- Filter Bar --}}
  <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex flex-wrap items-center gap-2">
    <div class="flex items-center bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100 flex-1 min-w-[160px]">
      <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" x-model="search" @input.debounce.300ms="fetchCustomers()" placeholder="{{ __('Search customers...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400 w-20">
    </div>
    <select x-model="statusFilter" @change="fetchCustomers()" class="text-xs border border-gray-100 rounded-lg px-2 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer">
      <option value="">{{ __('All Status') }}</option>
      <option value="active">{{ __('Active') }}</option>
      <option value="paused">{{ __('Paused') }}</option>
      <option value="cancelled">{{ __('Cancelled') }}</option>
      <option value="inactive">{{ __('Inactive') }}</option>
    </select>
    <select x-model="planFilter" @change="fetchCustomers()" class="text-xs border border-gray-100 rounded-lg px-2 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer">
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
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
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
                <p class="text-xs font-medium text-gray-400">{{ __('No customers found') }}</p>
                <p class="text-[10px] text-gray-300 mt-0.5">{{ __('Customers will appear here once registered') }}</p>
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
              <td class="px-4 py-3"><span class="text-xs font-bold text-gray-900" x-text="c.orders"></span></td>
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
      <p class="text-[10px] text-gray-400" x-text="`{{ __('Showing') }} ${customers.length} {{ __('customers') }}`"></p>
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
    <div class="relative w-full max-w-lg bg-white shadow-2xl h-full overflow-y-auto" @click.outside="selected = null">

      {{-- Header --}}
      <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] p-6 text-white sticky top-0 z-10">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-sm font-bold">{{ __('Customer Details') }}</h3>
          <button @click="selected = null" class="text-white/60 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
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
          <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-3 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
            <div class="relative z-10">
              <p class="text-[10px] text-white/60 font-medium">{{ __('Total Spent') }}</p>
              <p class="text-lg font-bold mt-0.5" x-text="'SAR ' + Number(selected?.customerStats?.total_spent || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"></p>
            </div>
          </div>
          <div class="bg-gradient-to-br from-[#033133] to-[#025C5F] rounded-xl p-3 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
            <div class="relative z-10">
              <p class="text-[10px] text-white/60 font-medium">{{ __('Total Orders') }}</p>
              <p class="text-lg font-bold mt-0.5" x-text="selected?.customerStats?.total_orders || 0"></p>
            </div>
          </div>
          <div class="bg-gradient-to-br from-[#6E7A25] to-[#949B50] rounded-xl p-3 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
            <div class="relative z-10">
              <p class="text-[10px] text-white/60 font-medium">{{ __('Payments') }}</p>
              <p class="text-lg font-bold mt-0.5" x-text="(selected?.customerStats?.successful_payments || 0) + '/' + (selected?.customerStats?.total_payments || 0)"></p>
            </div>
          </div>
          <div class="bg-gradient-to-br from-[#173327] to-[#033133] rounded-xl p-3 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
            <div class="relative z-10">
              <p class="text-[10px] text-white/60 font-medium">{{ __('Subscriptions') }}</p>
              <p class="text-lg font-bold mt-0.5" x-text="(selected?.customerStats?.active_subscriptions || 0) + ' ' + '{{ __('active') }}'"></p>
            </div>
          </div>
        </div>

        {{-- Quick Actions --}}
        <div x-show="!detailLoading" class="flex gap-2">
          <button @click="assignPlan(selected)" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-[#6E7A25] text-white hover:bg-[#5a6820] transition-all">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('Assign Plan') }}
          </button>
          <button @click="openEdit(selected)" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            {{ __('Edit') }}
          </button>
        </div>

        <div x-show="!detailLoading" class="border-t border-gray-50"></div>

        {{-- Profile Info --}}
        <div x-show="!detailLoading">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Profile') }}</h4>
          <div class="bg-gray-50/50 rounded-xl p-4 space-y-3">
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Phone') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.phone || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Location') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.location || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Address') }}</span><span class="text-xs font-semibold text-gray-900 text-right max-w-[200px] truncate" x-text="selected?.address || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Joined') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.joined_formatted || selected?.joined || '—'"></span></div>
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

  {{-- Assign Plan Modal --}}
  <div x-show="showAssignPlan" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none">
    <div class="absolute inset-0 bg-black/40" @click="showAssignPlan = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6" @click.outside="showAssignPlan = false">
      <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Assign Plan') }}</h3>
      <p class="text-xs text-gray-400 mb-4" x-text="`${__('Assign a plan to')} ${assignTarget?.name}`"></p>
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
      <form @submit.prevent="submitEdit" class="space-y-3">
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
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Location') }}</label>
          <input type="text" x-model="editForm.location" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
        </div>
        <div>
          <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1 block">{{ __('Address') }}</label>
          <input type="text" x-model="editForm.address" class="w-full text-xs border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 outline-none focus:ring-2 focus:ring-[#6E7A25]/20">
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
    editForm: { first_name: '', last_name: '', email: '', phone: '', location: '', address: '' },
    saving: false,
    showDelete: false,
    deleteTarget: null,
    deleting: false,
    detailLoading: false,
    search: '',
    statusFilter: '',
    planFilter: '',
    page: 1,
    hasMore: false,
    loading: true,

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
    },

    async fetchCustomers() {
      this.loading = true;
      try {
        const p = new URLSearchParams({ page: this.page, limit: 20 });
        if (this.statusFilter) p.set('status', this.statusFilter);
        if (this.planFilter) p.set('plan_id', this.planFilter);
        if (this.search) p.set('search', this.search);
        const r = await fetch(`{{ route('admin.customers') }}?${p.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const d = await r.json();
        this.customers = d.customers || [];
        this.stats = d.stats || [];
        if (d.plans) this.plans = d.plans;
        this.hasMore = d.has_more || false;
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
        const r = await fetch(`{{ url('admin/customers') }}/${c.id}/details`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const d = await r.json();
        if (d.customer) Object.assign(this.selected, d.customer);
      } catch(e) { console.error('Failed to fetch customer details', e); }
      finally { this.detailLoading = false; }
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
      };
      this.showEdit = true;
    },

    async submitEdit() {
      this.saving = true;
      try {
        const r = await fetch(`{{ url('admin/customers') }}/${this.editTarget.id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
          body: JSON.stringify(this.editForm)
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

    prevPage() { if (this.page > 1) { this.page--; this.fetchCustomers(); } },
    nextPage() { if (this.hasMore) { this.page++; this.fetchCustomers(); } }
  }
}
</script>
@endpush
@endsection
