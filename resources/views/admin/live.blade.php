@extends('layouts.admin')

@section('title', __('Live') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Live Operations'))

@section('content')
<div x-data="liveApp()" x-init="init()" class="space-y-4">

  {{-- Header Stats --}}
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3" x-show="!loading">
    <template x-for="s in stats" :key="s.label">
      <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <p class="text-[10px] text-gray-400 mb-0.5" x-text="s.label"></p>
        <p class="text-lg font-bold" :class="s.color" x-text="s.value"></p>
      </div>
    </template>
  </div>

  {{-- Controls --}}
  <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex items-center justify-between">
    <div class="flex items-center gap-2">
      <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
      <span class="text-xs font-bold text-gray-700">{{ __('Live') }}</span>
      <span class="text-[10px] text-gray-400" x-text="'{{ __('Updated') }}: ' + lastUpdated"></span>
    </div>
    <div class="flex items-center gap-2">
      <button @click="tab = 'deliveries'" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all" :class="tab === 'deliveries' ? 'bg-[#6E7A25] text-white shadow-sm' : 'bg-gray-50 text-gray-500 hover:bg-gray-100'">
        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
        {{ __('Deliveries') }}
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-white/20" x-text="counts.pending_deliveries"></span>
      </button>
      <button @click="tab = 'orders'" class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all" :class="tab === 'orders' ? 'bg-[#6E7A25] text-white shadow-sm' : 'bg-gray-50 text-gray-500 hover:bg-gray-100'">
        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        {{ __('Orders') }}
        <span class="ml-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-white/20" x-text="counts.today_orders"></span>
      </button>
      <div class="w-px h-6 bg-gray-100 mx-1"></div>
      <button @click="fetchLiveData()" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gray-50 text-gray-500 hover:bg-gray-100 transition-all flex items-center gap-1">
        <svg class="w-3.5 h-3.5" :class="{'animate-spin': refreshing}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        {{ __('Refresh') }}
      </button>
    </div>
  </div>

  {{-- Deliveries Tab --}}
  <div x-show="tab === 'deliveries'" class="space-y-2">
    <template x-if="loading">
      <div class="space-y-2"><template x-for="i in 4"><div class="h-16 bg-gray-50 rounded-xl animate-pulse"></div></template></div>
    </template>
    <template x-for="d in deliveries" :key="d.id">
      <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:shadow-sm hover:border-gray-200 transition-all bg-white">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" :class="{
          'bg-green-50 text-green-600': d.status === 'delivered',
          'bg-blue-50 text-blue-600': ['en_route','out_for_delivery'].includes(d.status),
          'bg-purple-50 text-purple-600': d.status === 'assigned',
          'bg-amber-50 text-amber-600': ['pending','preparing','scheduled'].includes(d.status),
          'bg-red-50 text-red-600': ['failed','cancelled'].includes(d.status)
        }">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
        </div>
        <div class="flex-1 min-w-0 grid grid-cols-4 gap-2 text-xs">
          <div><p class="text-[10px] text-gray-400">{{ __('Delivery') }}</p><p class="font-semibold text-gray-900 truncate" x-text="d.label"></p></div>
          <div><p class="text-[10px] text-gray-400">{{ __('Order') }}</p><p class="font-semibold text-gray-900 truncate" x-text="d.order"></p></div>
          <div><p class="text-[10px] text-gray-400">{{ __('Customer') }}</p><p class="font-medium text-gray-700 truncate" x-text="d.customer"></p></div>
          <div><p class="text-[10px] text-gray-400">{{ __('Zone') }}</p><p class="font-medium text-gray-700 truncate" x-text="d.zone"></p></div>
        </div>
        <div class="flex-shrink-0 w-44">
          <template x-if="d.status !== 'delivered' && d.status !== 'cancelled'">
            <div class="flex items-center gap-1.5">
              <select x-model="d.driver_id" @change="assignDriver(d)" class="text-[10px] border border-gray-100 rounded-lg px-2 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer w-full">
                <option value="">{{ __('Assign driver...') }}</option>
                <template x-for="dr in drivers" :key="dr.id">
                  <option :value="dr.id" x-text="dr.name"></option>
                </template>
              </select>
              <span x-show="d.assigned" class="text-green-500 flex-shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></span>
            </div>
          </template>
          <template x-if="d.status === 'delivered'">
            <span class="text-[10px] font-bold text-green-600 flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ __('Delivered') }}</span>
          </template>
        </div>
        <div class="flex-shrink-0 w-24 text-right">
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold border" :class="{
            'bg-green-50 text-green-700 border-green-200': d.status === 'delivered',
            'bg-blue-50 text-blue-700 border-blue-200': ['en_route','out_for_delivery'].includes(d.status),
            'bg-purple-50 text-purple-700 border-purple-200': d.status === 'assigned',
            'bg-amber-50 text-amber-700 border-amber-200': ['pending','preparing','scheduled'].includes(d.status),
            'bg-red-50 text-red-600 border-red-200': ['failed','cancelled'].includes(d.status)
          }" x-text="d.status.replace('_',' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
          <p class="text-[9px] text-gray-400 mt-0.5" x-text="d.time"></p>
        </div>
      </div>
    </template>
    <template x-if="!loading && deliveries.length === 0">
      <div class="text-center py-16">
        <svg class="w-16 h-16 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
        <p class="text-sm text-gray-400">{{ __('No deliveries for today.') }}</p>
      </div>
    </template>
  </div>

  {{-- Orders Tab --}}
  <div x-show="tab === 'orders'" class="space-y-2">
    <template x-if="loading">
      <div class="space-y-2"><template x-for="i in 4"><div class="h-16 bg-gray-50 rounded-xl animate-pulse"></div></template></div>
    </template>
    <template x-for="o in orders" :key="o.id">
      <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:shadow-sm hover:border-gray-200 transition-all bg-white">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <div class="flex-1 min-w-0 grid grid-cols-4 gap-2 text-xs">
          <div><p class="text-[10px] text-gray-400">{{ __('Order') }}</p><p class="font-semibold text-gray-900 truncate" x-text="o.id"></p></div>
          <div><p class="text-[10px] text-gray-400">{{ __('Customer') }}</p><p class="font-medium text-gray-700 truncate" x-text="o.customer"></p></div>
          <div><p class="text-[10px] text-gray-400">{{ __('Plan') }}</p><p class="font-medium text-gray-700 truncate" x-text="o.plan"></p></div>
          <div><p class="text-[10px] text-gray-400">{{ __('Amount') }}</p><p class="font-bold text-gray-900" x-text="'SAR ' + o.amount.toLocaleString()"></p></div>
        </div>
        <div class="flex-shrink-0 flex items-center gap-2">
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold border" :class="{
            'bg-green-50 text-green-700 border-green-200': o.status === 'delivered',
            'bg-blue-50 text-blue-700 border-blue-200': o.status === 'en_route',
            'bg-amber-50 text-amber-700 border-amber-200': ['pending','preparing'].includes(o.status),
            'bg-red-50 text-red-600 border-red-200': o.status === 'cancelled'
          }" x-text="o.status.charAt(0).toUpperCase() + o.status.slice(1)"></span>
          <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold border" :class="{
            'bg-green-50 text-green-700 border-green-200': o.payment_status === 'paid',
            'bg-amber-50 text-amber-700 border-amber-200': o.payment_status === 'unpaid' || o.payment_status === 'pending',
            'bg-red-50 text-red-600 border-red-200': o.payment_status === 'failed'
          }" x-text="o.payment_status.charAt(0).toUpperCase() + o.payment_status.slice(1)"></span>
        </div>
      </div>
    </template>
    <template x-if="!loading && orders.length === 0">
      <div class="text-center py-16">
        <svg class="w-16 h-16 mx-auto text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <p class="text-sm text-gray-400">{{ __('No orders for today.') }}</p>
      </div>
    </template>
  </div>

</div>

@push('scripts')
<script>
function liveApp() {
  return {
    tab: 'deliveries',
    deliveries: [],
    orders: [],
    drivers: [],
    stats: [],
    counts: { pending_deliveries: 0, unassigned: 0, today_orders: 0 },
    lastUpdated: '—',
    loading: true,
    refreshing: false,

    init() { this.fetchLiveData(); setInterval(() => this.fetchLiveData(), 30000); },

    async fetchLiveData() {
      this.refreshing = true;
      try {
        const r = await fetch('{{ route('admin.dashboard.live') }}', {
          headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        const d = await r.json();
        this.deliveries = (d.deliveries || []).map(dl => ({ ...dl, assigned: false }));
        this.orders = d.orders || [];
        this.drivers = d.drivers || [];
        this.counts = d.counts || { pending_deliveries: 0, unassigned: 0, today_orders: 0 };
        this.stats = [
          { label: '{{ __('Pending Deliveries') }}', value: this.counts.pending_deliveries, color: 'text-amber-600' },
          { label: '{{ __('Unassigned') }}', value: this.counts.unassigned, color: 'text-red-600' },
          { label: "{{ __(\"Today's Orders\") }}", value: this.counts.today_orders, color: 'text-[#6E7A25]' },
          { label: '{{ __('Available Drivers') }}', value: this.drivers.length, color: 'text-gray-900' },
        ];
        this.lastUpdated = new Date().toLocaleTimeString();
      } catch(e) { console.error('Failed to fetch live data', e); }
      finally { this.refreshing = false; this.loading = false; }
    },

    async assignDriver(d) {
      if (!d.driver_id) return;
      d.assigned = false;
      try {
        const r = await fetch('{{ url('admin/deliveries') }}/' + d.id + '/assign-driver', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
          body: JSON.stringify({ driver_id: d.driver_id })
        });
        if (r.ok) {
          d.assigned = true;
          const driver = this.drivers.find(dr => dr.id == d.driver_id);
          d.driver = driver ? driver.name : 'Assigned';
          this.counts.unassigned = Math.max(0, this.counts.unassigned - 1);
          setTimeout(() => { d.assigned = false; }, 2000);
        }
      } catch(e) { console.error('Failed to assign driver', e); }
    }
  }
}
</script>
@endpush
@endsection
