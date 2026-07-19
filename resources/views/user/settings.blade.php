@extends('layouts.user')

@section('title', __('Settings') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Settings'))

@section('content')

{{-- Profile Header --}}
<div class="bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-2xl p-5 sm:p-6 text-white shadow-lg mb-6 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12 blur-2xl"></div>
    <div class="relative z-10 flex items-center gap-4">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/15 flex items-center justify-center flex-shrink-0 backdrop-blur-sm text-2xl sm:text-3xl font-bold">
            {{ strtoupper(substr($profile['first_name'] ?? 'U', 0, 1) . substr($profile['last_name'] ?? '', 0, 1)) }}
        </div>
        <div class="min-w-0">
            <h2 class="text-lg sm:text-xl font-bold truncate">{{ $profile['name'] ?: 'User' }}</h2>
            <div class="flex items-center gap-2 mt-1 flex-wrap">
                <span class="text-xs text-white/60">{{ $profile['email'] }}</span>
                @if($subscriptionInfo)
                <span class="w-1 h-1 bg-white/30 rounded-full"></span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $subscriptionInfo['status'] === 'active' ? 'bg-green-400/20 text-green-300' : 'bg-amber-400/20 text-amber-300' }}">
                    @if($subscriptionInfo['status'] === 'active')
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                    @endif
                    {{ ucfirst($subscriptionInfo['status']) }}
                </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Profile Info --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 lg:col-span-2">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 rounded-lg bg-[#6E7A25]/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900">{{ __('Profile') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Information') }}</span></h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('First Name') }}</label>
                <input type="text" value="{{ $profile['first_name'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Last Name') }}</label>
                <input type="text" value="{{ $profile['last_name'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Email') }}</label>
                <input type="email" value="{{ $profile['email'] }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Phone') }}</label>
                <input type="text" value="{{ $profile['phone'] ?: 'N/A' }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Date of Birth') }}</label>
                <input type="text" value="{{ $profile['dob'] ?: 'N/A' }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Gender') }}</label>
                <input type="text" value="{{ $profile['gender'] ?: 'N/A' }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Activity Level') }}</label>
                <input type="text" value="{{ $profile['activity'] ?: 'N/A' }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" readonly>
            </div>
            <div>
                <label class="text-[10px] font-medium text-gray-400">{{ __('Delivery Zone') }}</label>
                <input type="text" value="{{ $profile['zone'] ?: 'N/A' }}" class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" readonly>
            </div>
        </div>
        <div class="mt-4">
            <label class="text-[10px] font-medium text-gray-400">{{ __('Delivery Address') }}</label>
            <textarea class="mt-1 w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#6E7A25]/20 focus:border-[#6E7A25] outline-none transition-all bg-gray-50/50" rows="2" readonly>{{ $profile['address'] ?: 'N/A' }}</textarea>
        </div>
    </div>

    {{-- Health Goals --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#122620] rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#6E7A25]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-[#6E7A25]/5 rounded-full -ml-10 -mb-10 blur-xl"></div>
        <div class="flex items-center gap-2 mb-5 relative z-10">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="text-sm font-bold">{{ __('Health') }} <span class="text-[#6E7A25]">{{ __('Goals') }}</span></h3>
        </div>
        <div class="space-y-4 relative z-10">
            <div class="flex items-center justify-between py-2 border-b border-white/10">
                <span class="text-[10px] text-white/50">{{ __('Height') }}</span>
                <p class="text-lg font-bold">{{ $profile['height'] ?: 'N/A' }} <span class="text-xs text-white/40">cm</span></p>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/10">
                <span class="text-[10px] text-white/50">{{ __('Current Weight') }}</span>
                <p class="text-lg font-bold">{{ $profile['weight'] ?: 'N/A' }} <span class="text-xs text-white/40">kg</span></p>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/10">
                <span class="text-[10px] text-white/50">{{ __('Goal') }}</span>
                <p class="text-sm font-bold text-[#6E7A25]">{{ $profile['goal'] ?: 'N/A' }}</p>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-[10px] text-white/50">{{ __('Activity') }}</span>
                <p class="text-sm font-bold">{{ $profile['activity'] ?: 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Subscription Panel --}}
@if($subscriptionInfo)
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">
    <div class="flex items-center gap-2 mb-5">
        <div class="w-8 h-8 rounded-lg bg-[#025C5F]/10 flex items-center justify-center">
            <svg class="w-4 h-4 text-[#025C5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </div>
        <h3 class="text-sm font-bold text-gray-900">{{ __('Active') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Subscription') }}</span></h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Plan name --}}
        <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-md relative overflow-hidden">
            <div class="absolute top-0 right-0 w-12 h-12 bg-white/10 rounded-full -mr-6 -mt-6"></div>
            <div class="relative z-10">
                <span class="text-[10px] text-white/50">{{ __('Plan') }}</span>
                <p class="text-base font-bold mt-1 truncate">{{ $subscriptionInfo['plan_name'] }}</p>
                <p class="text-[10px] text-white/40 mt-0.5">{{ $subscriptionInfo['duration_days'] }} days · {{ $subscriptionInfo['calories'] }} kcal</p>
            </div>
        </div>

        {{-- Meals progress --}}
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Meals Progress') }}</span>
            <div class="flex items-center gap-2 mt-1">
                <p class="text-2xl font-bold text-gray-900">{{ $subscriptionInfo['meals_consumed'] }}</p>
                <p class="text-xs text-gray-400">/ {{ $subscriptionInfo['total_meals'] }}</p>
            </div>
            <div class="mt-2 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-full transition-all duration-1000" style="width: {{ $subscriptionInfo['progress'] }}%"></div>
            </div>
            <p class="text-[10px] text-gray-400 mt-1">{{ $subscriptionInfo['remaining'] }} {{ __('remaining') }}</p>
        </div>

        {{-- Period --}}
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Period') }}</span>
            <p class="text-sm font-bold text-gray-900 mt-1">{{ $subscriptionInfo['start_date'] }}</p>
            <p class="text-[10px] text-gray-400">to {{ $subscriptionInfo['end_date'] }}</p>
            <div class="mt-2 flex items-center gap-2">
                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold {{ $subscriptionInfo['status'] === 'active' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ ucfirst($subscriptionInfo['status']) }}
                </span>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold {{ $subscriptionInfo['payment_status'] === 'paid' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ ucfirst($subscriptionInfo['payment_status']) }}
                </span>
            </div>
        </div>

        {{-- Price & pauses --}}
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <span class="text-[10px] font-medium text-gray-400">{{ __('Price') }}</span>
            <p class="text-2xl font-bold text-[#6E7A25] mt-1">SAR {{ number_format($subscriptionInfo['price']) }}</p>
            <p class="text-[10px] text-gray-400 mt-0.5">{{ $subscriptionInfo['meals_per_day'] }} {{ __('meals/day') }}</p>
            <p class="text-[10px] text-gray-400 mt-1">{{ $subscriptionInfo['remaining_pauses'] }} {{ __('pauses remaining') }}</p>
        </div>
    </div>
</div>
@endif

{{-- Payment History --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900">{{ __('Payment') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('History') }}</span></h3>
        </div>
        <div class="text-right">
            <span class="text-[10px] text-gray-400">{{ __('Total Spent') }}</span>
            <p class="text-lg font-bold text-[#6E7A25]">SAR {{ number_format($totalSpent, 2) }}</p>
        </div>
    </div>

    @if(!empty($paymentHistory))
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[10px] text-gray-400 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">{{ __('Plan') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Amount') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Provider') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Date') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentHistory as $payment)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3">
                        <span class="text-xs font-bold text-gray-900">{{ $payment['plan_name'] }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs font-bold text-gray-900">{{ $payment['currency'] }} {{ number_format($payment['amount'], 2) }}</span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-[10px] text-gray-500 capitalize">{{ $payment['provider'] }}</span>
                        @if($payment['provider_payment_id'])
                        <p class="text-[9px] text-gray-400 truncate max-w-[120px]">{{ $payment['provider_payment_id'] }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-[10px] text-gray-500">{{ $payment['paid_at'] ?: $payment['created_at'] }}</span>
                    </td>
                    <td class="px-5 py-3">
                        @if($payment['status'] === 'paid' || $payment['status'] === 'completed')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Paid') }}
                        </span>
                        @elseif($payment['status'] === 'pending')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Pending') }}
                        </span>
                        @elseif($payment['status'] === 'failed')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-50 text-red-700">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            {{ __('Failed') }}
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">
                            {{ ucfirst($payment['status']) }}
                        </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="p-10 text-center">
        <div class="w-14 h-14 mx-auto bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 rounded-2xl flex items-center justify-center mb-3">
            <svg class="w-7 h-7 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">{{ __('No payment history') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ __('Your payment transactions will appear here.') }}</p>
    </div>
    @endif
</div>

@endsection
