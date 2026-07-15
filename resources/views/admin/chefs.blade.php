@extends('layouts.admin')

@section('title', __('Chefs') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Chef Management'))

@section('content')
<div x-data="chefManager()" x-init="init()" class="space-y-4">
    @php
        $statusColors = [
            'active' => 'bg-green-50 text-green-700 border-green-200',
            'inactive' => 'bg-gray-50 text-gray-500 border-gray-200',
        ];
        $statusLabels = [
            'active' => __('Active'), 'inactive' => __('Inactive'),
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
            <p class="text-[10px] text-white/70 mb-1">{{ __('Total Chefs') }}</p>
            <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] text-gray-400 mb-1">{{ __('Active') }}</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] text-gray-400 mb-1">{{ __('Inactive') }}</p>
            <p class="text-2xl font-bold text-gray-500">{{ $stats['inactive'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex items-center justify-between cursor-pointer hover:shadow-md transition-all" @click="openCreate()">
            <div>
                <p class="text-[10px] text-gray-400 mb-1">{{ __('Action') }}</p>
                <p class="text-sm font-bold text-[#6E7A25]">{{ __('+ Add Chef') }}</p>
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
            <input type="text" x-model="search" placeholder="{{ __('Search chefs...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400">
        </div>
        <button @click="loadChefs()" class="px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-all shadow-sm">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('Refresh') }}
        </button>
        <button @click="assignOpen = true" class="px-3 py-1.5 text-xs font-bold text-[#6E7A25] bg-[#6E7A25]/10 rounded-lg hover:bg-[#6E7A25]/20 transition-all">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            {{ __('Assign Existing User') }}
        </button>
    </div>

    {{-- Chefs Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <template x-for="chef in filteredChefs" :key="chef.id">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-all group">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#C2410C] to-[#EA580C] flex items-center justify-center text-white font-bold text-base flex-shrink-0" x-text="(chef.name || 'C').charAt(0).toUpperCase()"></div>
                        <div>
                            <p class="text-sm font-bold text-gray-900" x-text="chef.name"></p>
                            <p class="text-[10px] text-gray-400" x-text="chef.email + ' · ' + chef.phone"></p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="chef.status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'" x-text="chef.status === 'active' ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"></span>
                </div>
                <div class="space-y-1 text-[10px] text-gray-500 mb-4">
                    <p x-show="chef.location"><svg class="w-3 h-3 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span x-text="chef.location"></span></p>
                    <p x-show="chef.address"><svg class="w-3 h-3 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span x-text="chef.address"></span></p>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                    <div class="flex items-center gap-2">
                        <button @click="editChef(chef)" class="text-[10px] font-bold text-[#6E7A25] hover:underline">{{ __('Edit') }}</button>
                        <span class="text-gray-200">|</span>
                        <button @click="toggleStatus(chef)" class="text-[10px] font-bold" :class="chef.status === 'active' ? 'text-red-500 hover:underline' : 'text-green-600 hover:underline'" x-text="chef.status === 'active' ? '{{ __('Deactivate') }}' : '{{ __('Activate') }}'"></button>
                        <span class="text-gray-200">|</span>
                        <button @click="removeRole(chef)" class="text-[10px] font-bold text-gray-400 hover:text-red-500 hover:underline">{{ __('Remove Role') }}</button>
                    </div>
                </div>
            </div>
        </template>
        <div x-show="filteredChefs.length === 0 && !loading" class="col-span-full p-12 text-center text-gray-400 text-sm bg-white rounded-2xl border border-gray-100">
            {{ __('No chefs found.') }}
        </div>
        <div x-show="loading" class="col-span-full p-12 text-center bg-white rounded-2xl border border-gray-100">
            <div class="animate-pulse flex justify-center space-x-2">
                <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce"></div>
                <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
            </div>
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex justify-end">
        <div x-show="modalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="closeModal()"></div>
        <div x-show="modalOpen" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full rtl:-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full rtl:-translate-x-full" class="absolute inset-y-0 right-0 rtl:right-auto rtl:left-0 w-full sm:w-[28rem] bg-white shadow-2xl">
            <div class="h-full flex flex-col">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900" x-text="form.id ? '{{ __('Edit Chef') }}' : '{{ __('Add Chef') }}'"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <form class="space-y-4" @submit.prevent="saveChef">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('First Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" x-model="form.first_name" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Last Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" x-model="form.last_name" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Email') }} <span class="text-red-500">*</span></label>
                                <input type="email" x-model="form.email" :required="!form.id" :disabled="!!form.id" :class="form.id ? 'bg-gray-50 text-gray-500' : ''" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none disabled:cursor-not-allowed">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Phone') }} <span class="text-red-500">*</span></label>
                                <input type="tel" x-model="form.phone" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                            <div x-show="!form.id" class="col-span-2">
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Password') }} <span class="text-red-500">*</span></label>
                                <input type="text" x-model="form.password" required minlength="6" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Location') }}</label>
                                <input type="text" x-model="form.location" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Address') }}</label>
                                <input type="text" x-model="form.address" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                        </div>
                        <div x-show="error" x-text="error" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                        <div x-show="success" x-text="success" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="resetForm()" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">{{ __('Reset') }}</button>
                            <button type="submit" :disabled="saving" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md disabled:opacity-60">
                                <span x-show="!saving" x-text="form.id ? '{{ __('Update Chef') }}' : '{{ __('Add Chef') }}'"></span>
                                <span x-show="saving">{{ __('Saving...') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Assign Existing User Modal --}}
    <div x-show="assignOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="assignOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="assignOpen = false"></div>
        <div x-show="assignOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">{{ __('Assign Existing User as Chef') }}</h3>
                <button @click="assignOpen = false" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <p class="text-xs text-gray-500 mb-4">{{ __('Enter the user ID of an existing customer to promote them to chef role.') }}</p>
            <form @submit.prevent="assignUser">
                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('User ID') }} <span class="text-red-500">*</span></label>
                <input type="number" x-model="assignUserId" required min="1" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none mb-4">
                <div x-show="assignError" x-text="assignError" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2 mb-3"></div>
                <div x-show="assignSuccess" x-text="assignSuccess" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2 mb-3"></div>
                <div class="flex items-center justify-end gap-2">
                    <button type="button" @click="assignOpen = false" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">{{ __('Cancel') }}</button>
                    <button type="submit" :disabled="assignSaving" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm disabled:opacity-60">
                        <span x-show="!assignSaving">{{ __('Assign Role') }}</span>
                        <span x-show="assignSaving">{{ __('Assigning...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function chefManager() {
    return {
        chefs: @json($chefs),
        search: '',
        loading: false,
        modalOpen: false,
        saving: false,
        error: '',
        success: '',
        form: { id: null, first_name: '', last_name: '', email: '', phone: '', password: '', location: '', address: '' },
        assignOpen: false,
        assignUserId: '',
        assignSaving: false,
        assignError: '',
        assignSuccess: '',

        get filteredChefs() {
            const term = this.search.toLowerCase().trim();
            if (!term) return this.chefs;
            return this.chefs.filter(c =>
                (c.name || '').toLowerCase().includes(term) ||
                (c.email || '').toLowerCase().includes(term) ||
                (c.phone || '').toLowerCase().includes(term) ||
                (c.location || '').toLowerCase().includes(term)
            );
        },

        init() { if (this.chefs.length === 0) this.loadChefs(); },

        async loadChefs() {
            this.loading = true;
            try {
                const res = await fetch('{{ route('admin.chefs') }}', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                const data = await res.json();
                if (data.success) {
                    this.chefs = data.chefs || [];
                }
            } catch (e) {}
            this.loading = false;
        },

        openCreate() {
            this.resetForm();
            this.modalOpen = true;
        },

        editChef(chef) {
            this.form = { ...chef, password: '' };
            this.error = ''; this.success = '';
            this.modalOpen = true;
        },

        resetForm() {
            this.form = { id: null, first_name: '', last_name: '', email: '', phone: '', password: '', location: '', address: '' };
            this.error = ''; this.success = '';
        },

        closeModal() {
            this.modalOpen = false;
            this.resetForm();
        },

        async saveChef() {
            this.saving = true; this.error = ''; this.success = '';
            const isEdit = !!this.form.id;
            const url = isEdit ? '{{ url('admin/chefs') }}/' + this.form.id : '{{ route('admin.chefs.store') }}';
            const formData = new FormData();
            const fields = isEdit
                ? ['id', 'first_name', 'last_name', 'phone', 'location', 'address']
                : ['first_name', 'last_name', 'email', 'phone', 'password', 'location', 'address'];
            fields.forEach(key => { const v = this.form[key]; if (v !== null && v !== undefined && v !== '') formData.append(key, v); });
            if (isEdit) formData.append('_method', 'PUT');
            try {
                const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: formData });
                const data = await res.json();
                if (data.success) {
                    await this.loadChefs();
                    this.resetForm();
                    this.modalOpen = false;
                    Swal.fire({ icon: 'success', title: data.message || '{{ __('Chef saved successfully.') }}', timer: 2000, showConfirmButton: false });
                } else {
                    this.error = data.message || '{{ __('Failed to save chef.') }}';
                }
            } catch (e) {
                this.error = '{{ __('Network error. Please try again.') }}';
            } finally {
                this.saving = false;
            }
        },

        async toggleStatus(chef) {
            const action = chef.status === 'active' ? 'deactivate' : 'activate';
            const confirmText = chef.status === 'active'
                ? `{{ __('Deactivate') }} ${chef.name}?`
                : `{{ __('Activate') }} ${chef.name}?`;
            if (!confirm(confirmText)) return;
            try {
                const formData = new FormData();
                const res = await fetch(`{{ url('admin/chefs') }}/${chef.id}/${action}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: formData });
                const data = await res.json();
                if (data.success) {
                    await this.loadChefs();
                    Swal.fire({ icon: 'success', title: data.message, timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: data.message || '{{ __('Failed.') }}' });
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: '{{ __('Network error.') }}' });
            }
        },

        async removeRole(chef) {
            if (!confirm(`{{ __('Remove chef role from') }} ${chef.name}? {{ __('They will become a regular customer.') }}`)) return;
            try {
                const formData = new FormData();
                const res = await fetch(`{{ url('admin/chefs') }}/${chef.id}/remove-role`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: formData });
                const data = await res.json();
                if (data.success) {
                    await this.loadChefs();
                    Swal.fire({ icon: 'success', title: data.message, timer: 2000, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: data.message || '{{ __('Failed.') }}' });
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: '{{ __('Network error.') }}' });
            }
        },

        async assignUser() {
            this.assignSaving = true; this.assignError = ''; this.assignSuccess = '';
            try {
                const formData = new FormData();
                formData.append('user_id', this.assignUserId);
                const res = await fetch('{{ route('admin.chefs.assign-existing') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }, body: formData });
                const data = await res.json();
                if (data.success) {
                    this.assignSuccess = data.message;
                    await this.loadChefs();
                    setTimeout(() => { this.assignOpen = false; this.assignUserId = ''; this.assignSuccess = ''; }, 1500);
                } else {
                    this.assignError = data.message || '{{ __('Failed to assign role.') }}';
                }
            } catch (e) {
                this.assignError = '{{ __('Network error.') }}';
            } finally {
                this.assignSaving = false;
            }
        }
    };
}
</script>
@endpush
