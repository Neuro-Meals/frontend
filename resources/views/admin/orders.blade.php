@extends('layouts.admin')

@section('title', __('Orders') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Orders'))

@section('content')
<div x-data="ordersApp()" x-init="init()" class="space-y-4">

  {{-- Stats Row --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="!loading">
    <template x-for="s in stats" :key="s.label">
      <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] text-gray-400 mb-0.5" x-text="s.label"></p>
        <p class="text-lg font-bold" :class="s.color" x-text="s.value"></p>
      </div>
    </template>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="loading">
    <template x-for="i in 4">
      <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm animate-pulse"><div class="h-3 bg-gray-100 rounded w-1/2 mb-2"></div><div class="h-6 bg-gray-100 rounded w-3/4"></div></div>
    </template>
  </div>

  {{-- Filter Bar --}}
  <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex flex-wrap items-center gap-2">
    <div class="flex items-center bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100 flex-1 min-w-[160px]">
      <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      <input type="text" x-model="search" @input.debounce.300ms="fetchOrders()" placeholder="{{ __('Search order...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400 w-20">
    </div>
    <select x-model="statusFilter" @change="fetchOrders()" class="text-xs border border-gray-100 rounded-lg px-2 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer">
      <option value="">{{ __('All Status') }}</option>
      <option value="pending">{{ __('Pending') }}</option>
      <option value="preparing">{{ __('Preparing') }}</option>
      <option value="out_for_delivery">{{ __('Out for Delivery') }}</option>
      <option value="delivered">{{ __('Delivered') }}</option>
      <option value="cancelled">{{ __('Cancelled') }}</option>
    </select>
    <button @click="fetchOrders()" class="px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-all shadow-sm whitespace-nowrap">
      <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
      {{ __('Refresh') }}
    </button>
  </div>

  {{-- Orders Table --}}
  <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-[10px] text-gray-400 bg-gray-50/50 border-b border-gray-100">
            <th class="px-4 py-2.5 font-medium">{{ __('Order') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Customer') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Plan') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Amount') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Delivery') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Date') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Status') }}</th>
            <th class="px-4 py-2.5 font-medium"></th>
          </tr>
        </thead>
        <tbody>
          <template x-if="loading && orders.length === 0">
            <tr><td colspan="8" class="px-4 py-8"><div class="space-y-2 animate-pulse"><template x-for="i in 4"><div class="h-8 bg-gray-50 rounded"></div></template></div></td></tr>
          </template>
          <template x-if="!loading && orders.length === 0">
            <tr><td colspan="8" class="px-4 py-8 text-center text-xs text-gray-400">{{ __('No orders found.') }}</td></tr>
          </template>
          <template x-for="order in orders" :key="order.id">
            <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors cursor-pointer" @click="showDetail(order)">
              <td class="px-4 py-2.5"><span class="text-xs font-bold text-gray-900" x-text="order.id"></span></td>
              <td class="px-4 py-2.5">
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-[9px] flex-shrink-0" x-text="order.customer?.charAt(0)?.toUpperCase()"></div>
                  <span class="text-xs font-medium text-gray-700" x-text="order.customer"></span>
                </div>
              </td>
              <td class="px-4 py-2.5 text-xs text-gray-500" x-text="order.plan"></td>
              <td class="px-4 py-2.5"><span class="text-xs font-bold text-gray-900" x-text="'SAR ' + order.amount"></span></td>
              <td class="px-4 py-2.5 text-xs text-gray-500" x-text="order.delivery"></td>
              <td class="px-4 py-2.5 text-xs text-gray-400" x-text="order.date"></td>
              <td class="px-4 py-2.5">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border whitespace-nowrap" :class="statusClass(order.status)">
                  <span x-text="statusLabel(order.status)"></span>
                </span>
              </td>
              <td class="px-4 py-2.5 text-right">
                <button @click.stop="showDetail(order)" class="text-[10px] font-bold text-[#6E7A25] hover:underline">{{ __('View') }}</button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-50 flex items-center justify-between">
      <p class="text-[10px] text-gray-400" x-text="`{{ __('Showing') }} ${orders.length} {{ __('orders') }}`"></p>
      <div class="flex items-center gap-1">
        <button @click="prevPage" x-show="page > 1" class="px-2.5 py-1 text-[10px] font-medium text-gray-500 rounded-lg hover:bg-gray-50 transition-colors">{{ __('Prev') }}</button>
        <span class="px-2 py-1 text-[10px] font-bold text-white bg-[#6E7A25] rounded-lg" x-text="page"></span>
        <button @click="nextPage" x-show="hasMore" class="px-2.5 py-1 text-[10px] font-medium text-gray-500 rounded-lg hover:bg-gray-50 transition-colors">{{ __('Next') }}</button>
      </div>
    </div>
  </div>

  {{-- Order Detail Slide-Out Panel --}}
  <div x-show="selected" class="fixed inset-0 z-50 flex justify-end" style="display: none">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="selected = null"></div>
    <div class="relative w-full max-w-lg bg-white shadow-2xl h-full overflow-y-auto" @click.outside="selected = null">
      {{-- Header --}}
      <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] p-6 text-white sticky top-0 z-10">
        <div class="flex items-center justify-between mb-2">
          <div>
            <h3 class="text-base font-bold">{{ __('Order Details') }}</h3>
            <p class="text-xs text-white/70" x-text="selected?.id"></p>
          </div>
          <button @click="selected = null" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="flex items-center gap-2 mt-3">
          <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border border-white/20 bg-white/10" :class="statusClass(selected?.status)">
            <span x-text="statusLabel(selected?.status)"></span>
          </span>
          <span class="text-xs text-white/70" x-text="selected?.date"></span>
        </div>
      </div>

      <div class="p-6 space-y-6">
        {{-- Customer Info --}}
        <div class="bg-gray-50 rounded-xl p-4">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Customer') }}</h4>
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-base flex-shrink-0 shadow-md" x-text="selected?.customer?.charAt(0)?.toUpperCase()"></div>
            <div class="flex-1">
              <p class="text-sm font-semibold text-gray-900" x-text="selected?.customer"></p>
              <p class="text-xs text-gray-500" x-text="selected?.customer_email || ''"></p>
              <p class="text-xs text-gray-400 mt-0.5" x-text="selected?.customer_phone || ''"></p>
            </div>
          </div>
        </div>

        {{-- Order Summary --}}
        <div class="bg-gray-50 rounded-xl p-4">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Order Summary') }}</h4>
          <div class="space-y-3">
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
              <span class="text-xs text-gray-500">{{ __('Plan') }}</span>
              <span class="text-xs font-semibold text-gray-900" x-text="selected?.plan"></span>
            </div>
            <div class="flex justify-between items-center pb-2 border-b border-gray-200">
              <span class="text-xs text-gray-500">{{ __('Amount') }}</span>
              <span class="text-sm font-bold text-[#6E7A25]" x-text="'SAR ' + selected?.amount"></span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-xs text-gray-500">{{ __('Payment') }}</span>
              <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-gray-900" x-text="selected?.payment_method || 'N/A'"></span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="paymentStatusClass(selected?.payment_status)">
                  <span x-text="selected?.payment_status ? selected.payment_status.charAt(0).toUpperCase() + selected.payment_status.slice(1) : 'N/A'"></span>
                </span>
              </div>
            </div>
          </div>
        </div>

        {{-- Delivery Info --}}
        <div class="bg-gray-50 rounded-xl p-4">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Delivery') }}</h4>
          <div class="space-y-3">
            <div class="flex items-start gap-2">
              <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <div>
                <p class="text-xs text-gray-500">{{ __('Address') }}</p>
                <p class="text-xs font-medium text-gray-900 mt-0.5" x-text="selected?.address || '—'"></p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <div>
                <p class="text-xs text-gray-500">{{ __('Window') }}</p>
                <p class="text-xs font-medium text-gray-900 mt-0.5" x-text="selected?.delivery"></p>
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

        {{-- Items --}}
        <template x-if="selected?.items && selected.items.length > 0">
          <div>
            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Items') }}</h4>
            <div class="bg-gray-50 rounded-xl p-4 space-y-2">
              <template x-for="(item, i) in selected.items" :key="i">
                <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#6E7A25]/20 to-[#173327]/20 flex items-center justify-center">
                      <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                      <p class="text-xs font-medium text-gray-900" x-text="item.name || item.meal_name || 'Item'"></p>
                      <p class="text-[10px] text-gray-400" x-text="item.description || ''"></p>
                    </div>
                  </div>
                  <span class="text-xs font-bold text-[#6E7A25 bg-[#6E7A25]/10 px-2 py-1 rounded-full" x-text="'×' + (item.quantity || 1)"></span>
                </div>
              </template>
            </div>
          </div>
        </template>

        {{-- Notes --}}
        <div>
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Notes') }}</h4>
          <div class="bg-gray-50 rounded-xl p-4">
            <textarea class="w-full text-xs text-gray-700 bg-transparent outline-none resize-none h-20" placeholder="{{ __('Add notes about this order...') }}" x-model="selected?.notes"></textarea>
          </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-2 pt-2">
          <button @click="printOrder" class="flex-1 px-4 py-2.5 text-xs font-bold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            {{ __('Print') }}
          </button>
          <button class="flex-1 px-4 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-xl hover:shadow-lg transition-all flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('Refresh') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function ordersApp() {
  return {
    orders: [],
    stats: [],
    selected: null,
    search: '',
    statusFilter: '',
    page: 1,
    hasMore: false,
    loading: true,

    statusClass(s) {
      const m = { delivered:'bg-green-50 text-green-700 border-green-200', out_for_delivery:'bg-blue-50 text-blue-700 border-blue-200', en_route:'bg-blue-50 text-blue-700 border-blue-200', preparing:'bg-amber-50 text-amber-700 border-amber-200', pending:'bg-gray-50 text-gray-600 border-gray-200', cancelled:'bg-red-50 text-red-600 border-red-200' };
      return m[s] || 'bg-gray-50 text-gray-600 border-gray-200';
    },
    paymentStatusClass(s) {
      const m = { paid:'bg-green-50 text-green-700 border-green-200', unpaid:'bg-amber-50 text-amber-700 border-amber-200', pending:'bg-amber-50 text-amber-700 border-amber-200', failed:'bg-red-50 text-red-600 border-red-200', refunded:'bg-purple-50 text-purple-700 border-purple-200' };
      return m[s] || 'bg-gray-50 text-gray-600 border-gray-200';
    },
    statusLabel(s) {
      const m = { delivered:'Delivered', out_for_delivery:'Out for Delivery', en_route:'En Route', preparing:'Preparing', pending:'Pending', cancelled:'Cancelled' };
      return m[s] || s;
    },

    init() { this.fetchOrders(); },

    async fetchOrders() {
      this.loading = true;
      try {
        const p = new URLSearchParams({ page: this.page, limit: 20 });
        if (this.statusFilter) p.set('status', this.statusFilter);
        if (this.search) p.set('search', this.search);
        const r = await fetch(`{{ route('admin.orders') }}?${p.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const d = await r.json();
        this.orders = d.orders || [];
        this.stats = d.stats || [];
        this.hasMore = d.has_more || false;
      } catch(e) { console.error('Failed to fetch orders', e); }
      finally { this.loading = false; }
    },

    showDetail(order) { this.selected = order; },

    prevPage() { if (this.page > 1) { this.page--; this.fetchOrders(); } },
    nextPage() { if (this.hasMore) { this.page++; this.fetchOrders(); } }
  }
}
</script>
@endpush
@endsection
