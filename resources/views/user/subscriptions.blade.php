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
            <div class="text-xs text-white/50">Meals remaining</div>
            @if($activePlan['status'] === 'active')
            <form action="{{ route('user.subscriptions.pause', $activePlan['id']) }}" method="POST" onsubmit="return confirm('Pause your subscription? Deliveries will be stopped until you resume.')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-400/20 text-amber-300 hover:bg-amber-400/30 text-xs font-bold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pause
                </button>
            </form>
            @elseif($activePlan['status'] === 'paused')
            <form action="{{ route('user.subscriptions.resume', $activePlan['id']) }}" method="POST" onsubmit="return confirm('Resume your subscription? Deliveries will start again.')">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-green-400/20 text-green-300 hover:bg-green-400/30 text-xs font-bold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Resume
                </button>
            </form>
            @endif
            @elseif($activePlan['payment_status'] === 'unpaid' || $activePlan['payment_status'] === 'pending')
            <div class="text-xs text-white/70 mb-1">{{ __('Complete payment to activate') }}</div>
            <form action="{{ route('user.subscriptions.pay', $activePlan['id']) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-white text-[#173327] hover:bg-white/90 text-xs font-bold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    {{ __('Pay') }} SAR {{ $activePlan['price'] }}
                </button>
            </form>
            @endif
        </div>
        @endif
    </div>
    @if($activePlan['status'] === 'active' && $activePlan['payment_status'] === 'paid')
    @php $progressWidth = ($activePlan['mealsTotal'] ?? 0) > 0 ? round($activePlan['mealsRemaining'] / $activePlan['mealsTotal'] * 100) : 0; @endphp
    <div class="mt-4 h-2 bg-white/10 rounded-full overflow-hidden">
        <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $progressWidth }}%"></div>
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
                Active
            </button>
            @else
            <form action="{{ route('user.subscriptions.subscribe') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan['id'] }}">
                <button type="submit" class="w-full px-3 py-2 text-xs font-bold rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white hover:shadow-md transition-all">
                    Switch Plan
                </button>
            </form>
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
                        @if($item['payment_status'] !== 'paid' && !empty($item['id']))
                        <form action="{{ route('user.subscriptions.pay', $item['id']) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white text-[10px] font-bold hover:shadow-md transition-all">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                {{ __('Pay') }}
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
