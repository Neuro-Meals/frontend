@extends('layouts.user')

@section('title', __('Refer & Earn') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Refer & Earn'))

@section('content')
@php
    $code = $referralData['referral_code'] ?? '';
    $earnings = is_array($referralData['earnings'] ?? null)
        ? $referralData['earnings']
        : (is_array($referralData['rewards'] ?? null) ? $referralData['rewards'] : []);
    $referrals = is_array($referralData['referrals'] ?? null)
        ? $referralData['referrals']
        : [];
@endphp

<div x-data="{ copied: false }" class="space-y-5">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#173327] via-[#214b35] to-[#6E7A25] p-6 sm:p-8 text-white shadow-xl">
        <div class="absolute -right-14 -top-14 h-40 w-40 rounded-full bg-white/10"></div>
        <div class="absolute -bottom-16 left-1/3 h-40 w-40 rounded-full bg-white/5"></div>

        <div class="relative max-w-3xl">
            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-white/60">{{ __('NutrioMeals Referral Program') }}</p>
            <h1 class="mt-2 text-2xl font-black sm:text-3xl">{{ __('Share your code. Earn NutrioMeals credit.') }}</h1>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-white/70">
                {{ __('When customers registered through your referral code make payments that qualify under the current referral program, the system automatically creates credit for you. Each earning receives its own private one-use reward code.') }}
            </p>

            <div class="mt-5 rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                <p class="text-[10px] font-bold uppercase tracking-wider text-white/60">{{ __('Your Referral Code') }}</p>
                <div class="mt-2 flex flex-col gap-2 sm:flex-row">
                    <div class="min-w-0 flex-1 rounded-xl bg-white px-4 py-3 font-mono text-lg font-black tracking-[.15em] text-[#173327]">
                        {{ $code ?: __('Unavailable') }}
                    </div>
                    @if($code)
                        <button
                            type="button"
                            @click="
                                navigator.clipboard.writeText(@js($code));
                                copied = true;
                                setTimeout(() => copied = false, 1800);
                            "
                            class="rounded-xl bg-[#6E7A25] px-5 py-3 text-xs font-extrabold text-white transition hover:bg-[#7f8e2c]"
                        >
                            <span x-text="copied ? '{{ __('Copied!') }}' : '{{ __('Copy Code') }}'"></span>
                        </button>
                    @endif
                </div>

                @if($code)
                    <p class="mt-2 break-all text-[10px] text-white/55">
                        {{ __('Invite link') }}:
                        {{ route('register', ['ref' => $code]) }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-[#173327]">{{ (int) ($referralData['total_referrals'] ?? 0) }}</p>
            <p class="mt-1 text-[10px] font-bold text-gray-400">{{ __('People Referred') }}</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-purple-600">{{ (int) ($referralData['successful_transactions'] ?? 0) }}</p>
            <p class="mt-1 text-[10px] font-bold text-gray-400">{{ __('Rewarded Transactions') }}</p>
        </div>

        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-green-600">SAR {{ number_format((float) ($referralData['total_earned'] ?? 0), 2) }}</p>
            <p class="mt-1 text-[10px] font-bold text-gray-400">{{ __('Total Earned') }}</p>
        </div>

        <div class="rounded-2xl border border-[#6E7A25]/20 bg-[#f8f9f2] p-4 shadow-sm">
            <p class="text-2xl font-black text-[#6E7A25]">SAR {{ number_format((float) ($referralData['available_credit'] ?? 0), 2) }}</p>
            <p class="mt-1 text-[10px] font-bold text-gray-500">{{ __('Available Credit') }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-4">
        <div class="flex items-start gap-3">
            <div class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-700">i</div>
            <div>
                <p class="text-xs font-extrabold text-blue-900">{{ __('How your referral profit works') }}</p>
                <p class="mt-1 text-[11px] leading-relaxed text-blue-700">
                    {{ __('The Admin controls whether you earn a fixed SAR amount, a percentage of the payment, or a one-time first-payment reward. The system calculates the amount only after a qualifying payment succeeds. Your earning is then converted into private NutrioMeals credit that only your account can use.') }}
                </p>
            </div>
        </div>
    </div>

    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="flex flex-col gap-2 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-extrabold text-gray-900">{{ __('Referral Earnings') }}</h2>
                <p class="mt-0.5 text-[10px] text-gray-400">{{ __('Every qualifying successful payment creates a separate earning record.') }}</p>
            </div>
            <div class="rounded-xl bg-gray-50 px-3 py-2 text-[10px] font-bold text-gray-500">
                {{ __('Used credit') }}: SAR {{ number_format((float) ($referralData['used_credit'] ?? 0), 2) }}
            </div>
        </div>

        @forelse($earnings as $earning)
            <div class="border-b border-gray-50 p-5 last:border-0">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full px-2 py-1 text-[9px] font-extrabold uppercase
                                {{ ($earning['status'] ?? '') === 'available' ? 'bg-green-50 text-green-700' : (($earning['status'] ?? '') === 'used' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                                {{ __($earning['status'] ?? 'reward') }}
                            </span>
                            <span class="text-sm font-black text-green-700">
                                + SAR {{ number_format((float) ($earning['reward_value'] ?? $earning['reward_amount'] ?? 0), 2) }}
                            </span>
                        </div>

                        @if(!empty($earning['payment_id']))
                            <div class="mt-2 grid grid-cols-2 gap-x-5 gap-y-1 text-[10px] text-gray-400 sm:flex sm:flex-wrap">
                                <span>{{ __('Payment') }} #{{ $earning['payment_id'] }}</span>
                                <span>{{ __('Paid') }} SAR {{ number_format((float) ($earning['payment_amount'] ?? 0), 2) }}</span>
                                @if(($earning['reward_type'] ?? '') === 'percentage_of_payment')
                                    <span>{{ __('Rate') }} {{ number_format((float) ($earning['reward_rate'] ?? 0), 2) }}%</span>
                                @endif
                            </div>
                        @endif

                        @if(!empty($earning['coupon_code']))
                            <div class="mt-3 inline-flex max-w-full items-center gap-2 rounded-xl bg-[#f8f9f2] px-3 py-2">
                                <span class="text-[9px] font-bold uppercase text-gray-400">{{ __('Credit Code') }}</span>
                                <span class="truncate font-mono text-xs font-black tracking-wider text-[#173327]">{{ $earning['coupon_code'] }}</span>
                            </div>
                        @endif

                        @if(!empty($earning['expires_at']))
                            <p class="mt-2 text-[9px] text-gray-400">
                                {{ __('Expires') }} {{ \Carbon\Carbon::parse($earning['expires_at'])->format('M d, Y') }}
                            </p>
                        @endif
                    </div>

                    @if(!empty($earning['coupon_code']) && ($earning['status'] ?? '') === 'available')
                        <a href="{{ route('user.subscriptions') }}" class="rounded-xl bg-[#173327] px-4 py-2.5 text-center text-xs font-extrabold text-white">
                            {{ __('Use Credit') }}
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-10 text-center">
                <p class="text-sm font-bold text-gray-500">{{ __('No referral earnings yet') }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ __('Share your code. Earnings appear here after qualifying referred payments succeed.') }}</p>
            </div>
        @endforelse
    </section>

    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-sm font-extrabold text-gray-900">{{ __('People You Referred') }}</h2>
            <p class="mt-0.5 text-[10px] text-gray-400">{{ __('A referral relationship remains attached to the original code owner according to the program rules.') }}</p>
        </div>

        @forelse($referrals as $referral)
            <div class="flex items-center justify-between gap-3 border-b border-gray-50 px-5 py-4 last:border-0">
                <div>
                    <p class="text-xs font-bold text-gray-800">{{ __('Customer') }} #{{ $referral['referred_user_id'] ?? '-' }}</p>
                    <p class="mt-1 text-[10px] text-gray-400">
                        {{ !empty($referral['created_at']) ? \Carbon\Carbon::parse($referral['created_at'])->format('M d, Y') : '' }}
                    </p>
                </div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[9px] font-extrabold uppercase text-gray-600">
                    {{ __($referral['status'] ?? 'pending') }}
                </span>
            </div>
        @empty
            <div class="p-7 text-center text-xs text-gray-400">{{ __('No referrals yet.') }}</div>
        @endforelse
    </section>
</div>
@endsection
