@extends('layouts.user')

@section('title', 'Subscriptions - Nutrio Meals')
@section('page_title', 'My Subscriptions')

@section('content')

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

{{-- Inline payment error toast --}}
<div id="payment-error-toast" class="hidden mb-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm flex items-start gap-3">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <div class="flex-1">
        <p class="font-semibold">{{ __('Payment Error') }}</p>
        <p id="payment-error-message" class="text-sm"></p>
    </div>
    <button type="button" onclick="document.getElementById('payment-error-toast').classList.add('hidden')" class="text-red-400 hover:text-red-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

{{-- Active Plan Banner --}}
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-2xl p-6 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-medium text-white/50">
                @if($activePlan['status'] === 'paused')
                Paused Subscription
                @elseif($activePlan['payment_status'] === 'paid')
                Active Subscription
                @elseif($activePlan['payment_status'] === 'unpaid' || $activePlan['payment_status'] === 'pending')
                Pending Payment
                @else
                Subscription
                @endif
            </span>
            <h2 class="text-2xl font-bold mt-1">{{ $activePlan['name'] }}</h2>
            <div class="flex items-center gap-4 mt-3 text-xs text-white/60">
                @if($activePlan['price'] > 0)
                <span>SAR {{ $activePlan['price'] }} / {{ $activePlan['duration'] }}</span>
                <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                @endif
                <span>{{ $activePlan['calories'] }} kcal</span>
                @if($activePlan['status'] === 'active')
                <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                <span>Renews {{ $activePlan['renewal'] }}</span>
                @endif
            </div>
            {{-- Payment status badge --}}
            <div class="mt-2 flex items-center gap-2">
                @if($activePlan['payment_status'] === 'paid')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-400/20 text-green-300">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Paid
                </span>
                @elseif($activePlan['payment_status'] === 'unpaid' || $activePlan['payment_status'] === 'pending')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ ucfirst($activePlan['payment_status']) }}
                </span>
                @endif
                @if($activePlan['status'] === 'paused')
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-400/20 text-amber-300">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Paused
                </span>
                @endif
            </div>
        </div>
        @if(!empty($activePlan['id']))
        <div class="text-right flex flex-col items-end gap-2">
            @if($activePlan['payment_status'] === 'paid')
            <div class="text-3xl font-bold">{{ $activePlan['mealsRemaining'] }}<span class="text-sm text-white/50">/{{ $activePlan['mealsTotal'] }}</span></div>
            <div class="text-xs text-white/50">{{ __('Meals remaining') }}</div>
            @if(!empty($activePlan['receipt']))
            <button type="button" onclick="showReceipt({{ json_encode($activePlan) }})"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10 text-white hover:bg-white/20 text-xs font-bold transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                {{ __('View Receipt') }}
            </button>
            @endif
            @if($activePlan['status'] === 'active')
            @if($activePlan['remaining_pauses'] > 0)
            <form action="{{ route('user.subscriptions.pause', $activePlan['id']) }}" method="POST" class="swal-confirm-form"
                data-title="{{ __('Pause Subscription?') }}"
                data-text="{{ __('Deliveries will be stopped until you resume. You have') }} {{ $activePlan['remaining_pauses'] }} {{ __('pause(s) remaining.') }}"
                data-confirm-text="{{ __('Pause') }}"
                data-cancel-text="{{ __('Cancel') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-400/20 text-amber-300 hover:bg-amber-400/30 text-xs font-bold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Pause') }}
                </button>
            </form>
            @else
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/5 text-white/30 text-xs font-bold cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('Pause limit reached') }}
            </span>
            @endif
            @elseif($activePlan['status'] === 'paused')
            <form action="{{ route('user.subscriptions.resume', $activePlan['id']) }}" method="POST" class="swal-confirm-form"
                data-title="{{ __('Resume Subscription?') }}"
                data-text="{{ __('Deliveries will start again and your end date will be extended by the paused duration.') }}"
                data-confirm-text="{{ __('Resume') }}"
                data-cancel-text="{{ __('Cancel') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-400/20 text-green-300 hover:bg-green-400/30 text-xs font-bold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Resume') }}
                </button>
            </form>
            @endif
            @elseif($activePlan['payment_status'] === 'unpaid' || $activePlan['payment_status'] === 'pending')
            <div class="text-xs text-white/70 mb-1">{{ __('Complete payment to activate') }}</div>
            <button type="button" onclick="openMoyasarCheckout(this)"
                data-checkout-url="{{ route('user.subscriptions.checkout', $activePlan['id']) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-white text-[#173327] hover:bg-white/90 text-xs font-bold transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="pay-label">{{ __('Pay') }} SAR {{ $activePlan['price'] }}</span>
                <svg class="pay-spinner hidden w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
            @endif
        </div>
        @endif
    </div>
    @if($activePlan['payment_status'] === 'paid' && in_array($activePlan['status'], ['active', 'paused']))
    @php $progressWidth = ($activePlan['mealsTotal'] ?? 0) > 0 ? round($activePlan['mealsRemaining'] / $activePlan['mealsTotal'] * 100) : 0; @endphp
    <div class="mt-4 h-2 bg-white/10 rounded-full overflow-hidden">
        <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $progressWidth }}%"></div>
    </div>
    @if($activePlan['status'] === 'paused')
    <div class="mt-3 flex items-center gap-3 text-[10px] text-white/40">
        <span class="inline-flex items-center gap-1">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('Paused') }} — {{ __('Resume anytime to continue') }}
        </span>
    </div>
    @endif
    <div class="mt-2 flex items-center gap-3 text-[10px] text-white/30">
        <span>{{ __('Pauses used') }}: {{ $activePlan['pause_count'] }}/{{ $activePlan['max_pauses'] }}</span>
        @if($activePlan['total_paused_days'] > 0)
        <span class="w-1 h-1 bg-white/20 rounded-full"></span>
        <span>{{ __('Total paused') }}: {{ $activePlan['total_paused_days'] }}/{{ $activePlan['max_pause_days'] }} {{ __('days') }}</span>
        @endif
    </div>
    @endif
