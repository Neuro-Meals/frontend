@extends('layouts.user')

@section('title', __('Refer & Earn') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Refer & Earn'))

@section('content')
@php
    $code = $referralData['referral_code'] ?? '';
    $rewards = is_array($referralData['rewards'] ?? null) ? $referralData['rewards'] : [];
    $referrals = is_array($referralData['referrals'] ?? null) ? $referralData['referrals'] : [];
@endphp

<div x-data="{ copied: false }" class="space-y-5">
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#173327] via-[#214b35] to-[#6E7A25] p-6 sm:p-8 text-white shadow-xl">
        <div class="absolute -right-14 -top-14 h-40 w-40 rounded-full bg-white/10"></div>
        <div class="relative max-w-2xl">
            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-white/60">{{ __('NutrioMeals Referral Program') }}</p>
            <h1 class="mt-2 text-2xl sm:text-3xl font-black">{{ __('Share NutrioMeals. Earn rewards.') }}</h1>
            <p class="mt-2 max-w-xl text-sm leading-relaxed text-white/70">
                {{ __('Share your personal referral code. When your friend completes their first qualifying subscription payment, your reward becomes available as a private discount code.') }}
            </p>

            <div class="mt-5 rounded-2xl border border-white/15 bg-white/10 p-4 backdrop-blur">
                <p class="text-[10px] font-bold uppercase tracking-wider text-white/60">{{ __('Your referral code') }}</p>
                <div class="mt-2 flex flex-col sm:flex-row gap-2">
                    <div class="flex-1 rounded-xl bg-white px-4 py-3 font-mono text-lg font-black tracking-[.15em] text-[#173327]">
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
                <p class="mt-2 text-[10px] text-white/55">
                    {{ __('Registration link') }}:
                    <span class="break-all">{{ route('register', ['ref' => $code]) }}</span>
                </p>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-3">
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-[#173327]">{{ (int) ($referralData['total_referrals'] ?? 0) }}</p>
            <p class="mt-1 text-[10px] font-bold text-gray-400">{{ __('Total Referrals') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-amber-600">{{ (int) ($referralData['pending_referrals'] ?? 0) }}</p>
            <p class="mt-1 text-[10px] font-bold text-gray-400">{{ __('Pending') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-green-600">{{ (int) ($referralData['rewarded_referrals'] ?? 0) }}</p>
            <p class="mt-1 text-[10px] font-bold text-gray-400">{{ __('Rewarded') }}</p>
        </div>
    </div>

    <section class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-sm font-extrabold text-gray-900">{{ __('My Rewards') }}</h2>
            <p class="mt-0.5 text-[10px] text-gray-400">{{ __('Use available reward codes during subscription checkout.') }}</p>
        </div>

        @forelse($rewards as $reward)
            <div class="flex flex-col gap-3 border-b border-gray-50 p-5 last:border-0 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="rounded-full px-2 py-1 text-[9px] font-extrabold uppercase
                            {{ ($reward['status'] ?? '') === 'available' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ __($reward['status'] ?? 'reward') }}
                        </span>
                        <span class="text-xs font-black text-gray-900">
                            SAR {{ number_format((float) ($reward['reward_value'] ?? 0), 2) }}
                        </span>
                    </div>
                    <p class="mt-2 font-mono text-sm font-bold tracking-wider text-[#173327]">
                        {{ $reward['coupon_code'] ?? __('Reward code pending') }}
                    </p>
                    @if(!empty($reward['expires_at']))
                        <p class="mt-1 text-[10px] text-gray-400">
                            {{ __('Expires') }} {{ \Carbon\Carbon::parse($reward['expires_at'])->format('M d, Y') }}
                        </p>
                    @endif
                </div>
                @if(!empty($reward['coupon_code']) && ($reward['status'] ?? '') === 'available')
                    <a href="{{ route('user.subscriptions') }}" class="rounded-xl bg-[#173327] px-4 py-2.5 text-center text-xs font-extrabold text-white">
                        {{ __('Use Reward') }}
                    </a>
                @endif
            </div>
        @empty
            <div class="p-8 text-center">
                <p class="text-sm font-bold text-gray-500">{{ __('No referral rewards yet') }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ __('Share your code to start earning.') }}</p>
            </div>
        @endforelse
    </section>

    <section class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="text-sm font-extrabold text-gray-900">{{ __('Referral Activity') }}</h2>
        </div>
        @forelse($referrals as $referral)
            <div class="flex items-center justify-between gap-3 border-b border-gray-50 px-5 py-4 last:border-0">
                <div>
                    <p class="text-xs font-bold text-gray-800">
                        {{ __('Referral') }} #{{ $referral['id'] ?? '-' }}
                    </p>
                    <p class="mt-1 text-[10px] text-gray-400">
                        {{ !empty($referral['created_at']) ? \Carbon\Carbon::parse($referral['created_at'])->format('M d, Y') : '' }}
                    </p>
                </div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-extrabold uppercase text-gray-600">
                    {{ __($referral['status'] ?? 'pending') }}
                </span>
            </div>
        @empty
            <div class="p-7 text-center text-xs text-gray-400">{{ __('No referrals yet.') }}</div>
        @endforelse
    </section>
</div>
@endsection
