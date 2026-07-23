@extends('layouts.admin')

@section('title', __('Customer-Drivers') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Customer-Driver Assignments'))

@section('content')
<div x-data="customerDriverManager()" x-init="init()" class="space-y-4">
    @php
        $statusColors = [
            'active' => 'bg-green-50 text-green-700 border-green-200',
            'inactive' => 'bg-gray-50 text-gray-500 border-gray-200',
        ];
    @endphp

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
    @endif

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-4 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -mr-10 -mt-10"></div>
            <p class="text-[10px] text-white/70 mb-1">{{ __('Total Assignments') }}</p>
            <p class="text-2xl font-bold">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] text-gray-400 mb-1">{{ __('Active') }}</p>
            <p class="text-2xl font-bold text-green-600">{{ collect($formattedAssignments)->where('is_active', true)->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] text-gray-400 mb-1">{{ __('Inactive') }}</p>
            <p class="text-2xl font-bold text-gray-500">{{ collect($formattedAssignments)->where('is_active', false)->count() }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex items-center justify-between cursor-pointer hover:shadow-md transition-all" @click="openAssignModal()">
            <div>
                <p class="text-[10px] text-gray-400 mb-1">{{ __('Action') }}</p>
                <p class="text-sm font-bold text-[#6E7A25]">{{ __('+ Assign Driver') }}</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-[#6E7A25]/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-3 shadow-sm flex flex-wrap items-center gap-2">
        <div class="flex items-center bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100 flex-1 min-w-[160px]">
            <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" x-model="search" @input.debounce.300ms="loadAssignments()" placeholder="{{ __('Search by customer or driver...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400 w-20">
        </div>
        <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">
            <input type="checkbox" x-model="activeOnly" @change="loadAssignments()" class="w-3.5 h-3.5 rounded border-gray-300 text-[#6E7A25] focus:ring-[#6E7A25]">
            {{ __('Active only') }}
        </label>
        <button @click="loadAssignments()" class="px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-all shadow-sm">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('Refresh') }}
        </button>
    </div>

    {{-- Assignments Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Customer') }}</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Driver') }}</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Reason') }}</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Assigned') }}</th>
                        <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="assignment in assignments" :key="assignment.id">
                        <tr class="hover:bg-gray-50/30 transition-colors">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0" x-text="assignment.customer_name.charAt(0).toUpperCase()"></div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900" x-text="assignment.customer_name"></p>
                                        <p class="text-[10px] text-gray-400" x-text="assignment.customer_phone"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-[10px] flex-shrink-0" x-text="assignment.driver_name.charAt(0).toUpperCase()"></div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900" x-text="assignment.driver_name"></p>
                                        <p class="text-[10px] text-gray-400" x-text="assignment.driver_phone"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-xs text-gray-600" x-text="assignment.assignment_reason || '—'"></p>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-xs text-gray-500" x-text="formatDate(assignment.assigned_at)"></p>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="assignment.is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'" x-text="assignment.is_active ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"></span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="openChangeModal(assignment)" class="text-[10px] font-bold text-[#6E7A25] hover:underline px-1.5 py-1 rounded hover:bg-[#6E7A25]/5 transition-all">{{ __('Change') }}</button>
                                    <button @click="viewHistory(assignment.customer_id)" class="text-[10px] font-bold text-blue-600 hover:underline px-1.5 py-1 rounded hover:bg-blue-50 transition-all">{{ __('History') }}</button>
                                    <button @click="confirmRemove(assignment)" class="text-[10px] font-bold text-red-500 hover:underline px-1.5 py-1 rounded hover:bg-red-50 transition-all">{{ __('Remove') }}</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="assignments.length === 0 && !loading">
                        <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">{{ __('No customer-driver assignments found.') }}</td>
                    </tr>
                    <tr x-show="loading">
                        <td colspan="6" class="px-4 py-12 text-center">
                            <div class="animate-pulse flex justify-center space-x-2">
                                <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce"></div>
                                <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Assign Modal --}}
    <div x-show="assignModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="assignModalOpen" x-transition class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="assignModalOpen = false"></div>
        <div x-show="assignModalOpen" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Assign Driver to Customer') }}</h3>
                <button @click="assignModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <form @submit.prevent="submitAssign()" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Customer ID') }} <span class="text-red-500">*</span></label>
                    <input type="number" x-model="assignForm.customer_id" required min="1" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none" placeholder="{{ __('Enter customer user ID') }}">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Select Driver') }} <span class="text-red-500">*</span></label>
                    <select x-model="assignForm.driver_id" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        <option value="">{{ __('Choose a driver...') }}</option>
                        <template x-for="driver in drivers" :key="driver.id">
                            <option :value="driver.id" x-text="driver.name + ' · ' + driver.phone"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Assignment Reason') }}</label>
                    <input type="text" x-model="assignForm.assignment_reason" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none" placeholder="{{ __('e.g. Same delivery zone') }}">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Notes') }}</label>
                    <textarea x-model="assignForm.notes" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none" placeholder="{{ __('Optional notes') }}"></textarea>
                </div>
                <div x-show="assignError" x-text="assignError" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                <div x-show="assignSuccess" x-text="assignSuccess" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="assignModalOpen = false" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">{{ __('Cancel') }}</button>
                    <button type="submit" :disabled="assigning" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md disabled:opacity-60">
                        <span x-show="!assigning">{{ __('Assign Driver') }}</span>
                        <span x-show="assigning">{{ __('Assigning...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Change Modal --}}
    <div x-show="changeModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="changeModalOpen" x-transition class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="changeModalOpen = false"></div>
        <div x-show="changeModalOpen" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Change Driver') }}</h3>
                <button @click="changeModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="bg-gray-50 rounded-xl p-3 mb-4">
                <p class="text-xs text-gray-500">{{ __('Customer') }}: <span class="font-bold text-gray-900" x-text="changeTarget?.customer_name"></span></p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Current Driver') }}: <span class="font-bold text-gray-900" x-text="changeTarget?.driver_name"></span></p>
            </div>
            <form @submit.prevent="submitChange()" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('New Driver') }} <span class="text-red-500">*</span></label>
                    <select x-model="changeForm.driver_id" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        <option value="">{{ __('Choose a driver...') }}</option>
                        <template x-for="driver in drivers" :key="driver.id">
                            <option :value="driver.id" x-text="driver.name + ' · ' + driver.phone" :disabled="driver.id === changeTarget?.driver_id"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Assignment Reason') }}</label>
                    <input type="text" x-model="changeForm.assignment_reason" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none" placeholder="{{ __('e.g. Customer requested change') }}">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Notes') }}</label>
                    <textarea x-model="changeForm.notes" rows="2" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none" placeholder="{{ __('Optional notes') }}"></textarea>
                </div>
                <div x-show="changeError" x-text="changeError" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                <div x-show="changeSuccess" x-text="changeSuccess" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="changeModalOpen = false" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">{{ __('Cancel') }}</button>
                    <button type="submit" :disabled="changing" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md disabled:opacity-60">
                        <span x-show="!changing">{{ __('Change Driver') }}</span>
                        <span x-show="changing">{{ __('Changing...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Remove Confirmation Modal --}}
    <div x-show="removeModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="removeModalOpen" x-transition class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="removeModalOpen = false"></div>
        <div x-show="removeModalOpen" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">{{ __('Remove Driver Assignment') }}</h3>
                    <p class="text-xs text-gray-500" x-text="removeTarget ? removeTarget.customer_name + ' → ' + removeTarget.driver_name : ''"></p>
                </div>
            </div>
            <p class="text-xs text-gray-600 mb-5">{{ __('Are you sure you want to remove this driver assignment? The customer will no longer have a dedicated driver. Assignment history is preserved.') }}</p>
            <div class="flex items-center justify-end gap-2">
                <button @click="removeModalOpen = false" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">{{ __('Cancel') }}</button>
                <button @click="submitRemove()" :disabled="removing" class="px-4 py-2 rounded-lg bg-red-500 text-white text-xs font-bold hover:bg-red-600 disabled:opacity-60">
                    <span x-show="!removing">{{ __('Remove') }}</span>
                    <span x-show="removing">{{ __('Removing...') }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- History Modal --}}
    <div x-show="historyModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="historyModalOpen" x-transition class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="historyModalOpen = false"></div>
        <div x-show="historyModalOpen" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[80vh] overflow-hidden flex flex-col">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 flex-shrink-0">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Assignment History') }}</h3>
                <button @click="historyModalOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="flex-1 overflow-y-auto p-5 space-y-3">
                <template x-for="item in historyItems" :key="item.id">
                    <div class="border border-gray-100 rounded-xl p-3" :class="item.is_active ? 'bg-green-50/30 border-green-100' : ''">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-[10px]" x-text="item.driver_name.charAt(0).toUpperCase()"></div>
                                <div>
                                    <p class="text-xs font-bold text-gray-900" x-text="item.driver_name"></p>
                                    <p class="text-[10px] text-gray-400" x-text="item.driver_phone"></p>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="item.is_active ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'" x-text="item.is_active ? '{{ __('Active') }}' : '{{ __('Ended') }}'"></span>
                        </div>
                        <div class="text-[10px] text-gray-500 space-y-0.5">
                            <p x-show="item.assignment_reason"><span class="font-bold">{{ __('Reason') }}:</span> <span x-text="item.assignment_reason"></span></p>
                            <p x-show="item.notes"><span class="font-bold">{{ __('Notes') }}:</span> <span x-text="item.notes"></span></p>
                            <p><span class="font-bold">{{ __('Assigned') }}:</span> <span x-text="formatDate(item.assigned_at)"></span></p>
                            <p x-show="item.ended_at"><span class="font-bold">{{ __('Ended') }}:</span> <span x-text="formatDate(item.ended_at)"></span></p>
                        </div>
                    </div>
                </template>
                <div x-show="historyItems.length === 0" class="text-center py-8 text-gray-400 text-sm">{{ __('No assignment history found.') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function customerDriverManager() {
    return {
        assignments: @json($formattedAssignments),
        drivers: @json($drivers),
        search: '',
        activeOnly: true,
        loading: false,
        assignModalOpen: false,
        changeModalOpen: false,
        removeModalOpen: false,
        historyModalOpen: false,
        assigning: false,
        changing: false,
        removing: false,
        assignError: '',
        assignSuccess: '',
        changeError: '',
        changeSuccess: '',
        assignForm: { customer_id: '', driver_id: '', assignment_reason: '', notes: '' },
        changeForm: { driver_id: '', assignment_reason: '', notes: '' },
        changeTarget: null,
        removeTarget: null,
        historyItems: [],

        init() { this.loadAssignments(); },

        formatDate(d) {
            if (!d) return '—';
            try { return new Date(d).toLocaleDateString(); } catch(e) { return d; }
        },

        async loadAssignments() {
            this.loading = true;
            try {
                const params = new URLSearchParams();
                if (this.search) params.set('search', this.search);
                params.set('active_only', this.activeOnly ? '1' : '0');
                const res = await fetch('{{ route('admin.customer-drivers') }}?' + params.toString(), {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                if (data.success) {
                    this.assignments = data.assignments || [];
                    this.drivers = data.drivers || this.drivers;
                }
            } catch(e) { console.error('loadAssignments error:', e); }
            this.loading = false;
        },

        openAssignModal() {
            this.assignForm = { customer_id: '', driver_id: '', assignment_reason: '', notes: '' };
            this.assignError = ''; this.assignSuccess = '';
            this.assignModalOpen = true;
        },

        async submitAssign() {
            this.assigning = true; this.assignError = ''; this.assignSuccess = '';
            try {
                const res = await fetch('{{ route('admin.customer-drivers.assign') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.assignForm),
                });
                const data = await res.json();
                if (data.success) {
                    this.assignSuccess = data.message || '{{ __('Driver assigned successfully.') }}';
                    await this.loadAssignments();
                    setTimeout(() => { this.assignModalOpen = false; }, 1500);
                } else {
                    this.assignError = data.message || '{{ __('Failed to assign driver.') }}';
                }
            } catch(e) { this.assignError = '{{ __('Network error.') }}'; }
            this.assigning = false;
        },

        openChangeModal(assignment) {
            this.changeTarget = assignment;
            this.changeForm = { driver_id: '', assignment_reason: '', notes: '' };
            this.changeError = ''; this.changeSuccess = '';
            this.changeModalOpen = true;
        },

        async submitChange() {
            if (!this.changeTarget) return;
            this.changing = true; this.changeError = ''; this.changeSuccess = '';
            try {
                const res = await fetch('{{ route('admin.customer-drivers.change', ['customerId' => '__ID__']) }}'.replace('__ID__', this.changeTarget.customer_id), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify(this.changeForm),
                });
                const data = await res.json();
                if (data.success) {
                    this.changeSuccess = data.message || '{{ __('Driver changed successfully.') }}';
                    await this.loadAssignments();
                    setTimeout(() => { this.changeModalOpen = false; }, 1500);
                } else {
                    this.changeError = data.message || '{{ __('Failed to change driver.') }}';
                }
            } catch(e) { this.changeError = '{{ __('Network error.') }}'; }
            this.changing = false;
        },

        confirmRemove(assignment) {
            this.removeTarget = assignment;
            this.removeModalOpen = true;
        },

        async submitRemove() {
            if (!this.removeTarget) return;
            this.removing = true;
            try {
                const res = await fetch('{{ route('admin.customer-drivers.remove', ['customerId' => '__ID__']) }}'.replace('__ID__', this.removeTarget.customer_id), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                });
                const data = await res.json();
                if (data.success) {
                    this.removeModalOpen = false;
                    await this.loadAssignments();
                } else {
                    alert(data.message || '{{ __('Failed to remove assignment.') }}');
                }
            } catch(e) { alert('{{ __('Network error.') }}'); }
            this.removing = false;
        },

        async viewHistory(customerId) {
            this.historyModalOpen = true;
            this.historyItems = [];
            try {
                const res = await fetch('{{ route('admin.customer-drivers.history', ['customerId' => '__ID__']) }}'.replace('__ID__', customerId), {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                if (data.success) this.historyItems = data.history || [];
            } catch(e) { console.error('History error:', e); }
        },
    };
}
</script>
@endpush
