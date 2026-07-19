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
    <div class="mb-4 bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm" x-show="!flashDismissed" x-transition x-data="{ flashDismissed: false }" x-init="setTimeout(() => flashDismissed = true, 4000)">
        {{ session('status') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm" x-show="!flashDismissed" x-transition x-data="{ flashDismissed: false }" x-init="setTimeout(() => flashDismissed = true, 4000)">
        {{ session('error') }}
    </div>
    @endif

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20 animate__animated animate__fadeInUp" style="animation-delay: 0.1s">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/5 rounded-full -ml-8 -mb-8"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-white/60 font-medium">{{ __('Total Subscriptions') }}</p>
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold tracking-tight" x-text="stats.total">{{ $stats['total'] }}</p>
                <p class="text-xs text-white/50 mt-1">{{ __('All time') }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-green-500/20 animate__animated animate__fadeInUp" style="animation-delay: 0.2s">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-white/60 font-medium">{{ __('Active') }}</p>
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold tracking-tight" x-text="stats.active">{{ $stats['active'] }}</p>
                <p class="text-xs text-white/50 mt-1" x-text="(stats.paused || 0) + ' {{ __('paused') }}'">{{ $stats['paused'] ?? 0 }} paused</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#033133] to-[#025C5F] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#033133]/20 animate__animated animate__fadeInUp" style="animation-delay: 0.3s">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-white/60 font-medium">{{ __('Total Revenue') }}</p>
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold tracking-tight" x-text="'SAR ' + Number(stats.total_revenue || 0).toLocaleString(undefined, {minimumFractionDigits: 2})">SAR {{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                <p class="text-xs text-white/50 mt-1" x-text="'MRR: SAR ' + Number(stats.mrr || 0).toLocaleString()">MRR: SAR {{ number_format($stats['mrr']) }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-br from-[#6E7A25] to-[#949B50] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20 animate__animated animate__fadeInUp" style="animation-delay: 0.4s">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs text-white/60 font-medium">{{ __('Pending') }}</p>
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-bold tracking-tight" x-text="stats.pending">{{ $stats['pending'] }}</p>
                <p class="text-xs text-white/50 mt-1" x-text="(stats.paid || 0) + ' {{ __('paid') }}'">{{ $stats['paid'] }} paid</p>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="bg-white rounded-xl border border-gray-100 p-3 shadow-sm flex flex-wrap items-center gap-2 mb-6 animate__animated animate__fadeInUp" style="animation-delay: 0.45s">
        <div class="flex items-center bg-gray-50 rounded-lg px-2.5 py-1.5 border border-gray-100 flex-1 min-w-[160px]">
            <svg class="w-3.5 h-3.5 text-gray-400 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model="search" @input.debounce.300ms="loadSubscriptions()" type="text" placeholder="{{ __('Search customer or plan...') }}" class="bg-transparent text-xs outline-none flex-1 text-gray-600 placeholder-gray-400 w-20">
        </div>
        <select x-model="statusFilter" @change="loadSubscriptions()" class="text-xs border border-gray-100 rounded-lg px-2.5 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer">
            <option value="">{{ __('All Statuses') }}</option>
            <option value="active">{{ __('Active') }}</option>
            <option value="paused">{{ __('Paused') }}</option>
            <option value="pending_payment">{{ __('Pending Payment') }}</option>
            <option value="cancelled">{{ __('Cancelled') }}</option>
            <option value="expired">{{ __('Expired') }}</option>
        </select>
        <select x-model="paymentFilter" @change="loadSubscriptions()" class="text-xs border border-gray-100 rounded-lg px-2.5 py-1.5 bg-gray-50 text-gray-600 outline-none cursor-pointer">
            <option value="">{{ __('All Payments') }}</option>
            <option value="paid">{{ __('Paid') }}</option>
            <option value="pending">{{ __('Pending') }}</option>
            <option value="unpaid">{{ __('Unpaid') }}</option>
            <option value="failed">{{ __('Failed') }}</option>
            <option value="refunded">{{ __('Refunded') }}</option>
        </select>
        <button @click="exportExcel()" class="px-3 py-1.5 text-xs font-bold text-[#173327] bg-[#6E7A25]/10 rounded-lg hover:bg-[#6E7A25]/20 transition-all whitespace-nowrap flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            {{ __('Export') }}
        </button>
        <button @click="openCreate()" class="px-3 py-1.5 text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg shadow-sm hover:shadow-md transition-all whitespace-nowrap flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('New Subscription') }}
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6 animate__animated animate__fadeInUp" style="animation-delay: 0.5s">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[10px] text-gray-400 bg-gray-50/50 border-b border-gray-100">
                        <th class="px-4 py-3 font-medium">{{ __('Customer') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Plan') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Amount') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Status') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Payment') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Period') }}</th>
                        <th class="px-4 py-3 font-medium">{{ __('Created') }}</th>
                        <th class="px-4 py-3 font-medium text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr><td colspan="8" class="px-4 py-8"><div class="space-y-2 animate-pulse"><template x-for="i in 4" :key="i"><div class="h-10 bg-gray-50 rounded"></div></template></div></td></tr>
                    </template>
                    <template x-if="!loading && subscriptions.length === 0">
                        <tr><td colspan="8" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mb-3">
                                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                </div>
                                <p class="text-xs font-medium text-gray-400">{{ __('No subscriptions found') }}</p>
                                <p class="text-[10px] text-gray-300 mt-0.5">{{ __('Subscriptions will appear here once created') }}</p>
                            </div>
                        </td></tr>
                    </template>
                    <template x-for="sub in subscriptions" :key="sub.id">
                        <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#6E7A25] to-[#173327] flex items-center justify-center text-white font-bold text-xs flex-shrink-0 shadow-sm" x-text="sub.customer?.charAt(0)?.toUpperCase()"></div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-900" x-text="sub.customer"></p>
                                        <p class="text-[10px] text-gray-400" x-text="sub.customer_email"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold border whitespace-nowrap"
                                    :style="`background: ${sub.plan_color || '#6E7A25'}15; color: ${sub.plan_color || '#6E7A25'}; border-color: ${sub.plan_color || '#6E7A25'}30`">
                                    <span class="w-1.5 h-1.5 rounded-full" :style="`background: ${sub.plan_color || '#6E7A25'}`"></span>
                                    <span x-text="sub.plan_name"></span>
                                </span>
                            </td>
                            <td class="px-4 py-3"><span class="text-xs font-bold text-[#173327]" x-text="'SAR ' + Number(sub.amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"></span></td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border whitespace-nowrap" :class="statusClass(sub.status)" x-text="formatStatus(sub.status)"></span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border whitespace-nowrap" :class="paymentClass(sub.payment_status)" x-text="formatStatus(sub.payment_status)"></span>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-xs text-gray-500" x-text="sub.start_formatted || '—'"></p>
                                <p class="text-[10px] text-gray-400" x-text="sub.end_formatted || '—'"></p>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-400" x-text="sub.created_formatted || '—'"></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button @click="viewSubscription(sub)" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-[#6E7A25] hover:bg-[#6E7A25]/10 transition-all" title="{{ __('View') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                    <button @click="editSubscription(sub)" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="{{ __('Edit') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button @click="cancelSubscription(sub)" x-show="sub.status !== 'cancelled' && sub.status !== 'expired'" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 transition-all" title="{{ __('Cancel') }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-50 flex items-center justify-between">
            <p class="text-[10px] text-gray-400" x-text="`{{ __('Showing') }} ${subscriptions.length} {{ __('subscriptions') }}`"></p>
        </div>
    </div>

    {{-- Create/Edit Sidebar Panel --}}
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex justify-end" style="display: none">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closeModal()"></div>
        <div x-show="modalOpen" x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="relative w-full max-w-md bg-white shadow-2xl h-full overflow-y-auto">
            <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] p-6 text-white sticky top-0 z-10">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-bold" x-text="form.id ? '{{ __('Edit Subscription') }}' : '{{ __('New Subscription') }}'"></h3>
                    <button @click="closeModal()" class="text-white/60 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-xs text-white/60" x-text="form.id ? '#{{ __('Subscription') }} ' + form.id : '{{ __('Create a new subscription') }}'"></p>
            </div>
            <form class="p-6 space-y-4" @submit.prevent="saveSubscription">
                <input type="hidden" x-model="form.id">
                <div x-show="!form.id">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Customer') }} <span class="text-red-500">*</span></label>
                    <select x-model="form.user_id" required class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-gray-50">
                        <option value="">{{ __('Select customer') }}</option>
                        @foreach($users as $user)
                        <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['email'] }})</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="!form.id">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Plan') }} <span class="text-red-500">*</span></label>
                    <select x-model="form.plan_id" required @change="updateAmount" class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-gray-50">
                        <option value="">{{ __('Select plan') }}</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan['id'] }}" data-price="{{ $plan['price'] }}">{{ $plan['name'] }} - SAR {{ number_format($plan['price']) }} / {{ $plan['duration_days'] }} days</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="form.id">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Status') }} <span class="text-red-500">*</span></label>
                    <select x-model="form.status" required class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-gray-50">
                        <option value="active">{{ __('Active') }}</option>
                        <option value="paused">{{ __('Paused') }}</option>
                        <option value="pending_payment">{{ __('Pending Payment') }}</option>
                        <option value="cancelled">{{ __('Cancelled') }}</option>
                        <option value="expired">{{ __('Expired') }}</option>
                    </select>
                </div>
                <div x-show="form.id">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Payment Status') }} <span class="text-red-500">*</span></label>
                    <select x-model="form.payment_status" required class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-gray-50">
                        <option value="paid">{{ __('Paid') }}</option>
                        <option value="pending">{{ __('Pending') }}</option>
                        <option value="unpaid">{{ __('Unpaid') }}</option>
                        <option value="failed">{{ __('Failed') }}</option>
                        <option value="refunded">{{ __('Refunded') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">{{ __('Notes') }}</label>
                    <textarea x-model="form.notes" rows="3" class="w-full px-3 py-2.5 rounded-lg border border-gray-200 text-sm focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/20 outline-none bg-gray-50" placeholder="{{ __('Add notes...') }}"></textarea>
                </div>
                <div x-show="formError" x-text="formError" class="text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2"></div>
                <div x-show="formSuccess" x-text="formSuccess" class="text-xs text-green-700 bg-green-50 rounded-lg px-3 py-2"></div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" @click="closeModal()" class="px-4 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">{{ __('Cancel') }}</button>
                    <button type="submit" :disabled="saving" class="px-4 py-2 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-xs font-bold shadow-sm hover:shadow-md transition-all disabled:opacity-60">
                        <span x-show="!saving" x-text="form.id ? '{{ __('Update') }}' : '{{ __('Create') }}'"></span>
                        <span x-show="saving">{{ __('Saving...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- View Detail Slide-Out Panel --}}
    <div x-show="viewOpen" x-cloak class="fixed inset-0 z-50 flex justify-end" style="display: none">
        <div x-show="viewOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="viewOpen = false"></div>
        <div x-show="viewOpen" x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="relative w-full max-w-lg bg-white shadow-2xl h-full overflow-y-auto">
            <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] p-6 text-white sticky top-0 z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold">{{ __('Subscription Details') }}</h3>
                    <button @click="viewOpen = false" class="text-white/60 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white font-bold text-xl flex-shrink-0 shadow-lg" x-text="viewData?.customer?.charAt(0)?.toUpperCase()"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-bold truncate" x-text="viewData?.customer"></p>
                        <p class="text-xs text-white/60 truncate" x-text="viewData?.customer_email"></p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border border-white/20 bg-white/10" :class="statusClass(viewData?.status)">
                                <span x-text="formatStatus(viewData?.status)"></span>
                            </span>
                            <span class="text-[10px] text-white/50" x-text="'#' + (viewData?.id || '')"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-5 space-y-5">
                <div x-show="viewLoading" class="flex items-center justify-center py-8">
                    <svg class="w-8 h-8 text-gray-200 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>

                <div x-show="!viewLoading" class="grid grid-cols-2 gap-3">
                    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-3 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] text-white/60 font-medium">{{ __('Amount') }}</p>
                            <p class="text-lg font-bold mt-0.5" x-text="'SAR ' + Number(viewData?.amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2})"></p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-[#033133] to-[#025C5F] rounded-xl p-3 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] text-white/60 font-medium">{{ __('Payment') }}</p>
                            <p class="text-sm font-bold mt-0.5" x-text="formatStatus(viewData?.payment_status)"></p>
                        </div>
                    </div>
                </div>

                <div x-show="!viewLoading">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Details') }}</h4>
                    <div class="bg-gray-50/50 rounded-xl p-4 space-y-3">
                        <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Plan') }}</span>
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold border" :style="`background: ${viewData?.plan_color || '#6E7A25'}15; color: ${viewData?.plan_color || '#6E7A25'}; border-color: ${viewData?.plan_color || '#6E7A25'}30`">
                                <span class="w-1.5 h-1.5 rounded-full" :style="`background: ${viewData?.plan_color || '#6E7A25'}`"></span>
                                <span x-text="viewData?.plan_name"></span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Customer') }}</span><span class="text-xs font-semibold text-gray-900" x-text="viewData?.customer"></span></div>
                        <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Email') }}</span><span class="text-xs font-semibold text-gray-900" x-text="viewData?.customer_email || '—'"></span></div>
                        <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Phone') }}</span><span class="text-xs font-semibold text-gray-900" x-text="viewData?.customer_phone || '—'"></span></div>
                        <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Start Date') }}</span><span class="text-xs font-semibold text-gray-900" x-text="viewData?.start_formatted || '—'"></span></div>
                        <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('End Date') }}</span><span class="text-xs font-semibold text-gray-900" x-text="viewData?.end_formatted || '—'"></span></div>
                        <div class="flex justify-between items-center"><span class="text-xs text-gray-400">{{ __('Created') }}</span><span class="text-xs font-semibold text-gray-900" x-text="viewData?.created_formatted || '—'"></span></div>
                    </div>
                </div>

                <div x-show="!viewLoading && viewData?.notes">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Notes') }}</h4>
                    <div class="bg-gray-50/50 rounded-xl p-4">
                        <p class="text-xs text-gray-600" x-text="viewData?.notes"></p>
                    </div>
                </div>

                <div x-show="!viewLoading" class="flex gap-2">
                    <button @click="editSubscription(viewData); viewOpen = false" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        {{ __('Edit') }}
                    </button>
                    <button @click="cancelSubscription(viewData); viewOpen = false" x-show="viewData?.status !== 'cancelled' && viewData?.status !== 'expired'" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all">
                        <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel Confirmation Modal --}}
    <div x-show="cancelOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none">
        <div class="absolute inset-0 bg-black/40" @click="cancelOpen = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
            <div class="flex flex-col items-center text-center mb-4">
                <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Cancel Subscription') }}</h3>
                <p class="text-xs text-gray-400 mt-1" x-text="`{{ __('Are you sure you want to cancel subscription') }} #${cancelTarget?.id}?`"></p>
            </div>
            <div class="flex gap-2">
                <button @click="cancelOpen = false" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">{{ __('Dismiss') }}</button>
                <button @click="confirmCancel" class="flex-1 px-3 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-red-500 to-red-600 text-white hover:shadow-md transition-all" x-text="cancelling ? '{{ __('Cancelling...') }}' : '{{ __('Cancel Subscription') }}'"></button>
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
        viewOpen: false,
        viewData: null,
        viewLoading: false,
        cancelOpen: false,
        cancelTarget: null,
        cancelling: false,

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

        exportExcel() {
            const params = new URLSearchParams();
            if (this.statusFilter) params.set('status', this.statusFilter);
            if (this.paymentFilter) params.set('payment_status', this.paymentFilter);
            if (this.search) params.set('search', this.search);
            params.set('export', 'excel');
            window.location.href = `{{ route('admin.subscriptions') }}?${params.toString()}`;
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

        async viewSubscription(sub) {
            this.viewOpen = true;
            this.viewLoading = true;
            this.viewData = sub;
            try {
                const res = await fetch(`{{ url('admin/subscriptions') }}/${sub.id}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                if (data.success && data.subscription) {
                    this.viewData = { ...sub, ...data.subscription };
                }
            } catch (e) {
                console.error('Failed to fetch subscription details', e);
            } finally {
                this.viewLoading = false;
            }
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
                    setTimeout(() => { this.closeModal(); }, 1500);
                } else {
                    this.formError = data.message || data.error || '{{ __('Failed to save subscription.') }}';
                }
            } catch (e) {
                this.formError = '{{ __('Network error. Please try again.') }}';
            } finally {
                this.saving = false;
            }
        },

        cancelSubscription(sub) {
            this.cancelTarget = sub;
            this.cancelOpen = true;
        },

        async confirmCancel() {
            if (!this.cancelTarget) return;
            this.cancelling = true;
            try {
                const res = await fetch(`{{ url('admin/subscriptions') }}/${this.cancelTarget.id}/cancel`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                });
                const data = await res.json();
                if (data.success) {
                    this.cancelOpen = false;
                    await this.loadSubscriptions();
                } else {
                    alert(data.message || '{{ __('Failed to cancel subscription.') }}');
                }
            } catch (e) {
                alert('{{ __('Network error. Please try again.') }}');
            } finally {
                this.cancelling = false;
            }
        },
    };
}
</script>
@endpush
