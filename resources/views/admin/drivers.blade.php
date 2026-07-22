@extends('layouts.admin')

@section('title', __('Drivers') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Driver Management'))

@section('content')
<div x-data="driverManager()" x-init="init()" class="space-y-4">
    @php
        $statusColors = [
            'active' => 'bg-green-50 text-green-700 border-green-200',
            'inactive' => 'bg-gray-50 text-gray-500 border-gray-200',
            'delivered' => 'bg-green-50 text-green-700 border-green-200',
            'out_for_delivery' => 'bg-blue-50 text-blue-700 border-blue-200',
            'picked_up' => 'bg-amber-50 text-amber-700 border-amber-200',
            'assigned' => 'bg-purple-50 text-purple-700 border-purple-200',
            'failed' => 'bg-red-50 text-red-600 border-red-200',
            'pending' => 'bg-gray-50 text-gray-600 border-gray-200',
            'cancelled' => 'bg-red-50 text-red-600 border-red-200',
            'other' => 'bg-gray-50 text-gray-600 border-gray-200',
        ];
        $statusLabels = [
            'active' => __('Active'), 'inactive' => __('Inactive'),
            'delivered' => __('Delivered'), 'out_for_delivery' => __('Out for Delivery'),
            'picked_up' => __('Picked Up'), 'assigned' => __('Assigned'),
            'failed' => __('Failed'), 'pending' => __('Pending'), 'cancelled' => __('Cancelled'),
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
            <p class="text-[10px] text-white/70 mb-1">{{ __('Total Drivers') }}</p>
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
                <p class="text-sm font-bold text-[#6E7A25]">{{ __('+ Add Driver') }}</p>
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
            <input type="text" x-model="search" @input.debounce.300ms="loadDrivers()" placeholder="{{ __('Search drivers...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400 w-20">
        </div>
        <button @click="loadDrivers()" class="px-3 py-1.5 text-xs font-bold text-white bg-[#6E7A25] rounded-lg hover:bg-[#5a6820] transition-all shadow-sm">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('Refresh') }}
        </button>
    </div>

    {{-- Drivers Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        <template x-for="driver in filteredDrivers" :key="driver.id">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-all cursor-pointer group" @click="showDriver(driver.id)">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#173327] to-[#6E7A25] flex items-center justify-center text-white font-bold text-base flex-shrink-0" x-text="(driver.name || 'D').charAt(0).toUpperCase()"></div>
                        <div>
                            <p class="text-sm font-bold text-gray-900" x-text="driver.name"></p>
                            <p class="text-[10px] text-gray-400" x-text="driver.email + ' · ' + driver.phone"></p>
                        </div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border" :class="driver.status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-500 border-gray-200'" x-text="driver.status === 'active' ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"></span>
                </div>
                <div class="space-y-1 text-[10px] text-gray-500 mb-4">
                    <p x-show="driver.location"><svg class="w-3 h-3 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span x-text="driver.location"></span></p>
                    <p x-show="driver.address"><svg class="w-3 h-3 inline mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span x-text="driver.address"></span></p>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                    <span class="text-[10px] text-gray-400">{{ __('Click to view details') }}</span>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-[#6E7A25] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </template>
        <div x-show="filteredDrivers.length === 0 && !loading" class="col-span-full p-12 text-center text-gray-400 text-sm bg-white rounded-2xl border border-gray-100">
            {{ __('No drivers found.') }}
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
                    <h3 class="text-lg font-bold text-gray-900" x-text="form.id ? '{{ __('Edit Driver') }}' : '{{ __('Add Driver') }}'"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg hover:bg-gray-100"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                </div>
                <div class="flex-1 overflow-y-auto p-6">
                    <form class="space-y-4" @submit.prevent="saveDriver">
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
                                <div class="flex items-center justify-between mb-1">
                                    <label class="block text-xs font-bold text-gray-700">{{ __('Password') }}</label>
                                    <button type="button" @click="form.password = Math.random().toString(36).slice(2) + Math.random().toString(36).slice(2,4).toUpperCase()" class="text-[10px] font-bold text-[#6E7A25] hover:underline">{{ __('Generate') }}</button>
                                </div>
                                <input type="text" x-model="form.password" placeholder="{{ __('Leave empty to auto-generate') }}" minlength="6" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Location') }}</label>
                                <input type="text" x-model="form.location" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">{{ __('Address') }}</label>
                                <input type="text" x-model="form.address" class="w-full px-3 py-2 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none">
                            </div>
                            <div class="col-span-2 flex items-center gap-2 p-3 rounded-lg bg-gray-50 border border-gray-100">
                                <input type="checkbox" id="driverActive" x-model="form.is_active" class="w-4 h-4 rounded border-gray-300 text-[#6E7A25] focus:ring-[#6E7A25]">
                                <label for="driverActive" class="text-xs font-bold text-gray-700">{{ __('Active driver account') }}</label>
                            </div>
                        </div>
                        <div x-show="error" x-text="error" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                        <div x-show="success" x-text="success" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>
                        <div x-show="credentials" x-cloak class="bg-[#F6F3E9] border border-[#d1cb9f] rounded-xl p-4">
                            <p class="text-xs font-bold text-[#6E7A25] mb-2">{{ __('Driver Login Credentials') }}</p>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-[#e8e4d0]">
                                    <span class="text-[10px] text-gray-500">{{ __('Email') }}</span>
                                    <span class="text-xs font-mono font-semibold" x-text="credentials?.email"></span>
                                </div>
                                <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2 border border-[#e8e4d0]">
                                    <span class="text-[10px] text-gray-500">{{ __('Password') }}</span>
                                    <span class="text-xs font-mono font-semibold" x-text="credentials?.password"></span>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-2">{{ __('Credentials have been emailed to the driver.') }}</p>
                        </div>
                        <div class="flex items-center justify-end gap-2 pt-2">
                            <button type="button" @click="resetForm()" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">{{ __('Reset') }}</button>
                            <button type="submit" :disabled="saving" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md disabled:opacity-60">
                                <span x-show="!saving" x-text="form.id ? '{{ __('Update Driver') }}' : '{{ __('Add Driver') }}'"></span>
                                <span x-show="saving">{{ __('Saving...') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Driver Detail Slide-Out --}}
    <div x-show="detailOpen" x-cloak class="fixed inset-0 z-50 flex justify-end">
        <div x-show="detailOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" @click="detailOpen = false"></div>
        <div x-show="detailOpen" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full rtl:-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full rtl:-translate-x-full" class="absolute inset-y-0 right-0 rtl:right-auto rtl:left-0 w-full sm:w-[34rem] bg-white shadow-2xl overflow-y-auto">
            <div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] p-6 text-white sticky top-0 z-10">
                <div class="flex items-center justify-between mb-4">
                    <button @click="detailOpen = false" class="text-white/70 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                    <button @click="editCurrentDriver()" class="px-3 py-1.5 text-xs font-bold bg-white/10 hover:bg-white/20 rounded-lg border border-white/20">{{ __('Edit') }}</button>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-white/15 flex items-center justify-center text-2xl font-bold border border-white/20" x-text="(currentDriver?.name || 'D').charAt(0).toUpperCase()"></div>
                    <div>
                        <h3 class="text-xl font-bold" x-text="currentDriver?.name"></h3>
                        <p class="text-sm text-white/70" x-text="currentDriver?.email + ' · ' + currentDriver?.phone"></p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border mt-2" :class="currentDriver?.status === 'active' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-50 text-gray-600 border-gray-200'" x-text="currentDriver?.status === 'active' ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"></span>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-6" x-show="!detailLoading">
                {{-- KPIs --}}
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Performance KPIs') }}</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center shadow-sm">
                            <p class="text-lg font-bold text-gray-900" x-text="kpi.total">0</p>
                            <p class="text-[10px] text-gray-400">{{ __('Total Deliveries') }}</p>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center shadow-sm">
                            <p class="text-lg font-bold text-green-600" x-text="kpi.completed">0</p>
                            <p class="text-[10px] text-gray-400">{{ __('Completed') }}</p>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center shadow-sm">
                            <p class="text-lg font-bold text-[#6E7A25]" x-text="kpi.completion_rate + '%'">0%</p>
                            <p class="text-[10px] text-gray-400">{{ __('Completion Rate') }}</p>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center shadow-sm">
                            <p class="text-lg font-bold text-blue-600" x-text="kpi.in_progress">0</p>
                            <p class="text-[10px] text-gray-400">{{ __('In Progress') }}</p>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center shadow-sm">
                            <p class="text-lg font-bold text-amber-600" x-text="kpi.pending">0</p>
                            <p class="text-[10px] text-gray-400">{{ __('Pending') }}</p>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 p-3 text-center shadow-sm">
                            <p class="text-lg font-bold text-red-600" x-text="kpi.failed">0</p>
                            <p class="text-[10px] text-gray-400">{{ __('Failed') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Status Breakdown --}}
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Status Breakdown') }}</h4>
                    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                        <div class="space-y-3">
                            <template x-for="(count, status) in statusCounts" :key="status">
                                <div x-show="count > 0" class="flex items-center gap-3">
                                    <span class="text-xs font-medium text-gray-600 w-28" x-text="statusLabel(status)"></span>
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full" :class="statusClass(status) ? statusClass(status).split(' ')[0].replace('bg-','bg-') + '-500' : 'bg-gray-400'" :style="`width: ${kpi.total > 0 ? (count / kpi.total * 100) + '%' : '0%'}`"></div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 w-8 text-right" x-text="count"></span>
                                </div>
                            </template>
                            <div x-show="kpi.total === 0" class="text-xs text-gray-400 text-center py-4">{{ __('No delivery history for this driver.') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Delivery History --}}
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Delivery History') }}</h4>
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <template x-for="delivery in deliveries" :key="delivery.id">
                            <div class="flex items-center justify-between p-4 border-b border-gray-50 last:border-0 hover:bg-gray-50/30 transition-colors">
                                <div>
                                    <p class="text-xs font-bold text-gray-900" x-text="delivery.order"></p>
                                    <p class="text-[10px] text-gray-400" x-text="delivery.customer + (delivery.address ? ' · ' + delivery.address : '')"></p>
                                    <p class="text-[10px] text-gray-400" x-text="delivery.date"></p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border whitespace-nowrap" :class="statusClass(delivery.status)" x-text="statusLabel(delivery.status)"></span>
                            </div>
                        </template>
                        <div x-show="deliveries.length === 0" class="p-8 text-center text-gray-400 text-sm">{{ __('No deliveries found.') }}</div>
                    </div>
                </div>

                {{-- Driver Info --}}
                <div>
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Driver Information') }}</h4>
                    <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                        <div class="flex justify-between text-xs"><span class="text-gray-500">{{ __('Location') }}</span><span class="font-semibold text-gray-900" x-text="currentDriver?.location || '—'"></span></div>
                        <div class="flex justify-between text-xs"><span class="text-gray-500">{{ __('Address') }}</span><span class="font-semibold text-gray-900" x-text="currentDriver?.address || '—'"></span></div>
                        <div class="flex justify-between text-xs"><span class="text-gray-500">{{ __('Joined') }}</span><span class="font-semibold text-gray-900" x-text="currentDriver?.created_at || '—'"></span></div>
                    </div>
                </div>
            </div>

            <div x-show="detailLoading" class="p-12 text-center">
                <div class="animate-pulse flex justify-center space-x-2">
                    <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce"></div>
                    <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                    <div class="w-3 h-3 bg-[#6E7A25] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function driverManager() {
    return {
        drivers: @json($drivers),
        search: '',
        loading: false,
        modalOpen: false,
        detailOpen: false,
        detailLoading: false,
        saving: false,
        error: '',
        success: '',
        credentials: null,
        form: { id: null, first_name: '', last_name: '', email: '', phone: '', password: '', location: '', address: '', is_active: true },
        currentDriver: null,
        kpi: { total: 0, completed: 0, completion_rate: 0, failed: 0, in_progress: 0, pending: 0 },
        statusCounts: { delivered: 0, out_for_delivery: 0, picked_up: 0, assigned: 0, failed: 0, pending: 0, cancelled: 0, other: 0 },
        deliveries: [],

        get filteredDrivers() {
            const term = this.search.toLowerCase().trim();
            if (!term) return this.drivers;
            return this.drivers.filter(d =>
                (d.name || '').toLowerCase().includes(term) ||
                (d.email || '').toLowerCase().includes(term) ||
                (d.phone || '').toLowerCase().includes(term) ||
                (d.location || '').toLowerCase().includes(term)
            );
        },

        init() { this.loadDrivers(); },

        statusClass(s) {
            const m = @json($statusColors);
            return m[s] || 'bg-gray-50 text-gray-600 border-gray-200';
        },
        statusLabel(s) {
            const m = @json($statusLabels);
            return m[s] || s;
        },

        async loadDrivers() {
            this.loading = true;
            try {
                const res = await fetch('{{ route('admin.drivers') }}', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                const data = await res.json();
                if (data.success) this.drivers = data.drivers || [];
            } catch (e) {}
            this.loading = false;
        },

        openCreate() {
            this.resetForm();
            this.modalOpen = true;
        },

        editDriver(driver) {
            this.form = { ...driver, password: '', is_active: driver.status === 'active' };
            this.credentials = null;
            this.modalOpen = true;
        },

        editCurrentDriver() {
            if (!this.currentDriver) return;
            this.detailOpen = false;
            this.editDriver(this.currentDriver);
        },

        resetForm() {
            this.form = { id: null, first_name: '', last_name: '', email: '', phone: '', password: '', location: '', address: '', is_active: true };
            this.error = ''; this.success = ''; this.credentials = null;
        },

        closeModal() {
            this.modalOpen = false;
            this.resetForm();
        },

        async saveDriver() {
            this.saving = true; this.error = ''; this.success = ''; this.credentials = null;
            const isEdit = !!this.form.id;
            const url = isEdit ? '{{ url('admin/drivers') }}/' + this.form.id : '{{ route('admin.drivers.store') }}';
            const formData = new FormData();
            const fields = isEdit
                ? ['id', 'first_name', 'last_name', 'phone', 'location', 'address', 'is_active']
                : ['first_name', 'last_name', 'email', 'phone', 'password', 'location', 'address'];
            fields.forEach(key => { const v = this.form[key]; if (v !== null && v !== undefined) formData.append(key, v); });
            if (isEdit) formData.append('_method', 'PUT');
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const data = await res.json();
                if (data.success) {
                    await this.loadDrivers();
                    this.resetForm();
                    this.success = data.message || '{{ __('Driver saved successfully.') }}';
                    if (data.credentials) this.credentials = data.credentials;
                } else {
                    this.error = data.message || '{{ __('Failed to save driver.') }}';
                }
            } catch (e) {
                this.error = '{{ __('Network error. Please try again.') }}';
            } finally {
                this.saving = false;
            }
        },

        async showDriver(id) {
            this.detailLoading = true;
            this.detailOpen = true;
            try {
                const res = await fetch('{{ url('admin/drivers') }}/' + id, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                const data = await res.json();
                if (data.success) {
                    this.currentDriver = data.driver;
                    this.kpi = data.kpi;
                    this.statusCounts = data.status_counts;
                    this.deliveries = data.deliveries;
                }
            } catch (e) {}
            this.detailLoading = false;
        },

        async toggleStatus(driver) {
            if (!confirm(`{{ __('Are you sure you want to change status for') }} ${driver.name}?`)) return;
            try {
                const formData = new FormData();
                formData.append('is_active', driver.status !== 'active');
                formData.append('_method', 'PUT');
                const res = await fetch('{{ url('admin/drivers') }}/' + driver.id, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: formData });
                const data = await res.json();
                if (data.success) await this.loadDrivers();
            } catch (e) {}
        }
    };
}
</script>
@endpush