</div>

{{-- Available Plans --}}
<div class="mb-6">
    <h3 class="text-sm font-bold text-gray-900 mb-4">Available <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Plans</span></h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($availablePlans as $plan)
        <div class="bg-white rounded-xl border {{ $plan['current'] ? 'border-[#6E7A25] ring-2 ring-[#6E7A25]/20' : 'border-gray-100' }} p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden">
            @if($plan['current'])
            <span class="absolute top-3 right-3 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#6E7A25]/10 text-[#6E7A25]">Current</span>
            @endif
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" style="background: {{ $plan['color'] }}20">
                <svg class="w-5 h-5" style="color: {{ $plan['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <h4 class="text-sm font-bold text-gray-900">{{ $plan['name'] }}</h4>
            <div class="mt-2 text-2xl font-bold text-gray-900">SAR {{ $plan['price'] }}<span class="text-xs font-normal text-gray-400">/{{ $plan['duration'] ?? '4 weeks' }}</span></div>
            <p class="text-xs text-gray-400 mt-1">{{ $plan['calories'] }} kcal</p>
            <p class="text-[10px] text-gray-400 mt-2">{{ $plan['subscribers'] }} subscribers</p>
            @if($plan['current'])
            <button type="button" class="mt-4 w-full px-3 py-2 text-xs font-bold rounded-lg bg-gray-100 text-gray-400 cursor-default">
                {{ __('Active') }}
            </button>
            @else
            <button type="button" onclick="confirmSwitchPlan(this)"
                data-plan-id="{{ $plan['id'] }}"
                data-plan-name="{{ $plan['name'] }}"
                data-plan-price="{{ $plan['price'] }}"
                data-subscribe-url="{{ route('user.subscriptions.subscribe') }}"
                class="w-full px-3 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-md transition-all mt-4">
                <span class="pay-label">{{ __('Switch Plan') }}</span>
                <svg class="pay-spinner hidden w-3 h-3 animate-spin inline-block align-middle" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
            @endif
        </div>
        @endforeach
    </div>
</div>

