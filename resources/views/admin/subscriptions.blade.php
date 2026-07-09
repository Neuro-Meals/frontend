@extends('layouts.admin')

@section('title', __('Subscriptions') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Subscriptions'))

@section('content')
<div x-data="subscriptionManager()" x-init="init()">
    @php
        $statusColors = [
            'active' => 'bg-green-50 text-green-700 border-green-200',
            'paused' => 'bg-amber-50 text-amber-700 border-amber-200',
            'pending_payment' => 'bg-blue-50 text-blue-700 border-blue-200',
            'cancelled' => 'bg-red-50 text-red-600 border-red-200',
            'expired' => 'bg-gray-50 text-gray-500 border-gray-200',
        ];
        $paymentColors = [
            'paid' => 'bg-green-50 text-green-700 border-green-200',
            'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
            'unpaid' => 'bg-blue-50 text-blue-700 border-blue-200',
            'failed' => 'bg-red-50 text-red-600 border-red-200',
            'refunded' => 'bg-purple-50 text-purple-700 border-purple-200',
        ];
    @endphp

    {{-- Flash Messages --}}
    @if(session('status'))
    <div class="mb-4 bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">
        {{ session('status') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="relative z-10">
                <p class="text-xs text-white/60 font-medium mb-1">{{ __('Total Subscriptions') }}</p>
                <p class="text-2xl font-bold tracking-tight" x-text="stats.total">{{ $stats['total'] }}</p>
                <p class="text-xs text-white/50 mt-1">{{ __('All time subscriptions') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs text-gray-400 font-medium">{{ __('Active') }}</p>
            </div>
            <p class="text-2xl font-bold text-gray-900" x-text="stats.active">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs text-gray-400 font-medium">{{ __('MRR') }}</p>
            </div>
            <p class="text-2xl font-bold text-gray-900" x-text="'SAR ' + Number(stats.mrr).toLocaleString()">SAR {{ number_format($stats['mrr']) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-xs text-gray-400 font-medium">{{ __('Pending') }}</p>
            </div>
            <p class="text-2xl font-bold text-gray-900" x-text="stats.pending">{{ $stats['pending'] }}</p>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm mb-6">
        <div class="flex flex-col md:flex-row gap-3 md:items-center justify-between">
            <div class="flex flex-col sm:flex-row gap-3 flex-1">
                <div class="relative flex-1 max-w-sm">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input x-model="search" @input.debounce.300ms="loadSubscriptions()" type="text" placeholder="{{ __('Search customer or plan...') }}" class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                </div>
                <select x-model="statusFilter" @change="loadSubscriptions()" class="px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-white">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="active">{{ __('Active') }}</option>
                    <option value="paused">{{ __('Paused') }}</option>
                    <option value="pending_payment">{{ __('Pending Payment') }}</option>
                    <option value="cancelled">{{ __('Cancelled') }}</option>
                    <option value="expired">{{ __('Expired') }}</option>
                </select>
                <select x-model="paymentFilter" @change="loadSubscriptions()" class="px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-white">
                    <option value="">{{ __('All Payments') }}</option>
                    <option value="paid">{{ __('Paid') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="unpaid">{{ __('Unpaid') }}</option>
                    <option value="failed">{{ __('Failed') }}</option>
                    <option value="refunded">{{ __('Refunded') }}</option>
                </select>
            </div>
            <button @click="openCreate" class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Subscription') }}
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">{{ __('ID') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Customer') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Plan') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Amount') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Payment') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Period') }}</th>
                        <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr><td colspan="8" class="p-8 text-center text-gray-400">{{ __('Loading...') }}</td></tr>
                    </template>
                    <template x-if="!loading && subscriptions.length === 0">
                        <tr><td colspan="8" class="p-8 text-center text-gray-400">{{ __('No subscriptions found.') }}</td></tr>
                    </template>
                    <template x-for="sub in subscriptions" :key="sub.id">
                        <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                            <td class="px-6 py-3.5"><span class="text-xs font-bold text-gray-900" x-text="'#' + sub.id"></span></td>
                            <td class="px-6 py-3.5">
                                <div>
                                    <p class="text-xs font-bold text-gray-900" x-text="sub.customer"></p>
                                    <p class="text-[10px] text-gray-400" x-text="sub.customer_email"></p>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs font-medium text-gray-700" x-text="sub.plan_name"></td>
                            <td class="px-6 py-3.5 text-xs font-bold text-gray-900" x-text="'SAR ' + Number(sub.amount).toLocaleString()"></td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border" :class="statusClass(sub.status)" x-text="formatStatus(sub.status)"></span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border" :class="paymentClass(sub.payment_status)" x-text="formatStatus(sub.payment_status)"></span>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="text-xs text-gray-500" x-text="sub.start_date ? new Date(sub.start_date).toLocaleDateString() : '-'"></p>
                                <p class="text-[10px] text-gray-400" x-text="sub.end_date ? new Date(sub.end_date).toLocaleDateString() : '-'"></p>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editSubscription(sub)" class="p-1.5 text-[#6E7A25] hover:bg-[#6E7A25]/10 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.43-9.121a2.948 2.948 0 00-4.172 0L11.879 5.88a2.948 2.948 0 000 4.172l5.586 5.586a2.948 2.948 0 004.172 0l.586-.586a2.948 2.948 0 000-4.172l-5.586-5.586z"/></svg>
                                    </button>
                                    <button @click="cancelSubscription(sub)" x-show="sub.status !== 'cancelled' && sub.status !== 'expired'" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Cancel') }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50" aria-modal="true">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div x-show="modalOpen" x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-y-10 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-10 opacity-0" class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white">
                    <h3 class="text-base font-bold" x-text="form.id ? '{{ __('Edit Subscription') }}' : '{{ __('New Subscription') }}'"></h3>
                    <button @click="closeModal()" class="p-1 text-white/70 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form class="p-6 space-y-4" @submit.prevent="saveSubscription">
                    <input type="hidden" x-model="form.id">
                    <div x-show="!form.id">
                        <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Customer') }} <span class="text-red-500">*</span></label>
                        <select x-model="form.user_id" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-white">
                            <option value="">{{ __('Select customer') }}</option>
                            @foreach($users as $user)
                            <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['email'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-show="!form.id">
                        <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Plan') }} <span class="text-red-500">*</span></label>
                        <select x-model="form.plan_id" required @change="updateAmount" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-white">
                            <option value="">{{ __('Select plan') }}</option>
                            @foreach($plans as $plan)
                            <option value="{{ $plan['id'] }}" data-price="{{ $plan['price'] }}">{{ $plan['name'] }} - SAR {{ number_format($plan['price']) }} / {{ $plan['duration_days'] }} days</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-show="form.id">
                        <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Status') }} <span class="text-red-500">*</span></label>
                        <select x-model="form.status" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-white">
                            <option value="active">{{ __('Active') }}</option>
                            <option value="paused">{{ __('Paused') }}</option>
                            <option value="pending_payment">{{ __('Pending Payment') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                            <option value="expired">{{ __('Expired') }}</option>
                        </select>
                    </div>
                    <div x-show="form.id">
                        <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Payment Status') }} <span class="text-red-500">*</span></label>
                        <select x-model="form.payment_status" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-white">
                            <option value="paid">{{ __('Paid') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="unpaid">{{ __('Unpaid') }}</option>
                            <option value="failed">{{ __('Failed') }}</option>
                            <option value="refunded">{{ __('Refunded') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Notes') }}</label>
                        <textarea x-model="form.notes" rows="3" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none"></textarea>
                    </div>
                    <div x-show="formError" x-text="formError" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                    <div x-show="formSuccess" x-text="formSuccess" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="closeModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">{{ __('Cancel') }}</button>
                        <button type="submit" :disabled="saving" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-60">
                            <span x-show="!saving" x-text="form.id ? '{{ __('Update Subscription') }}' : '{{ __('Create Subscription') }}'"></span>
                            <span x-show="saving">{{ __('Saving...') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function subscriptionManager() {
    return {
        subscriptions: @json($subscriptions),
        stats: @json($stats),
        loading: false,
        saving: false,
        modalOpen: false,
        search: '',
        statusFilter: '',
        paymentFilter: '',
        formError: '',
        formSuccess: '',
        form: {
            id: null,
            user_id: '',
            plan_id: '',
            status: 'active',
            payment_status: 'unpaid',
            notes: '',
        },

        init() {
            // initial server-rendered data is already loaded
        },

        async loadSubscriptions() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.statusFilter) params.set('status', this.statusFilter);
                if (this.paymentFilter) params.set('payment_status', this.paymentFilter);
                if (this.search) params.set('search', this.search);
                const res = await fetch(`{{ route('admin.subscriptions') }}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.success) {
                    this.subscriptions = data.subscriptions || [];
                    this.stats = data.stats || this.stats;
                }
            } catch (e) {
                console.error('Failed to load subscriptions', e);
            } finally {
                this.loading = false;
            }
        },

        statusClass(status) {
            const map = {
                active: 'bg-green-50 text-green-700 border-green-200',
                paused: 'bg-amber-50 text-amber-700 border-amber-200',
                pending_payment: 'bg-blue-50 text-blue-700 border-blue-200',
                cancelled: 'bg-red-50 text-red-600 border-red-200',
                expired: 'bg-gray-50 text-gray-500 border-gray-200',
            };
            return map[status] || 'bg-gray-50 text-gray-600 border-gray-200';
        },

        paymentClass(status) {
            const map = {
                paid: 'bg-green-50 text-green-700 border-green-200',
                pending: 'bg-amber-50 text-amber-700 border-amber-200',
                unpaid: 'bg-blue-50 text-blue-700 border-blue-200',
                failed: 'bg-red-50 text-red-600 border-red-200',
                refunded: 'bg-purple-50 text-purple-700 border-purple-200',
            };
            return map[status] || 'bg-gray-50 text-gray-600 border-gray-200';
        },

        formatStatus(status) {
            if (!status) return '';
            return status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },

        updateAmount() {
            // optional: auto-fill amount from selected plan option
        },

        openCreate() {
            this.resetForm();
            this.modalOpen = true;
        },

        editSubscription(sub) {
            this.form = {
                id: sub.id,
                user_id: sub.user_id,
                plan_id: sub.plan_id,
                status: sub.status,
                payment_status: sub.payment_status,
                notes: sub.notes || '',
            };
            this.formError = '';
            this.formSuccess = '';
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
            this.resetForm();
        },

        resetForm() {
            this.form = { id: null, user_id: '', plan_id: '', status: 'active', payment_status: 'unpaid', notes: '' };
            this.formError = '';
            this.formSuccess = '';
        },

        async saveSubscription() {
            this.saving = true;
            this.formError = '';
            this.formSuccess = '';
            const isEdit = !!this.form.id;
            const url = isEdit ? `{{ url('admin/subscriptions') }}/${this.form.id}` : '{{ route('admin.subscriptions.store') }}';
            const formData = new FormData();
            const fields = isEdit ? ['id', 'status', 'payment_status', 'notes'] : ['user_id', 'plan_id', 'notes'];
            fields.forEach(key => {
                const value = this.form[key];
                if (value !== null && value !== undefined) formData.append(key, value);
            });
            if (isEdit) formData.append('_method', 'PATCH');

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData,
                });
                const data = await res.json();
                if (data.success) {
                    await this.loadSubscriptions();
                    this.formSuccess = data.message || (isEdit ? '{{ __('Subscription updated.') }}' : '{{ __('Subscription created.') }}');
                    if (!isEdit) this.resetForm();
                } else {
                    this.formError = data.message || '{{ __('Failed to save subscription.') }}';
                }
            } catch (e) {
                this.formError = '{{ __('Network error. Please try again.') }}';
            } finally {
                this.saving = false;
            }
        },

        async cancelSubscription(sub) {
            if (!confirm(`{{ __('Are you sure you want to cancel subscription') }} #${sub.id}?`)) return;
            try {
                const res = await fetch(`{{ url('admin/subscriptions') }}/${sub.id}/cancel`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                if (data.success) {
                    await this.loadSubscriptions();
                } else {
                    alert(data.message || '{{ __('Failed to cancel subscription.') }}');
                }
            } catch (e) {
                alert('{{ __('Network error. Please try again.') }}');
            }
        },
    };
}
</script>
@endpush
