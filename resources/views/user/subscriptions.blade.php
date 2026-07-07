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
            <span class="text-xs font-medium text-white/50">Active Subscription</span>
            <h2 class="text-2xl font-bold mt-1">{{ $activePlan['name'] }}</h2>
            <div class="flex items-center gap-4 mt-3 text-xs text-white/60">
                <span>SAR {{ $activePlan['price'] }} / {{ $activePlan['duration'] }}</span>
                <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                <span>{{ $activePlan['calories'] }} kcal</span>
                <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                <span>Renews {{ $activePlan['renewal'] }}</span>
            </div>
        </div>
        <div class="text-right">
            <div class="text-3xl font-bold">{{ $activePlan['mealsRemaining'] }}<span class="text-sm text-white/50">/{{ $activePlan['mealsTotal'] }}</span></div>
            <div class="text-xs text-white/50 mt-1">Meals remaining</div>
        </div>
    </div>
    @php $progressWidth = ($activePlan['mealsTotal'] ?? 0) > 0 ? round($activePlan['mealsRemaining'] / $activePlan['mealsTotal'] * 100) : 0; @endphp
    <div class="mt-4 h-2 bg-white/10 rounded-full overflow-hidden">
        <div class="h-full bg-white rounded-full transition-all duration-1000" style="width: {{ $progressWidth }}%"></div>
    </div>
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
                </tr>
            </thead>
            <tbody>
                @foreach($history as $item)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-semibold text-gray-900">{{ $item['plan'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $item['period'] }}</td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">SAR {{ $item['amount'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $item['status'] === 'active' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ ucfirst($item['status']) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
