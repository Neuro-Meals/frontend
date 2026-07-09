@extends('layouts.admin')

@section('title', __('Payments') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Payments'))

@section('content')
<div
  x-data="paymentsApp()"
  x-init="init()"
  class="space-y-4"
>
  {{-- Stats Row --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="!loading">
    <template x-for="stat in stats" :key="stat.label">
      <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-1.5">
          <div class="w-9 h-9 rounded-lg flex items-center justify-center" :style="'background:'+stat.bg">
            <svg class="w-4 h-4" :style="'color:'+stat.color" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"/></svg>
          </div>
          <span class="text-[10px] font-bold" :class="stat.trendClass" x-text="stat.trend"></span>
        </div>
        <p class="text-[10px] text-gray-400 mb-0.5" x-text="stat.label"></p>
        <p class="text-lg font-bold text-gray-900" x-text="stat.value"></p>
      </div>
    </template>
  </div>

  {{-- Skeleton loader for stats --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="loading">
    <template x-for="i in 4">
      <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm animate-pulse">
        <div class="h-3 bg-gray-100 rounded w-1/2 mb-2"></div>
        <div class="h-6 bg-gray-100 rounded w-3/4"></div>
      </div>
    </template>
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
            <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors cursor-pointer" @click="showReceipt(payment)">
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
                <button @click.stop="showReceipt(payment)" class="text-[10px] font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-2.5 py-1 rounded-lg hover:shadow-md transition-all">
                  {{ __('Receipt') }}
                </button>
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

  {{-- Receipt Modal --}}
  <div x-show="receipt" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none" x-cloak>
    <div x-show="receipt" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="receipt = null"></div>
    <div x-show="receipt" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.outside="receipt = null">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] p-6 text-white text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12 blur-xl"></div>
        <div class="relative z-10">
          <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-white/15 backdrop-blur flex items-center justify-center">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <h3 class="text-base font-bold">{{ __('Payment Receipt') }}</h3>
          <p class="text-[10px] text-white/70 mt-1" x-text="receipt?.id"></p>
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold mt-3 border border-white/20" :class="statusClass(receipt?.status)">
            <span x-text="receipt?.status ? receipt.status.charAt(0).toUpperCase() + receipt.status.slice(1) : ''"></span>
          </span>
        </div>
      </div>

      {{-- Receipt Body --}}
      <div class="p-6 space-y-5">
        {{-- Customer --}}
        <div>
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Customer') }}</h4>
          <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-sm flex-shrink-0" x-text="receipt?.customer ? receipt.customer.charAt(0).toUpperCase() : 'C'"></div>
            <div class="min-w-0">
              <p class="text-sm font-bold text-gray-900 truncate" x-text="receipt?.customer"></p>
              <p class="text-[10px] text-gray-500 truncate" x-text="receipt?.customer_email || receipt?.customer_phone || ''"></p>
            </div>
          </div>
        </div>

        {{-- Subscription --}}
        <div>
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Subscription') }}</h4>
          <div class="space-y-2">
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Plan') }}</span>
              <span class="text-xs font-semibold text-gray-900" x-text="receipt?.plan_name || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Subscription ID') }}</span>
              <span class="text-xs font-mono text-gray-700" x-text="receipt?.order || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Status') }}</span>
              <span class="text-xs font-semibold text-gray-900 capitalize" x-text="receipt?.subscription_status || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Period') }}</span>
              <span class="text-xs text-gray-600" x-text="(receipt?.subscription_start ? new Date(receipt.subscription_start).toLocaleDateString() : '—') + ' - ' + (receipt?.subscription_end ? new Date(receipt.subscription_end).toLocaleDateString() : '—')"></span>
            </div>
          </div>
        </div>

        {{-- Payment --}}
        <div>
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Payment Details') }}</h4>
          <div class="space-y-2">
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Amount') }}</span>
              <span class="text-base font-bold text-[#6E7A25]" x-text="receipt?.currency + ' ' + Number(receipt?.amount || 0).toLocaleString()"></span>
            </div>
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Method') }}</span>
              <span class="text-xs font-semibold text-gray-900" x-text="receipt?.method || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Provider') }}</span>
              <span class="text-xs font-semibold text-gray-900 capitalize" x-text="receipt?.provider || '—'"></span>
            </div>
            <div class="flex justify-between items-center py-1.5 border-b border-gray-50">
              <span class="text-[10px] text-gray-400">{{ __('Paid At') }}</span>
              <span class="text-xs text-gray-600" x-text="receipt?.paid_at ? new Date(receipt.paid_at).toLocaleString() : '—'"></span>
            </div>
            <div class="flex justify-between items-center py-1.5">
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

      {{-- Print-only version --}}
      <div id="printReceipt" class="hidden print:block p-8">
        <div class="text-center mb-6">
          <h2 class="text-2xl font-bold text-gray-900">{{ __('Nutrio Meals') }}</h2>
          <p class="text-sm text-gray-500">{{ __('Payment Receipt') }}</p>
        </div>
        <div class="space-y-4 text-sm">
          <div class="flex justify-between border-b border-gray-200 pb-2">
            <span class="text-gray-600">{{ __('Transaction ID') }}</span>
            <span class="font-mono" x-text="receipt?.id"></span>
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

      {{-- Actions --}}
      <div class="px-6 pb-6 flex gap-2 print:hidden">
        <button @click="receipt = null" class="flex-1 px-3 py-2.5 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
          {{ __('Close') }}
        </button>
        <button @click="printReceipt" class="flex-1 px-3 py-2.5 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-lg transition-all flex items-center justify-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
          {{ __('Print') }}
        </button>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
@media print {
  body > * { display: none !important; }
  div[x-data="paymentsApp()"] { display: block !important; position: static !important; }
  .fixed.inset-0 { position: static !important; display: block !important; }
  .fixed.inset-0 > .relative { box-shadow: none !important; border-radius: 0 !important; max-width: 100% !important; width: 100% !important; }
  #printReceipt { display: block !important; }
  .print\:hidden { display: none !important; }
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
    search: '',
    statusFilter: '',
    page: 1,
    hasMore: false,
    loading: true,

    statusClass(status) {
      const map = {
        paid: 'bg-green-50 text-green-700 border-green-200',
        completed: 'bg-green-50 text-green-700 border-green-200',
        pending: 'bg-amber-50 text-amber-700 border-amber-200',
        failed: 'bg-red-50 text-red-600 border-red-200',
        refunded: 'bg-purple-50 text-purple-700 border-purple-200',
        cancelled: 'bg-gray-50 text-gray-600 border-gray-200',
      };
      return map[status] || 'bg-gray-50 text-gray-600 border-gray-200';
    },

    init() {
      this.fetchPayments();
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
