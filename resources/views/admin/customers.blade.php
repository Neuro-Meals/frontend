@extends('layouts.admin')

@section('title', __('Customers') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Customers'))

@section('content')
<div x-data="customersApp()" x-init="init()" class="space-y-4">

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
  <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="text-left text-[10px] text-gray-400 bg-gray-50/50 border-b border-gray-100">
            <th class="px-4 py-2.5 font-medium">{{ __('Customer') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Contact') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Plan') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Orders') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Spent') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Status') }}</th>
            <th class="px-4 py-2.5 font-medium">{{ __('Joined') }}</th>
            <th class="px-4 py-2.5 font-medium"></th>
          </tr>
        </thead>
        <tbody>
          <template x-if="loading && customers.length === 0">
            <tr><td colspan="8" class="px-4 py-8"><div class="space-y-2 animate-pulse"><template x-for="i in 4"><div class="h-8 bg-gray-50 rounded"></div></template></div></td></tr>
          </template>
          <template x-if="!loading && customers.length === 0">
            <tr><td colspan="8" class="px-4 py-8 text-center text-xs text-gray-400">{{ __('No customers found.') }}</td></tr>
          </template>
          <template x-for="c in customers" :key="c.id">
            <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors cursor-pointer" @click="showDetail(c)">
              <td class="px-4 py-2.5">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0" x-text="c.name?.charAt(0)?.toUpperCase()"></div>
                  <span class="text-xs font-semibold text-gray-900" x-text="c.name"></span>
                </div>
              </td>
              <td class="px-4 py-2.5">
                <p class="text-xs text-gray-500" x-text="c.email"></p>
                <p class="text-[10px] text-gray-400" x-text="c.phone"></p>
              </td>
              <td class="px-4 py-2.5 text-xs text-gray-600" x-text="c.plan"></td>
              <td class="px-4 py-2.5"><span class="text-xs font-bold text-gray-900" x-text="c.orders"></span></td>
              <td class="px-4 py-2.5"><span class="text-xs font-bold text-gray-900" x-text="'SAR ' + c.spent.toLocaleString()"></span></td>
              <td class="px-4 py-2.5">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border whitespace-nowrap" :class="statusClass(c.status)">
                  <span x-text="c.status?.charAt(0)?.toUpperCase() + c.status?.slice(1)"></span>
                </span>
              </td>
              <td class="px-4 py-2.5 text-xs text-gray-400" x-text="c.joined"></td>
              <td class="px-4 py-2.5 text-right">
                <button @click.stop="showDetail(c)" class="text-[10px] font-bold text-[#6E7A25] hover:underline">{{ __('View') }}</button>
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
    <div class="absolute inset-0 bg-black/30" @click="selected = null"></div>
    <div class="relative w-full max-w-lg bg-white shadow-2xl h-full overflow-y-auto" @click.outside="selected = null">

      {{-- Header --}}
      <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] p-5 text-white sticky top-0 z-10">
        <div class="flex items-center justify-between mb-3">
          <h3 class="text-sm font-bold">{{ __('Customer Details') }}</h3>
          <button @click="selected = null" class="text-white/60 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-lg flex-shrink-0" x-text="selected?.name?.charAt(0)?.toUpperCase()"></div>
          <div>
            <p class="text-base font-bold" x-text="selected?.name"></p>
            <p class="text-xs text-white/60" x-text="selected?.email"></p>
          </div>
        </div>
      </div>

      <div class="p-5 space-y-5">

        {{-- Quick Actions --}}
        <div class="flex gap-2">
          <button @click="assignPlan(selected)" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-[#6E7A25] text-white hover:bg-[#5a6820] transition-all">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('Assign Plan') }}
          </button>
          <button @click="viewPayments(selected)" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('Payments') }}
          </button>
        </div>

        <div class="border-t border-gray-50"></div>

        {{-- Profile Info --}}
        <div>
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Profile') }}</h4>
          <div class="space-y-2.5">
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Name') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.name"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Email') }}</span><span class="text-xs text-gray-600" x-text="selected?.email"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Phone') }}</span><span class="text-xs font-semibold text-gray-900" x-text="selected?.phone || '—'"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Joined') }}</span><span class="text-xs text-gray-600" x-text="selected?.joined"></span></div>
            <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Status') }}</span>
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="statusClass(selected?.status)">
                <span x-text="selected?.status?.charAt(0)?.toUpperCase() + selected?.status?.slice(1)"></span>
              </span>
            </div>
          </div>
        </div>

        <div class="border-t border-gray-50"></div>

        {{-- Current Subscription --}}
        <div x-show="selected?.subscription">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Current Subscription') }}</h4>
          <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25]/10 rounded-xl p-4 border border-[#6E7A25]/20">
            <div class="space-y-2">
              <div class="flex justify-between items-center"><span class="text-xs text-gray-500">{{ __('Plan') }}</span><span class="text-xs font-bold text-gray-900" x-text="selected?.subscription?.plan_name || selected?.plan"></span></div>
              <div class="flex justify-between items-center"><span class="text-xs text-gray-500">{{ __('Amount') }}</span><span class="text-sm font-bold text-[#6E7A25]" x-text="'SAR ' + (selected?.subscription?.amount || 0)"></span></div>
              <div class="flex justify-between items-center"><span class="text-xs text-gray-500">{{ __('Start') }}</span><span class="text-xs text-gray-600" x-text="selected?.subscription?.start_date || '—'"></span></div>
              <div class="flex justify-between items-center"><span class="text-xs text-gray-500">{{ __('End') }}</span><span class="text-xs text-gray-600" x-text="selected?.subscription?.end_date || '—'"></span></div>
              <div class="flex justify-between items-center"><span class="text-xs text-gray-500">{{ __('Payment') }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="paymentStatusClass(selected?.subscription?.payment_status)">
                  <span x-text="selected?.subscription?.payment_status ? selected.subscription.payment_status.charAt(0).toUpperCase() + selected.subscription.payment_status.slice(1) : 'N/A'"></span>
                </span>
              </div>
            </div>
          </div>
        </div>

        <div class="border-t border-gray-50"></div>

        {{-- Subscription History --}}
        <div x-show="selected?.subscriptions && selected.subscriptions.length > 0">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Subscription History') }}</h4>
          <div class="space-y-1.5">
            <template x-for="sub in selected.subscriptions" :key="sub.id">
              <div class="flex items-center justify-between py-1.5 px-3 bg-gray-50 rounded-lg">
                <div>
                  <p class="text-xs font-semibold text-gray-900" x-text="sub.plan_name || sub.plan || 'Plan'"></p>
                  <p class="text-[10px] text-gray-400" x-text="sub.start_date ? sub.start_date + ' — ' + (sub.end_date || 'ongoing') : ''"></p>
                </div>
                <div class="text-right">
                  <p class="text-xs font-bold text-gray-900" x-text="'SAR ' + (sub.amount || 0)"></p>
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-semibold border" :class="statusClass(sub.status)">
                    <span x-text="sub.status?.charAt(0)?.toUpperCase() + sub.status?.slice(1)"></span>
                  </span>
                </div>
              </div>
            </template>
          </div>
        </div>

        <div class="border-t border-gray-50"></div>

        {{-- Orders --}}
        <div x-show="selected?.orders && selected.orders.length > 0">
          <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Recent Orders') }}</h4>
          <div class="space-y-1.5">
            <template x-for="o in selected.orders" :key="o.id">
              <div class="flex items-center justify-between py-1.5">
                <div>
                  <p class="text-xs font-semibold text-gray-900" x-text="o.id"></p>
                  <p class="text-[10px] text-gray-400" x-text="o.date"></p>
                </div>
                <div class="text-right">
                  <p class="text-xs font-bold text-gray-900" x-text="'SAR ' + (o.amount || 0)"></p>
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-semibold border" :class="statusClass(o.status)">
                    <span x-text="o.status?.charAt(0)?.toUpperCase() + o.status?.slice(1)"></span>
                  </span>
                </div>
              </div>
            </template>
          </div>
        </div>

        {{-- Payments --}}
        <div x-show="selected?.payments && selected.payments.length > 0">
          <div class="border-t border-gray-50"></div>
          <div class="mt-4">
            <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Payments') }}</h4>
            <div class="space-y-1.5">
              <template x-for="p in selected.payments" :key="p.id">
                <div class="flex items-center justify-between py-1.5">
                  <div>
                    <p class="text-xs font-semibold text-gray-900" x-text="p.id"></p>
                    <p class="text-[10px] text-gray-400" x-text="p.date"></p>
                  </div>
                  <div class="text-right">
                    <p class="text-xs font-bold text-gray-900" x-text="'SAR ' + (p.amount || 0)"></p>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-semibold border" :class="paymentStatusClass(p.status)">
                      <span x-text="p.status?.charAt(0)?.toUpperCase() + p.status?.slice(1)"></span>
                    </span>
                  </div>
                </div>
              </template>
            </div>
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

</div>

@push('scripts')
<script>
function customersApp() {
  return {
    customers: [],
    stats: [],
    plans: [],
    selected: null,
    showAssignPlan: false,
    assignTarget: null,
    assignPlanId: '',
    assigning: false,
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
      const m = { paid:'bg-green-50 text-green-700 border-green-200', unpaid:'bg-amber-50 text-amber-700 border-amber-200', pending:'bg-amber-50 text-amber-700 border-amber-200', failed:'bg-red-50 text-red-600 border-red-200', refunded:'bg-purple-50 text-purple-700 border-purple-200' };
      return m[s] || 'bg-gray-50 text-gray-600 border-gray-200';
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
      try {
        const r = await fetch(`{{ url('admin/customers') }}/${c.id}/details`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
        const d = await r.json();
        if (d.customer) Object.assign(this.selected, d.customer);
      } catch(e) { console.error('Failed to fetch customer details', e); }
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

    prevPage() { if (this.page > 1) { this.page--; this.fetchCustomers(); } },
    nextPage() { if (this.hasMore) { this.page++; this.fetchCustomers(); } }
  }
}
</script>
@endpush
@endsection
