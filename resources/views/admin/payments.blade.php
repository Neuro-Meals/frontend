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
  <div x-show="receipt" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none">
    <div class="absolute inset-0 bg-black/40" @click="receipt = null"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" @click.outside="receipt = null">
      <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] p-5 text-white text-center relative">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <svg class="w-10 h-10 mx-auto mb-2 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <h3 class="text-sm font-bold relative z-10">{{ __('Payment Receipt') }}</h3>
        <p class="text-[10px] text-white/60 mt-0.5 relative z-10" x-text="receipt?.id"></p>
      </div>
      <div class="p-5 space-y-3">
        <div class="flex justify-between items-center py-2 border-b border-gray-50">
          <span class="text-[10px] text-gray-400">{{ __('Subscription') }}</span>
          <span class="text-xs font-semibold text-gray-900" x-text="receipt?.order"></span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-50">
          <span class="text-[10px] text-gray-400">{{ __('Amount') }}</span>
          <span class="text-sm font-bold text-gray-900" x-text="'SAR ' + receipt?.amount"></span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-50">
          <span class="text-[10px] text-gray-400">{{ __('Status') }}</span>
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="statusClass(receipt?.status)">
            <span x-text="receipt?.status?.charAt(0).toUpperCase() + receipt?.status?.slice(1)"></span>
          </span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-50">
          <span class="text-[10px] text-gray-400">{{ __('Date') }}</span>
          <span class="text-xs text-gray-600" x-text="receipt?.date"></span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-50">
          <span class="text-[10px] text-gray-400">{{ __('Stripe Session') }}</span>
          <span class="text-[10px] text-gray-400 font-mono truncate max-w-[140px]" x-text="receipt?.stripe_session_id || '—'"></span>
        </div>
        <div class="flex justify-between items-center py-2">
          <span class="text-[10px] text-gray-400">{{ __('Payment ID') }}</span>
          <span class="text-[10px] text-gray-500 font-mono" x-text="receipt?.id"></span>
        </div>
      </div>
      <div class="px-5 pb-5 flex gap-2">
        <button @click="receipt = null" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
          {{ __('Close') }}
        </button>
        <button @click="printReceipt" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-md transition-all">
          {{ __('Print') }}
        </button>
      </div>
    </div>
  </div>
</div>

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
