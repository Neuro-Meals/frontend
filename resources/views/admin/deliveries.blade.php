@extends('layouts.admin')

@section('title', __('Deliveries') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Deliveries'))

@section('content')
<div x-data="deliveryManager()">
@php
    $statusColors = [
        'delivered' => 'bg-green-50 text-green-700 border-green-200',
        'en_route' => 'bg-blue-50 text-blue-700 border-blue-200',
        'out_for_delivery' => 'bg-blue-50 text-blue-700 border-blue-200',
        'preparing' => 'bg-amber-50 text-amber-700 border-amber-200',
        'assigned' => 'bg-purple-50 text-purple-700 border-purple-200',
        'scheduled' => 'bg-gray-50 text-gray-600 border-gray-200',
        'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
        'failed' => 'bg-red-50 text-red-600 border-red-200',
        'cancelled' => 'bg-red-50 text-red-600 border-red-200',
    ];
    $statusLabels = [
        'delivered' => __('Delivered'),
        'en_route' => __('En Route'),
        'out_for_delivery' => __('Out for Delivery'),
        'preparing' => __('Preparing'),
        'assigned' => __('Assigned'),
        'scheduled' => __('Scheduled'),
        'pending' => __('Pending'),
        'failed' => __('Failed'),
        'cancelled' => __('Cancelled'),
    ];
@endphp

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm">
    {{ session('error') }}
</div>
@endif

{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Total Today') }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Delivered') }}</p>
        <p class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('In Transit') }}</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['enRoute'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('On-Time Rate') }}</p>
        <p class="text-2xl font-bold text-[#6E7A25]">{{ $stats['onTimeRate'] }}%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Zones Summary --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Delivery Zones') }}</h3>
        <p class="text-xs text-gray-400 mb-5">{{ __("Today's performance by zone") }}</p>
        <div class="space-y-4">
            @foreach($zones as $zone)
            @php $pct = $zone['orders'] > 0 ? round($zone['completed'] / $zone['orders'] * 100) : 0; @endphp
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <p class="text-xs font-semibold text-gray-900">{{ $zone['name'] }}</p>
                        <p class="text-[10px] text-gray-400">{{ $zone['drivers'] }} {{ __('drivers') }} · {{ $zone['completed'] }}/{{ $zone['orders'] }} {{ __('completed') }}</p>
                    </div>
                    <span class="text-xs font-bold {{ $pct >= 80 ? 'text-green-600' : 'text-amber-600' }}">{{ $pct }}%</span>
                </div>
                <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                    <div class="h-full rounded-full {{ $pct >= 80 ? 'bg-green-500' : 'bg-amber-500' }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Deliveries Table --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Live Deliveries') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('Real-time delivery tracking') }}</p>
            </div>
            <button @click="openDriverManager()" class="text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] px-3 py-2 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ __('Manage Drivers') }}
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">{{ __('Delivery ID') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Customer') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Zone') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Driver') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('ETA') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Status') }}</th>
                        <th class="px-6 py-3 font-medium">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deliveries as $delivery)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                        <td class="px-6 py-3.5">
                            <span class="text-xs font-bold text-gray-900">{{ $delivery['id'] }}</span>
                            <p class="text-[10px] text-gray-400">{{ $delivery['order'] }}</p>
                        </td>
                        <td class="px-6 py-3.5 text-xs font-medium text-gray-700">{{ $delivery['customer'] }}</td>
                        <td class="px-6 py-3.5 text-xs text-gray-500">{{ $delivery['zone'] }}</td>
                        <td class="px-6 py-3.5">
                            <span class="text-xs {{ $delivery['driver'] === 'Unassigned' ? 'text-red-500' : 'text-gray-700' }}">{{ $delivery['driver'] === 'Unassigned' ? __('Unassigned') : $delivery['driver'] }}</span>
                        </td>
                        <td class="px-6 py-3.5">
                            <p class="text-xs text-gray-500">{{ $delivery['time'] }}</p>
                            <p class="text-[10px] {{ $delivery['eta'] === 'On time' ? 'text-green-600' : ($delivery['eta'] === 'Pending' ? 'text-gray-400' : 'text-amber-600') }}">{{ $delivery['eta'] === 'On time' ? __('On time') : ($delivery['eta'] === 'Pending' ? __('Pending') : $delivery['eta']) }}</p>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $statusColors[$delivery['status']] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">
                                {{ $statusLabels[$delivery['status']] ?? __(ucfirst($delivery['status'])) }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                @if(in_array($delivery['status'], ['pending', 'scheduled', 'assigned']))
                                {{-- Assign Driver --}}
                                <form action="{{ route('admin.deliveries.assign-driver', $delivery['id']) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    <select name="driver_id" class="text-[10px] border border-gray-200 rounded-lg px-1.5 py-1 bg-gray-50 outline-none">
                                        <option value="">{{ __('Assign...') }}</option>
                                        @foreach($drivers as $driver)
                                        <option value="{{ $driver['id'] }}">{{ $driver['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="text-[10px] font-bold text-[#6E7A25] hover:underline whitespace-nowrap">{{ __('Go') }}</button>
                                </form>
                                @endif
                                @if(in_array($delivery['status'], ['assigned', 'preparing', 'en_route']))
                                {{-- Update Status --}}
                                <form action="{{ route('admin.deliveries.update-status', $delivery['id']) }}" method="POST" class="inline-flex items-center gap-1">
                                    @csrf
                                    <select name="status" class="text-[10px] border border-gray-200 rounded-lg px-1.5 py-1 bg-gray-50 outline-none">
                                        <option value="preparing">{{ __('Preparing') }}</option>
                                        <option value="out_for_delivery">{{ __('Out for Delivery') }}</option>
                                        <option value="delivered">{{ __('Delivered') }}</option>
                                        <option value="failed">{{ __('Failed') }}</option>
                                    </select>
                                    <button type="submit" class="text-[10px] font-bold text-[#6E7A25] hover:underline whitespace-nowrap">{{ __('Update') }}</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Driver Management Modal --}}
<div x-show="driverModalOpen" x-cloak class="fixed inset-0 z-50" aria-labelledby="driver-modal-title" role="dialog" aria-modal="true">
    <div x-show="driverModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="closeDriverModal()"></div>

    <div x-show="driverModalOpen"
         x-transition:enter="transform transition ease-in-out duration-300"
         x-transition:enter-start="translate-x-full rtl:-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in-out duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full rtl:-translate-x-full"
         class="absolute inset-y-0 right-0 rtl:right-auto rtl:left-0 w-full sm:w-[32rem] lg:w-[36rem] bg-white shadow-2xl"
         style="max-width: 100vw;">
        <div class="h-full flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 flex-shrink-0">
                <h3 id="driver-modal-title" class="text-lg font-bold text-gray-900">{{ __('Manage Drivers') }}</h3>
                <button @click="closeDriverModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                {{-- Add / Edit Form --}}
                <form class="space-y-4 mb-6" @submit.prevent="saveDriver">
                    <input type="hidden" x-model="driverForm.id">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('First Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" x-model="driverForm.first_name" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Last Name') }} <span class="text-red-500">*</span></label>
                            <input type="text" x-model="driverForm.last_name" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Email') }} <span class="text-red-500">*</span></label>
                            <input type="email" x-model="driverForm.email" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Phone') }} <span class="text-red-500">*</span></label>
                            <input type="tel" x-model="driverForm.phone" required class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Location') }}</label>
                            <input type="text" x-model="driverForm.location" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Vehicle') }}</label>
                            <input type="text" x-model="driverForm.vehicle" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('License Number') }}</label>
                            <input type="text" x-model="driverForm.license_number" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                        </div>
                    </div>
                    <div x-show="driverError" x-text="driverError" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="resetDriverForm()" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">{{ __('Reset') }}</button>
                        <button type="submit" :disabled="driverSaving" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-60">
                            <span x-show="!driverSaving" x-text="driverForm.id ? '{{ __('Update Driver') }}' : '{{ __('Add Driver') }}'"></span>
                            <span x-show="driverSaving">{{ __('Saving...') }}</span>
                        </button>
                    </div>
                </form>

                {{-- Driver List --}}
                <h4 class="text-sm font-bold text-gray-900 mb-3">{{ __('Driver List') }}</h4>
                <div class="space-y-3">
                    <template x-for="driver in drivers" :key="driver.id">
                        <div class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:shadow-sm transition-all bg-white">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white font-bold text-xs" x-text="(driver.name || 'D').charAt(0)"></div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900" x-text="driver.name"></p>
                                    <p class="text-[10px] text-gray-400" x-text="driver.email + ' · ' + driver.phone"></p>
                                    <p class="text-[10px] text-gray-400" x-show="driver.vehicle || driver.license" x-text="(driver.vehicle ? driver.vehicle + ' · ' : '') + (driver.license || '')"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="driver.status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'" x-text="driver.status === 'active' ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"></span>
                                <button @click="editDriver(driver)" class="p-1.5 text-[#6E7A25] hover:bg-[#6E7A25]/10 rounded-lg transition-colors" title="{{ __('Edit') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.43-9.121a2.948 2.948 0 00-4.172 0L11.879 5.88a2.948 2.948 0 000 4.172l5.586 5.586a2.948 2.948 0 004.172 0l.586-.586a2.948 2.948 0 000-4.172l-5.586-5.586z"/></svg>
                                </button>
                                <button @click="deleteDriver(driver.id)" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="{{ __('Delete') }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                    <div x-show="drivers.length === 0 && !driverLoading" class="p-8 text-center text-gray-400 text-sm">
                        {{ __('No drivers found. Add your first driver above.') }}
                    </div>
                    <div x-show="driverLoading" class="p-8 text-center text-gray-400 text-sm">
                        {{ __('Loading drivers...') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    function deliveryManager() {
        return {
            driverModalOpen: false,
            driverLoading: false,
            driverSaving: false,
            driverError: '',
            drivers: [],
            driverForm: {
                id: null,
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                location: '',
                vehicle: '',
                license_number: '',
            },
            openDriverManager() {
                this.driverModalOpen = true;
                this.loadDrivers();
            },
            closeDriverModal() {
                this.driverModalOpen = false;
                this.resetDriverForm();
                this.driverError = '';
            },
            resetDriverForm() {
                this.driverForm = { id: null, first_name: '', last_name: '', email: '', phone: '', location: '', vehicle: '', license_number: '' };
            },
            editDriver(driver) {
                this.driverForm = { ...driver, license_number: driver.license || '' };
            },
            async loadDrivers() {
                this.driverLoading = true;
                try {
                    const res = await fetch('{{ route('admin.drivers') }}', {
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.drivers = data.drivers || [];
                    }
                } catch (e) {
                    this.driverError = '{{ __('Failed to load drivers.') }}';
                } finally {
                    this.driverLoading = false;
                }
            },
            async saveDriver() {
                this.driverSaving = true;
                this.driverError = '';
                const isEdit = !!this.driverForm.id;
                const url = isEdit ? '{{ url('admin/drivers') }}/' + this.driverForm.id : '{{ route('admin.drivers.store') }}';
                const method = isEdit ? 'PUT' : 'POST';
                const formData = new FormData();
                for (const [key, value] of Object.entries(this.driverForm)) {
                    if (value !== null && value !== undefined) formData.append(key, value);
                }
                if (isEdit) {
                    formData.append('_method', 'PUT');
                }
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData,
                    });
                    const data = await res.json();
                    if (data.success) {
                        await this.loadDrivers();
                        this.resetDriverForm();
                    } else {
                        this.driverError = data.message || '{{ __('Failed to save driver.') }}';
                    }
                } catch (e) {
                    this.driverError = '{{ __('Network error. Please try again.') }}';
                } finally {
                    this.driverSaving = false;
                }
            },
            async deleteDriver(id) {
                if (!confirm('{{ __('Are you sure you want to delete this driver?') }}')) return;
                try {
                    const res = await fetch('{{ url('admin/drivers') }}/' + id, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: new URLSearchParams({ _method: 'DELETE' })
                    });
                    const data = await res.json();
                    if (data.success) {
                        await this.loadDrivers();
                    } else {
                        this.driverError = data.message || '{{ __('Failed to delete driver.') }}';
                    }
                } catch (e) {
                    this.driverError = '{{ __('Network error. Please try again.') }}';
                }
            },
        };
    }
</script>
@endpush

@endsection