{{-- Subscription History --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">Subscription <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">History</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">Plan</th>
                    <th class="px-5 py-3 font-medium">Period</th>
                    <th class="px-5 py-3 font-medium">Amount</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Payment</th>
                    <th class="px-5 py-3 font-medium">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $item)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-semibold text-gray-900">{{ $item['plan'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $item['period'] }}</td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">SAR {{ $item['amount'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                            {{ $item['status'] === 'active' ? 'bg-green-50 text-green-700' : ($item['status'] === 'paused' ? 'bg-amber-50 text-amber-700' : ($item['status'] === 'cancelled' ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-500')) }}">
                            {{ ucfirst(str_replace('_', ' ', $item['status'])) }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item['payment_status'] === 'paid' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ ucfirst($item['payment_status']) }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            @if($item['payment_status'] !== 'paid' && !empty($item['id']))
                            <button type="button" onclick="openMoyasarCheckout(this)"
                                data-checkout-url="{{ route('user.subscriptions.checkout', $item['id']) }}"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-[10px] font-bold hover:shadow-md transition-all">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span class="pay-label">{{ __('Pay') }}</span>
                                <svg class="pay-spinner hidden w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                            @elseif($item['receipt'])
                            <button type="button" onclick="showReceipt({{ json_encode($item) }})"
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-[10px] font-bold hover:bg-green-100 transition-all">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                {{ __('View Receipt') }}
                            </button>
                            @else
                            <span class="text-xs text-gray-400">-</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Payment History --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mt-6">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">Payment <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">History</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">{{ __('Plan') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Amount') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Status') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Provider') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Type') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Date') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Paid At') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentHistory as $pm)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-semibold text-gray-900">
                        {{ $pm['plan_name'] }}
                        @if($pm['is_plan_change'])
                        <span class="ml-1 inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 text-blue-600">Upgrade</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $pm['currency'] }} {{ number_format($pm['amount'], 2) }}</td>
                    <td class="px-5 py-3">
                        @php
                        $pmStatus = $pm['status'];
                        $statusColors = [
                            'paid' => 'bg-green-50 text-green-700',
                            'pending' => 'bg-amber-50 text-amber-700',
                            'failed' => 'bg-red-50 text-red-600',
                            'cancelled' => 'bg-gray-100 text-gray-500',
                            'refunded' => 'bg-purple-50 text-purple-700',
                        ];
                        $statusColor = $statusColors[$pmStatus] ?? 'bg-gray-100 text-gray-500';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $statusColor }}">
                            @if($pmStatus === 'paid')
                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            @elseif($pmStatus === 'pending')
                            <svg class="w-3 h-3 mr-0.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            @elseif($pmStatus === 'failed')
                            <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            @endif
                            {{ ucfirst($pmStatus) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ ucfirst($pm['provider']) }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">
                        @if($pm['is_plan_change'])
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 text-blue-600">Plan Change</span>
                        @else
                        <span class="text-gray-400">{{ __('Subscription') }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $pm['created_at'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $pm['paid_at'] ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-8 text-center text-xs text-gray-400">{{ __('No payment records found.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Moyasar Payment Modal --}}
<div id="moyasar-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="moyasar-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeMoyasarModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full pointer-events-auto transform transition-all overflow-hidden" id="moyasar-panel">
            <div class="h-2 w-full bg-gradient-to-r from-[#173327] via-[#6E7A25] to-[#173327]"></div>
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 id="moyasar-title" class="text-base font-bold text-gray-900">{{ __('Complete Payment') }}</h3>
                    <button type="button" onclick="closeMoyasarModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div id="moyasar-amount-display" class="mb-4 text-center">
                    <p class="text-2xl font-bold text-[#173327]" id="moyasar-amount-text"></p>
                    <p class="text-xs text-gray-400 mt-1" id="moyasar-description-text"></p>
                </div>
                <div class="mysr-form" id="moyasar-form-container"></div>
                <div id="moyasar-loading" class="hidden text-center py-8">
                    <svg class="w-8 h-8 text-[#6E7A25] animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="text-xs text-gray-500 mt-2">{{ __('Loading payment form...') }}</p>
                </div>
                <div id="moyasar-error" class="hidden mt-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm"></div>
                <div class="mt-4 flex items-center justify-center gap-1.5 text-[10px] text-gray-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    {{ __('Secured by Moyasar') }} &middot; {{ __('256-bit SSL encryption') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Receipt Modal --}}
<div id="receipt-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="receipt-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeReceipt()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full pointer-events-auto transform transition-all" id="receipt-panel">
            <div id="receipt-content" class="p-6">
                {{-- Content injected by JS --}}
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between gap-3">
                <button type="button" onclick="downloadReceipt()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-[#173327] text-white text-xs font-bold hover:bg-[#1a4a3a] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    {{ __('Download') }}
                </button>
                <button type="button" onclick="closeReceipt()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition-colors">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Switch Plan Confirmation Modal --}}
<div id="switch-plan-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="switch-plan-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeSwitchPlanModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full pointer-events-auto transform transition-all p-6">
            <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 id="switch-plan-title" class="text-base font-bold text-gray-900 mb-2">{{ __('Switch Plan') }}?</h3>
            <p class="text-sm text-gray-600 mb-6">
                {{ __('You are about to switch to') }} <strong id="switch-plan-name" class="text-gray-900"></strong> {{ __('at') }} SAR <strong id="switch-plan-price" class="text-gray-900"></strong>.
                {{ __('This will create a new subscription and open a payment form to complete your purchase.') }}
            </p>
            <div class="flex items-center gap-3 justify-end">
                <button type="button" onclick="closeSwitchPlanModal()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition-colors">
                    {{ __('Cancel') }}
                </button>
                <button type="button" id="switch-plan-confirm-btn" class="px-4 py-2 rounded-lg bg-[#173327] text-white text-xs font-bold hover:bg-[#1a4a3a] transition-colors">
                    {{ __('Confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.moyasar.com/mpf/0.3.0/moyasar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .mysr-form { min-height: 200px; }
    .mysr-form .mysr-btn {
        background: linear-gradient(to right, #173327, #6E7A25) !important;
        border-radius: 0.5rem !important;
        font-weight: 700 !important;
    }
    .mysr-form .mysr-input {
        border-radius: 0.5rem !important;
        border-color: #e5e7eb !important;
    }
    .mysr-form .mysr-input:focus {
        border-color: #6E7A25 !important;
        box-shadow: 0 0 0 2px rgba(110, 122, 37, 0.15) !important;
    }
</style>
<script>
    document.querySelectorAll('.swal-confirm-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const title = this.getAttribute('data-title') || 'Are you sure?';
            const text = this.getAttribute('data-text') || '';
            const confirmText = this.getAttribute('data-confirm-text') || 'Confirm';
            const cancelText = this.getAttribute('data-cancel-text') || 'Cancel';
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#173327',
                cancelButtonColor: '#d1d5db',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-4 py-2 text-xs font-bold rounded-lg',
                    cancelButton: 'px-4 py-2 text-xs font-bold rounded-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function showPaymentError(message) {
        const toast = document.getElementById('payment-error-toast');
        const text = document.getElementById('payment-error-message');
        if (toast && text) {
            text.textContent = message || 'Unable to start payment. Please try again.';
            toast.classList.remove('hidden');
            toast.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            alert(message || 'Unable to start payment. Please try again.');
        }
    }

    function setPayLoading(button, loading) {
        if (!button) return;
        const label = button.querySelector('.pay-label');
        const spinner = button.querySelector('.pay-spinner');
        button.disabled = loading;
        if (label) label.classList.toggle('hidden', loading);
        if (spinner) spinner.classList.toggle('hidden', !loading);
        button.classList.toggle('opacity-75', loading);
        button.classList.toggle('cursor-not-allowed', loading);
    }

    let moyasarFormInstance = null;

    function openMoyasarModal(checkout) {
        const modal = document.getElementById('moyasar-modal');
        const formContainer = document.getElementById('moyasar-form-container');
        const loadingEl = document.getElementById('moyasar-loading');
        const errorEl = document.getElementById('moyasar-error');
        const amountText = document.getElementById('moyasar-amount-text');
        const descText = document.getElementById('moyasar-description-text');

        if (!modal || !formContainer) return;

        const amountHalalas = checkout.amount || 0;
        const amountSar = (amountHalalas / 100).toFixed(2);
        const currency = checkout.currency || 'SAR';
        const description = checkout.description || 'Subscription Payment';
        const publishableKey = checkout.publishable_api_key;
        const callbackUrl = checkout.callback_url || '';
        const metadata = checkout.metadata || {};
        const supportedNetworks = checkout.supported_networks || ['mada', 'visa', 'mastercard'];
        const methods = checkout.methods || ['creditcard'];
        const localPaymentId = checkout.payment_id;

        amountText.textContent = `${currency} ${amountSar}`;
        descText.textContent = description;

        errorEl.classList.add('hidden');
        errorEl.textContent = '';

        formContainer.innerHTML = '';
        formContainer.style.display = '';
        loadingEl.classList.remove('hidden');

        if (moyasarFormInstance && typeof moyasarFormInstance.destroy === 'function') {
            try { moyasarFormInstance.destroy(); } catch (e) {}
        }
        moyasarFormInstance = null;

        const moyasarCallbackUrl = callbackUrl + (callbackUrl.includes('?') ? '&' : '?') + 'payment_id=' + localPaymentId;

        modal.classList.remove('hidden');

        if (typeof Moyasar === 'undefined') {
            loadingEl.classList.add('hidden');
            errorEl.textContent = 'Payment SDK failed to load. Please refresh the page and try again.';
            errorEl.classList.remove('hidden');
            return;
        }

        try {
            moyasarFormInstance = Moyasar.init({
                element: '#moyasar-form-container',
                amount: amountHalalas,
                currency: currency,
                description: description,
                publishable_api_key: publishableKey,
                callback_url: moyasarCallbackUrl,
                supported_networks: supportedNetworks,
                methods: methods,
                metadata: metadata,
                language: document.documentElement.lang || 'en',
                on_completed: async function(payment) {
                    const status = (payment && payment.status || '').toLowerCase();
                    const moyasarPaymentUuid = payment && payment.id || '';

                    if (status === 'failed' || status === 'voided' || status === 'canceled' || status === 'cancelled') {
                        loadingEl.classList.add('hidden');
                        formContainer.style.display = '';
                        const source = payment && payment.source || {};
                        const errMsg = source.message || source.code || 'Payment was declined. Please try again with a different card.';
                        errorEl.textContent = errMsg;
                        errorEl.classList.remove('hidden');
                        return;
                    }

                    loadingEl.classList.remove('hidden');
                    formContainer.style.display = 'none';
                    errorEl.classList.add('hidden');

                    const loadingText = loadingEl.querySelector('p');

                    if (status === 'paid' || status === 'captured') {
                        if (loadingText) loadingText.textContent = '{{ __("Confirming payment...") }}';

                        if (moyasarPaymentUuid && localPaymentId) {
                            const attachUrl = '{{ route("user.payments.attach-moyasar", ["paymentId" => "__PID__"]) }}'.replace('__PID__', localPaymentId);
                            try {
                                const attachResponse = await fetch(attachUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': getCsrfToken(),
                                    },
                                    credentials: 'same-origin',
                                    body: JSON.stringify({ moyasar_payment_id: moyasarPaymentUuid }),
                                });
                                const attachResult = await attachResponse.json().catch(() => ({}));

                                if (!attachResult.success) {
                                    console.warn('Attach failed, redirecting to success page for fallback:', attachResult);
                                }
                            } catch (err) {
                                console.warn('Attach error, redirecting to success page for fallback:', err);
                            }
                        }

                        const successUrl = '{{ route("payment.success") }}' + '?payment_id=' + localPaymentId + '&id=' + moyasarPaymentUuid;
                        window.location.href = successUrl;
                    } else {
                        if (loadingText) loadingText.textContent = '{{ __("Processing payment...") }}';
                        const successUrl = '{{ route("payment.success") }}' + '?payment_id=' + localPaymentId + (moyasarPaymentUuid ? '&id=' + moyasarPaymentUuid : '');
                        window.location.href = successUrl;
                    }
                },
                on_redirect: function(url) {
                    loadingEl.classList.remove('hidden');
                    formContainer.style.display = 'none';
                    const loadingText = loadingEl.querySelector('p');
                    if (loadingText) loadingText.textContent = '{{ __("Redirecting to your bank for verification...") }}';
                },
                on_failure: function(error) {
                    loadingEl.classList.add('hidden');
                    formContainer.style.display = '';
                    errorEl.textContent = (error && error.message) || 'Payment form error. Please try again.';
                    errorEl.classList.remove('hidden');
                },
            });
            loadingEl.classList.add('hidden');
        } catch (err) {
            loadingEl.classList.add('hidden');
            errorEl.textContent = 'Failed to load payment form. Please try again.';
            errorEl.classList.remove('hidden');
            console.error('Moyasar init error:', err);
        }
    }

    function closeMoyasarModal() {
        const modal = document.getElementById('moyasar-modal');
        const formContainer = document.getElementById('moyasar-form-container');
        const loadingEl = document.getElementById('moyasar-loading');
        const errorEl = document.getElementById('moyasar-error');

        if (modal) modal.classList.add('hidden');
        if (formContainer) {
            formContainer.innerHTML = '';
            formContainer.style.display = '';
        }
        if (loadingEl) loadingEl.classList.add('hidden');
        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
        }
        moyasarFormInstance = null;
    }

    async function openMoyasarCheckout(button) {
        const url = button?.getAttribute('data-checkout-url');
        if (!url) {
            showPaymentError('Invalid payment link.');
            return;
        }

        setPayLoading(button, true);
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                credentials: 'same-origin',
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok || !result.success || !result.checkout) {
                showPaymentError(result.message || 'Unable to start payment. Please try again.');
                return;
            }

            openMoyasarModal(result.checkout);
        } catch (err) {
            showPaymentError('Network error. Please try again.');
        } finally {
            setPayLoading(button, false);
        }
    }

    let selectedSwitchPlanButton = null;
    let currentReceipt = null;

    function confirmSwitchPlan(button) {
        selectedSwitchPlanButton = button;
        const name = button?.getAttribute('data-plan-name') || 'this plan';
        const price = button?.getAttribute('data-plan-price') || '0';

        document.getElementById('switch-plan-name').textContent = name;
        document.getElementById('switch-plan-price').textContent = price;
        document.getElementById('switch-plan-modal').classList.remove('hidden');
    }

    function closeSwitchPlanModal() {
        selectedSwitchPlanButton = null;
        document.getElementById('switch-plan-modal').classList.add('hidden');
    }

    document.getElementById('switch-plan-confirm-btn')?.addEventListener('click', () => {
        if (selectedSwitchPlanButton) {
            openMoyasarSubscribe(selectedSwitchPlanButton);
        }
        closeSwitchPlanModal();
    });

    async function openMoyasarSubscribe(button) {
        const url = button?.getAttribute('data-subscribe-url');
        const planId = button?.getAttribute('data-plan-id');

        if (!url || !planId) {
            showPaymentError('Invalid subscription link.');
            return;
        }

        setPayLoading(button, true);
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                credentials: 'same-origin',
                body: JSON.stringify({ plan_id: parseInt(planId, 10), json: '1' }),
            });

            const result = await response.json().catch(() => ({}));

            if (!response.ok || !result.success) {
                showPaymentError(result.message || 'Unable to start subscription. Please try again.');
                return;
            }

            if (result.requires_payment === false && !result.checkout) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ title: 'Success', text: result.message || 'Plan change scheduled.', icon: 'success', confirmButtonText: 'OK' })
                        .then(() => window.location.reload());
                } else {
                    alert(result.message || 'Plan change scheduled.');
                    window.location.reload();
                }
                return;
            }

            if (!result.checkout) {
                showPaymentError(result.message || 'Unable to start payment. Please try again.');
                return;
            }

            openMoyasarModal(result.checkout);
        } catch (err) {
            showPaymentError('Network error. Please try again.');
        } finally {
            setPayLoading(button, false);
        }
    }

    function showReceipt(item) {
        currentReceipt = item;
        const content = document.getElementById('receipt-content');
        const paidAt = item.paid_at || 'N/A';
        const createdAt = item.created_at || 'N/A';
        const provider = item.payment_provider || item.provider || 'Moyasar';
        const currency = item.currency || 'SAR';
        const amount = parseFloat(item.amount || 0).toFixed(2);
        const serviceId = item.provider_payment_id || item.transaction_id || item.tap_charge_id || 'N/A';

        const extraRefs = [];
        if (item.provider_reference && item.provider_reference !== serviceId) extraRefs.push({ label: 'Gateway Reference', value: item.provider_reference });
        if (item.tap_charge_id && item.tap_charge_id !== serviceId) extraRefs.push({ label: 'Tap Charge ID', value: item.tap_charge_id });
        if (item.tap_payment_reference) extraRefs.push({ label: 'Tap Payment Ref', value: item.tap_payment_reference });
        if (item.tap_gateway_reference) extraRefs.push({ label: 'Tap Gateway Ref', value: item.tap_gateway_reference });

        const extraRefsHtml = extraRefs.length
            ? extraRefs.map(r => `
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">${escapeHtml(r.label)}</span>
                    <span class="font-semibold text-gray-900 text-[10px] truncate max-w-[180px]">${escapeHtml(r.value)}</span>
                </div>
            `).join('')
            : '';

        const responseCode = item.provider_response_code || item.tap_response_code;
        const responseMessage = item.provider_response_message || item.tap_response_message;
        const responseHtml = (responseCode || responseMessage)
            ? `
                <div class="bg-gray-50 rounded-lg p-3 mt-3">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Payment Response') }}</p>
                    ${responseCode ? `<div class="flex justify-between text-sm"><span class="text-gray-500">{{ __('Response Code') }}</span><span class="font-semibold text-gray-900">${escapeHtml(responseCode)}</span></div>` : ''}
                    ${responseMessage ? `<div class="flex justify-between text-sm mt-1"><span class="text-gray-500">{{ __('Response Message') }}</span><span class="font-semibold text-gray-900 text-[10px] text-right max-w-[180px]">${escapeHtml(responseMessage)}</span></div>` : ''}
                </div>
            `
            : '';

        content.innerHTML = `
            <div class="text-center mb-6">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-1">{{ __('Payment Receipt') }}</h2>
                <p class="text-xs text-gray-500">${provider.toUpperCase()}</p>
            </div>
            <div class="space-y-3 border-t border-gray-100 pt-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Plan') }}</span>
                    <span class="font-semibold text-gray-900">${escapeHtml(item.plan || 'N/A')}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Period') }}</span>
                    <span class="font-semibold text-gray-900">${escapeHtml(item.period || 'N/A')}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Amount') }}</span>
                    <span class="font-semibold text-[#6E7A25]">${currency} ${amount}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Status') }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700">${escapeHtml(item.payment_status || 'paid')}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Paid on') }}</span>
                    <span class="font-semibold text-gray-900">${escapeHtml(paidAt)}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Created on') }}</span>
                    <span class="font-semibold text-gray-900">${escapeHtml(createdAt)}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ __('Gateway Payment ID') }}</span>
                    <span class="font-semibold text-gray-900 text-[10px] truncate max-w-[180px]" title="${escapeHtml(serviceId)}">${escapeHtml(serviceId)}</span>
                </div>
                ${extraRefsHtml}
            </div>
            ${responseHtml}
            <div class="mt-6 text-center text-[10px] text-gray-400">
                {{ __('Thank you for choosing Nutrio Meals') }}
            </div>
        `;

        document.getElementById('receipt-modal').classList.remove('hidden');
    }

    function closeReceipt() {
        document.getElementById('receipt-modal').classList.add('hidden');
        currentReceipt = null;
    }

    function downloadReceipt() {
        if (!currentReceipt) return;
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        if (!printWindow) return;

        const content = document.getElementById('receipt-content').innerHTML;
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>{{ __('Payment Receipt') }}</title>
                <script src="https://cdn.tailwindcss.com"><\/script>
                <style>body{font-family:system-ui,-apple-system,sans-serif;}</style>
            </head>
            <body class="bg-gray-50 p-8">
                <div class="max-w-md mx-auto bg-white rounded-xl shadow-lg p-8">
                    ${content}
                </div>
                <script>window.onload = () => { setTimeout(() => { window.print(); }, 300); };<\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush
