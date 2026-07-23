@extends('layouts.admin')

@section('title', __('Payments') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Payments'))

@section('content')
<div
  x-data="paymentsApp()"
  x-init="init()"
  class="space-y-4"
>
  {{-- Powerful Payment Stats --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-2" x-show="!loading">
    {{-- Success Rate Circular Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm flex items-center gap-5">
      <div class="relative w-28 h-28 flex-shrink-0">
        <svg class="w-28 h-28 transform -rotate-90">
          <circle cx="56" cy="56" r="48" stroke="#f3f4f6" stroke-width="10" fill="none"/>
          <circle cx="56" cy="56" r="48" :stroke="successRate >= 80 ? '#22c55e' : (successRate >= 50 ? '#f59e0b' : '#ef4444')" stroke-width="10" fill="none" stroke-linecap="round"
            :stroke-dasharray="circumference * (successRate / 100) + ' ' + circumference * (1 - successRate / 100)"
            class="transition-all duration-700 ease-out"/>
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
          <span class="text-xl font-bold text-gray-900" x-text="successRate + '%'"></span>
          <span class="text-[10px] text-gray-400">{{ __('Success') }}</span>
        </div>
      </div>
      <div class="flex-1 min-w-0">
        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Payment Success Rate') }}</h3>
        <p class="text-xs text-gray-400 mb-3">{{ __('Percentage of successful payments') }}</p>
        <div class="flex items-center gap-2">
          <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-200" x-text="paidCount + ' {{ __('paid') }}'"></span>
          <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-gray-50 text-gray-600 border border-gray-200" x-text="payments.length + ' {{ __('total') }}'"></span>
        </div>
      </div>
    </div>

    {{-- Revenue & Claims Mini Cards --}}
    <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-3">
      <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-4 text-white shadow-lg shadow-[#6E7A25]/20 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full"></div>
        <div class="relative z-10">
          <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center mb-3">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <p class="text-[10px] text-white/70 mb-0.5">{{ __('Total Revenue') }}</p>
          <p class="text-lg font-bold" x-text="totalRevenue"></p>
        </div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center mb-3">
          <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="text-[10px] text-gray-400 mb-0.5">{{ __('Pending') }}</p>
        <p class="text-lg font-bold text-gray-900" x-text="pendingCount"></p>
      </div>

      <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="w-9 h-9 rounded-lg bg-red-50 flex items-center justify-center mb-3">
          <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <p class="text-[10px] text-gray-400 mb-0.5">{{ __('Failed') }}</p>
        <p class="text-lg font-bold text-gray-900" x-text="failedCount"></p>
      </div>

      <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
        <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center mb-3">
          <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <p class="text-[10px] text-gray-400 mb-0.5">{{ __('Refunded') }}</p>
        <p class="text-lg font-bold text-gray-900" x-text="refundedCount"></p>
      </div>
    </div>
  </div>

  {{-- Payment Status Breakdown Bars --}}
  <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm mb-2" x-show="!loading">
    <h4 class="text-sm font-bold text-gray-900 mb-4">{{ __('Payment Status Breakdown') }}</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <template x-for="item in statusBreakdown" :key="item.key">
        <div class="flex items-center gap-3">
          <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" :class="item.light">
            <svg class="w-4 h-4" :class="item.textColor" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon"/></svg>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-medium text-gray-700" x-text="item.label"></span>
              <div class="text-right">
                <span class="text-xs font-bold text-gray-900" x-text="item.count"></span>
                <span class="text-[10px] text-gray-400" x-text="'(' + item.pct + '%)'"></span>
              </div>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full transition-all duration-700 ease-out" :class="item.color" :style="'width: ' + item.pct + '%'"></div>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>

  {{-- Skeleton loader for stats --}}
  <div class="space-y-4" x-show="loading">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm animate-pulse h-36"></div>
      <div class="lg:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-3">
        <template x-for="i in 4">
          <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm animate-pulse h-28"></div>
        </template>
      </div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm animate-pulse h-40"></div>
  </div>

  {{-- Filter Bar --}}
  <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex flex-wrap items-center gap-2">
    <div class="flex items-center bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100 flex-1 min-w-[160px]">
      <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" x-model="search" @input.debounce.300ms="fetchPayments()" placeholder="{{ __('Search...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400 w-20">
    </div>
    <select x-model="statusFilter" @change="fetchPayments()" class="text-xs border border-gray-100 rounded-lg px-2 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer">
      <option value="">{{ __('All Status') }}</option>
      <option value="paid">{{ __('Paid') }}</option>
      <option value="pending">{{ __('Pending') }}</option>
      <option value="failed">{{ __('Failed') }}</option>
      <option value="refunded">{{ __('Refunded') }}</option>
    </select>
    <button @click="fetchPayments()" class="px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-all shadow-sm whitespace-nowrap">
      <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
      {{ __('Refresh') }}
    </button>
  </div>

  {{-- Payments Table --}}
  <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-[10px] text-gray-400 bg-gray-50/50 border-b border-gray-100">
            <th class="px-4 py-2.5 font-medium">{{ __('ID') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Customer') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Subscription') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Amount') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Method') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Date') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Status') }}</th>
            <th class="px-4 py-2.5 font-medium"></th>
          </tr>
        </thead>
        <tbody>
          <template x-if="loading && payments.length === 0">
            <tr>
              <td colspan="8" class="px-4 py-8">
                <div class="space-y-2 animate-pulse">
                  <template x-for="i in 4">
                    <div class="h-8 bg-gray-50 rounded"></div>
                  </template>
                </div>
              </td>
            </tr>
          </template>
          <template x-if="!loading && payments.length === 0">
            <tr>
              <td colspan="8" class="px-4 py-8 text-center text-xs text-gray-400">{{ __('No payments found.') }}</td>
            </tr>
          </template>
          <template x-for="payment in payments" :key="payment.id">
            <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors" :class="isPaid(payment) ? 'cursor-pointer' : ''" @click="isPaid(payment) && showReceipt(payment)">
              <td class="px-4 py-2.5">
                <span class="text-xs font-bold text-gray-900" x-text="payment.id"></span>
              </td>
              <td class="px-4 py-2.5">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0" x-text="payment.customer ? payment.customer.charAt(0).toUpperCase() : 'C'"></div>
                  <div>
                    <p class="text-xs font-semibold text-gray-900" x-text="payment.customer"></p>
                    <p class="text-[10px] text-gray-400" x-text="payment.customer_email || payment.customer_phone || ''"></p>
                  </div>
                </div>
              </td>
              <td class="px-4 py-2.5 text-xs text-gray-500" x-text="payment.order + (payment.plan_name ? ' · ' + payment.plan_name : '')"></td>
              <td class="px-4 py-2.5">
                <span class="text-xs font-bold text-gray-900" x-text="payment.currency + ' ' + payment.amount"></span>
              </td>
              <td class="px-4 py-2.5 text-xs text-gray-500" x-text="payment.method"></td>
              <td class="px-4 py-2.5 text-xs text-gray-400" x-text="payment.date"></td>
              <td class="px-4 py-2.5">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="statusClass(payment.status)">
                  <span x-text="payment.status.charAt(0).toUpperCase() + payment.status.slice(1)"></span>
                </span>
              </td>
              <td class="px-4 py-2.5 text-right">
                <button x-show="isPaid(payment)" @click.stop="showReceipt(payment)" class="text-[10px] font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-2.5 py-1 rounded-lg hover:shadow-md transition-all">
                  {{ __('Receipt') }}
                </button>
                <span x-show="!isPaid(payment)" class="text-[10px] font-medium text-gray-400 italic">{{ __('Awaiting Payment') }}</span>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-50 flex items-center justify-between">
      <p class="text-[10px] text-gray-400" x-text="`{{ __('Showing') }} ${payments.length} {{ __('payments') }}`"></p>
      <div class="flex items-center gap-1">
        <button @click="prevPage" x-show="page > 1" class="px-2.5 py-1 text-[10px] font-medium text-gray-500 rounded-lg hover:bg-gray-50 transition-colors">{{ __('Prev') }}</button>
        <span class="px-2 py-1 text-[10px] font-bold text-white bg-[#6E7A25] rounded-lg" x-text="page"></span>
        <button @click="nextPage" x-show="hasMore" class="px-2.5 py-1 text-[10px] font-medium text-gray-500 rounded-lg hover:bg-gray-50 transition-colors">{{ __('Next') }}</button>
      </div>
    </div>
  </div>

  {{-- Receipt Slide-over Sidebar --}}
  <div x-show="receipt" class="fixed inset-0 z-50" style="display: none" x-cloak>
    {{-- Backdrop --}}
    <div x-show="receipt" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="receipt = null"></div>

    {{-- Panel --}}
    <div x-show="receipt" x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="absolute inset-y-0 right-0 w-full max-w-md bg-white shadow-2xl overflow-y-auto print-panel">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] px-6 py-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12 blur-xl"></div>
        <div class="relative z-10 flex items-start justify-between">
          <div>
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-white/15 backdrop-blur mb-3">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold">{{ __('Payment Receipt') }}</h3>
            <p class="text-[10px] text-white/70 mt-0.5 font-mono" x-text="receipt?.id"></p>
          </div>
          <button @click="receipt = null" class="p-1.5 rounded-lg bg-white/10 hover:bg-white/20 transition-colors print:hidden">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="relative z-10 mt-4 flex items-center gap-2">
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold border border-white/20" :class="statusClass(receipt?.status)">
            <span x-text="receipt?.status ? receipt.status.charAt(0).toUpperCase() + receipt.status.slice(1) : ''"></span>
          </span>
          <span class="text-[10px] text-white/60" x-text="receipt?.date"></span>
        </div>
      </div>

      {{-- Receipt Body --}}
      <div class="p-6 space-y-6">
        {{-- Amount Card --}}
        <div class="p-4 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 text-center">
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Amount Paid') }}</p>
          <p class="text-3xl font-bold text-[#6E7A25]" x-text="receipt?.currency + ' ' + Number(receipt?.amount || 0).toLocaleString()"></p>
        </div>

        {{-- Customer --}}
        <div>
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Customer') }}</h4>
          <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-sm flex-shrink-0" x-text="receipt?.customer ? receipt.customer.charAt(0).toUpperCase() : 'C'"></div>
            <div class="min-w-0 flex-1">
              <p class="text-sm font-bold text-gray-900 truncate" x-text="receipt?.customer"></p>
              <p class="text-[10px] text-gray-500 truncate" x-text="receipt?.customer_email || receipt?.customer_phone || ''"></p>
            </div>
          </div>
        </div>

        {{-- Order + Subscription --}}
        <div>
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Order & Subscription') }}</h4>
          <div class="space-y-2">
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Order ID') }}</span>
              <span class="text-xs font-mono font-semibold text-gray-900" x-text="receipt?.order_id || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Order Number') }}</span>
              <span class="text-xs font-mono text-gray-700" x-text="receipt?.order_number || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Subscription ID') }}</span>
              <span class="text-xs font-mono text-gray-700" x-text="receipt?.order || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Plan') }}</span>
              <span class="text-xs font-semibold text-gray-900" x-text="receipt?.plan_name || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Subscription Status') }}</span>
              <span class="text-xs font-semibold text-gray-900 capitalize" x-text="receipt?.subscription_status || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2">
              <span class="text-[10px] text-gray-400">{{ __('Period') }}</span>
              <span class="text-xs text-gray-600" x-text="(receipt?.subscription_start ? new Date(receipt.subscription_start).toLocaleDateString() : '—') + ' - ' + (receipt?.subscription_end ? new Date(receipt.subscription_end).toLocaleDateString() : '—')"></span>
            </div>
          </div>
        </div>

        {{-- Payment --}}
        <div>
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Payment Details') }}</h4>
          <div class="space-y-2">
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Method') }}</span>
              <span class="text-xs font-semibold text-gray-900" x-text="receipt?.method || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Provider') }}</span>
              <span class="text-xs font-semibold text-gray-900 capitalize" x-text="receipt?.provider || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Status') }}</span>
              <span class="text-xs font-semibold text-gray-900 capitalize" x-text="receipt?.status || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Date') }}</span>
              <span class="text-xs text-gray-600" x-text="receipt?.date || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Paid At') }}</span>
              <span class="text-xs text-gray-600" x-text="receipt?.paid_at ? new Date(receipt.paid_at).toLocaleString() : '—'"></span>
            </div>
            <div class="flex justify-between items-center py-2">
              <span class="text-[10px] text-gray-400">{{ __('Transaction ID') }}</span>
              <span class="text-[10px] font-mono text-gray-500" x-text="receipt?.id"></span>
            </div>
          </div>
        </div>

        {{-- Brand Footer --}}
        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <img src="{{ asset('blackmodelogo.png') }}" alt="Nutrio Meals" class="h-6 w-auto" onerror="this.style.display='none'">
            <span class="text-xs font-bold text-gray-900">{{ __('Nutrio Meals') }}</span>
          </div>
          <span class="text-[10px] text-gray-400" x-text="receipt?.created_at ? new Date(receipt.created_at).toLocaleDateString() : ''"></span>
        </div>
      </div>

      {{-- Actions --}}
      <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4 flex flex-col gap-2 print:hidden">
        <button @click="fullReceipt = true" class="w-full px-3 py-2.5 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-lg transition-all flex items-center justify-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          {{ __('View Full Receipt') }}
        </button>
        <div class="flex gap-2">
          <button @click="receipt = null" class="flex-1 px-3 py-2.5 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
            {{ __('Close') }}
          </button>
          <button @click="printReceipt" class="flex-1 px-3 py-2.5 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-lg transition-all flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            {{ __('Print') }}
          </button>
        </div>
      </div>

      {{-- Print-only clean receipt --}}
      <div id="printReceipt" class="hidden print:block p-8">
        <div class="text-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">{{ __('Nutrio Meals') }}</h2>
          <p class="text-sm text-gray-500">{{ __('Payment Receipt') }}</p>
        </div>
        <div class="space-y-3 text-sm">
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Transaction ID') }}</span>
            <span class="font-mono" x-text="receipt?.id"></span>
          </div>
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Order ID') }}</span>
            <span class="font-mono" x-text="receipt?.order_id"></span>
          </div>
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Customer') }}</span>
            <span class="font-bold" x-text="receipt?.customer"></span>
          </div>
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Plan') }}</span>
            <span x-text="receipt?.plan_name"></span>
          </div>
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Method') }}</span>
            <span x-text="receipt?.method"></span>
          </div>
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Amount') }}</span>
            <span class="font-bold text-lg" x-text="receipt?.currency + ' ' + Number(receipt?.amount || 0).toLocaleString()"></span>
          </div>
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Status') }}</span>
            <span class="capitalize" x-text="receipt?.status"></span>
          </div>
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Paid At') }}</span>
            <span x-text="receipt?.paid_at ? new Date(receipt.paid_at).toLocaleString() : ''"></span>
          </div>
        </div>
        <p class="text-center text-xs text-gray-400 mt-8">{{ __('Thank you for choosing Nutrio Meals') }}</p>
      </div>
    </div>
  </div>

  {{-- Full-screen Official Receipt --}}
  <div x-show="fullReceipt" class="fixed inset-0 z-[60]" style="display: none" x-cloak>
    <div x-show="fullReceipt" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="fullReceipt = false"></div>
    <div x-show="fullReceipt" x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0" x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full" class="absolute inset-0 flex items-center justify-center p-4 md:p-8 pointer-events-none">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-full overflow-y-auto pointer-events-auto full-receipt">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] px-8 py-6 text-white relative overflow-hidden">
          <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -mr-24 -mt-24 blur-2xl"></div>
          <div class="relative z-10 flex items-start justify-between">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div>
                <h2 class="text-xl font-bold">{{ __('Nutrio Meals') }}</h2>
                <p class="text-xs text-white/70">{{ __('Official Payment Receipt') }}</p>
              </div>
            </div>
            <button @click="fullReceipt = false" class="p-2 rounded-lg bg-white/10 hover:bg-white/20 transition-colors print:hidden">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="relative z-10 mt-6 flex items-center justify-between">
            <div>
              <p class="text-[10px] text-white/60 uppercase tracking-wider">{{ __('Receipt No.') }}</p>
              <p class="text-sm font-mono font-bold" x-text="receipt?.id"></p>
            </div>
            <div class="text-right">
              <p class="text-[10px] text-white/60 uppercase tracking-wider">{{ __('Date') }}</p>
              <p class="text-sm font-bold" x-text="receipt?.date"></p>
            </div>
          </div>
        </div>

        {{-- Body --}}
        <div class="p-8 space-y-6">
          {{-- Bill To --}}
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Billed To') }}</h4>
              <p class="text-sm font-bold text-gray-900" x-text="receipt?.customer"></p>
              <p class="text-xs text-gray-500" x-text="receipt?.customer_email || ''"></p>
              <p class="text-xs text-gray-500" x-text="receipt?.customer_phone || ''"></p>
            </div>
            <div>
              <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Payment Info') }}</h4>
              <p class="text-xs text-gray-900"><span class="text-gray-400">{{ __('Method') }}:</span> <span x-text="receipt?.method"></span></p>
              <p class="text-xs text-gray-900"><span class="text-gray-400">{{ __('Provider') }}:</span> <span class="capitalize" x-text="receipt?.provider"></span></p>
              <p class="text-xs text-gray-900"><span class="text-gray-400">{{ __('Status') }}:</span> <span class="capitalize font-semibold" x-text="receipt?.status"></span></p>
            </div>
          </div>

          {{-- Items --}}
          <div class="border border-gray-100 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
              <thead class="bg-gray-50/70">
                <tr>
                  <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ __('Description') }}</th>
                  <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ __('Amount') }}</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                <tr>
                  <td class="px-4 py-3">
                    <p class="text-xs font-semibold text-gray-900" x-text="receipt?.plan_name || '{{ __('Subscription Plan') }}'"></p>
                    <p class="text-[10px] text-gray-400" x-text="(receipt?.subscription_start ? new Date(receipt.subscription_start).toLocaleDateString() : '') + ' - ' + (receipt?.subscription_end ? new Date(receipt.subscription_end).toLocaleDateString() : '')"></p>
                  </td>
                  <td class="px-4 py-3 text-right text-xs font-bold text-gray-900" x-text="receipt?.currency + ' ' + Number(receipt?.amount || 0).toLocaleString()"></td>
                </tr>
              </tbody>
              <tfoot class="bg-gray-50/30 border-t border-gray-100">
                <tr>
                  <td class="px-4 py-3 text-right text-xs font-bold text-gray-500">{{ __('Total Paid') }}</td>
                  <td class="px-4 py-3 text-right text-base font-bold text-[#6E7A25]" x-text="receipt?.currency + ' ' + Number(receipt?.amount || 0).toLocaleString()"></td>
                </tr>
              </tfoot>
            </table>
          </div>

          {{-- Meta Grid --}}
          <div class="grid grid-cols-2 gap-4">
            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
              <p class="text-[10px] text-gray-400">{{ __('Order ID') }}</p>
              <p class="text-xs font-mono font-semibold text-gray-900" x-text="receipt?.order_id || '—'"></p>
            </div>
            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
              <p class="text-[10px] text-gray-400">{{ __('Order Number') }}</p>
              <p class="text-xs font-mono text-gray-700" x-text="receipt?.order_number || '—'"></p>
            </div>
            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
              <p class="text-[10px] text-gray-400">{{ __('Subscription ID') }}</p>
              <p class="text-xs font-mono text-gray-700" x-text="receipt?.order || '—'"></p>
            </div>
            <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
              <p class="text-[10px] text-gray-400">{{ __('Paid At') }}</p>
              <p class="text-xs text-gray-700" x-text="receipt?.paid_at ? new Date(receipt.paid_at).toLocaleString() : '—'"></p>
            </div>
          </div>

          {{-- Footer Note --}}
          <div class="text-center p-4 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100">
            <p class="text-xs font-semibold text-gray-700">{{ __('Thank you for choosing Nutrio Meals') }}</p>
            <p class="text-[10px] text-gray-400 mt-1">{{ __('If you have any questions, contact our support team.') }}</p>
          </div>
        </div>

        {{-- Actions --}}
        <div class="px-8 py-5 border-t border-gray-100 flex gap-2 print:hidden bg-gray-50/50">
          <button @click="fullReceipt = false" class="px-4 py-2 text-xs font-bold rounded-lg bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
            {{ __('Close') }}
          </button>
          <button @click="printFullReceipt" class="flex-1 px-4 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-lg transition-all flex items-center justify-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            {{ __('Print Full Receipt') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
@media print {
  body > * { display: none !important; }
  div[x-data="paymentsApp()"] { display: block !important; position: static !important; }
  .print-panel { position: static !important; transform: none !important; max-width: 100% !important; width: 100% !important; box-shadow: none !important; overflow: visible !important; }
  .print-panel > * { display: none !important; }
  #printReceipt { display: block !important; }
  .print\:hidden { display: none !important; }

  body.full-receipt-mode #printReceipt { display: none !important; }
  body.full-receipt-mode .full-receipt,
  body.full-receipt-mode .full-receipt > div {
    display: block !important;
    position: static !important;
    transform: none !important;
    max-width: 100% !important;
    width: 100% !important;
    box-shadow: none !important;
    overflow: visible !important;
    border-radius: 0 !important;
    padding: 0 !important;
  }
  body.full-receipt-mode .full-receipt > * { display: block !important; }
  body.full-receipt-mode .full-receipt .print\:hidden { display: none !important; }
}
</style>
@endpush

@push('scripts')
<script>
function paymentsApp() {
  return {
    payments: [],
    stats: [],
    receipt: null,
    fullReceipt: false,
    search: '',
    statusFilter: '',
    page: 1,
    hasMore: false,
    loading: true,
    circumference: 2 * Math.PI * 48,

    get paidCount() {
      const s = this.stats.find(s => s.label === 'Success Rate');
      return s ? parseInt(s.trend) || 0 : this.payments.filter(p => ['paid', 'completed', 'captured'].includes(p.status)).length;
    },
    get successRate() {
      const s = this.stats.find(s => s.label === 'Success Rate');
      if (s) return parseFloat(s.value) || 0;
      const completed = this.payments.filter(p => ['paid', 'completed', 'captured', 'failed', 'refunded', 'cancelled'].includes(p.status));
      const paid = this.payments.filter(p => ['paid', 'completed', 'captured'].includes(p.status));
      return completed.length ? Math.round((paid.length / completed.length) * 100) : 0;
    },
    get totalRevenue() {
      const s = this.stats.find(s => s.label === 'Total Revenue');
      return s ? s.value : 'SAR 0.00';
    },
    get pendingCount() {
      const s = this.stats.find(s => s.label === 'Pending');
      return s ? s.value : this.payments.filter(p => p.status === 'pending').length;
    },
    get failedCount() {
      const s = this.stats.find(s => s.label === 'Failed / Refunded');
      if (s) return parseInt(s.value) || 0;
      return this.payments.filter(p => p.status === 'failed').length;
    },
    get refundedCount() {
      return this.payments.filter(p => p.status === 'refunded').length;
    },
    get statusBreakdown() {
      const total = this.payments.length || 1;
      const items = [
        { key: 'paid', label: '{{ __("Paid") }}', icon: 'M9 12l2 2 4-4', color: 'bg-green-500', light: 'bg-green-50', textColor: 'text-green-500' },
        { key: 'pending', label: '{{ __("Pending") }}', icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', color: 'bg-amber-500', light: 'bg-amber-50', textColor: 'text-amber-500' },
        { key: 'failed', label: '{{ __("Failed") }}', icon: 'M6 18L18 6M6 6l12 12', color: 'bg-red-500', light: 'bg-red-50', textColor: 'text-red-500' },
        { key: 'refunded', label: '{{ __("Refunded") }}', icon: 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', color: 'bg-orange-500', light: 'bg-orange-50', textColor: 'text-orange-500' },
        { key: 'disputed', label: '{{ __("Disputed") }}', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', color: 'bg-purple-500', light: 'bg-purple-50', textColor: 'text-purple-500' },
        { key: 'cancelled', label: '{{ __("Cancelled") }}', icon: 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636', color: 'bg-gray-500', light: 'bg-gray-50', textColor: 'text-gray-500' },
      ];
      return items.map(item => {
        const count = this.payments.filter(p => p.status === item.key).length;
        return { ...item, count, pct: Math.round((count / total) * 100) };
      }).filter(item => item.count > 0 || item.key === 'paid');
    },

    isPaid(payment) {
      return ['paid', 'completed', 'captured'].includes(payment.status);
    },

    statusClass(status) {
      const map = {
        paid: 'bg-green-50 text-green-700 border-green-200',
        completed: 'bg-green-50 text-green-700 border-green-200',
        captured: 'bg-green-50 text-green-700 border-green-200',
        pending: 'bg-amber-50 text-amber-700 border-amber-200',
        unpaid: 'bg-gray-50 text-gray-600 border-gray-200',
        failed: 'bg-red-50 text-red-600 border-red-200',
        refunded: 'bg-orange-50 text-orange-600 border-orange-200',
        disputed: 'bg-purple-50 text-purple-700 border-purple-200',
        cancelled: 'bg-gray-50 text-gray-600 border-gray-200',
      };
      return map[status] || 'bg-gray-50 text-gray-600 border-gray-200';
    },

    init() {
      this.fetchPayments();
      this.$watch('fullReceipt', value => {
        document.body.classList.toggle('full-receipt-mode', value);
      });
    },

    async fetchPayments() {
      this.loading = true;
      try {
        const params = new URLSearchParams({ page: this.page, limit: 20 });
        if (this.statusFilter) params.set('status', this.statusFilter);
        if (this.search) params.set('search', this.search);
        const res = await fetch(`{{ route('admin.payments') }}?${params.toString()}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const data = await res.json();
        this.payments = data.payments || [];
        this.stats = data.stats || [];
        this.hasMore = data.has_more || false;
      } catch (e) {
        console.error('Failed to fetch payments', e);
      } finally {
        this.loading = false;
      }
    },

    showReceipt(payment) {
      this.receipt = payment;
    },

    printReceipt() {
      window.print();
    },

    printFullReceipt() {
      this.fullReceipt = true;
      this.$nextTick(() => window.print());
    },

    prevPage() {
      if (this.page > 1) { this.page--; this.fetchPayments(); }
    },

    nextPage() {
      if (this.hasMore) { this.page++; this.fetchPayments(); }
    }
  }
}
</script>
@endpush
@endsection
