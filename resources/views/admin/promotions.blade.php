@extends('layouts.admin')

@section('title', __('Promotions') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Promotions'))

@section('content')
<div
    x-data="promotionsApp()"
    class="space-y-5"
>
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-black text-gray-900">{{ __('Promotions & Referrals') }}</h1>
            <p class="mt-1 text-xs text-gray-400">{{ __('Manage discount codes, referral rewards and qualification rules.') }}</p>
        </div>
        <button type="button" @click="openCoupon()" class="rounded-xl bg-gradient-to-r from-[#173327] to-[#6E7A25] px-4 py-2.5 text-xs font-extrabold text-white shadow">
            + {{ __('New Discount Code') }}
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-[#173327]">{{ count($coupons) }}</p>
            <p class="text-[10px] font-bold text-gray-400">{{ __('Discount Codes') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-green-600">{{ count(array_filter($coupons, fn ($c) => (bool) ($c['is_active'] ?? false))) }}</p>
            <p class="text-[10px] font-bold text-gray-400">{{ __('Active Codes') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-[#6E7A25]">{{ (int) ($referralMeta['total'] ?? count($referrals)) }}</p>
            <p class="text-[10px] font-bold text-gray-400">{{ __('Referrals') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
            <p class="text-2xl font-black text-amber-600">
                @if(($referralSettings['reward_mode'] ?? '') === 'percentage_of_payment')
                    {{ number_format((float) ($referralSettings['reward_value'] ?? 0), 2) }}%
                @else
                    SAR {{ number_format((float) ($referralSettings['reward_value'] ?? 100), 2) }}
                @endif
            </p>
            <p class="text-[10px] font-bold text-gray-400">{{ __('Current Referral Reward') }}</p>
        </div>
    </div>

    <div class="flex gap-2 overflow-x-auto rounded-2xl border border-gray-100 bg-white p-2 shadow-sm">
        <button type="button" @click="tab='coupons'" :class="tab==='coupons' ? 'bg-[#173327] text-white' : 'text-gray-500'" class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-extrabold">{{ __('Discount Codes') }}</button>
        <button type="button" @click="tab='referrals'" :class="tab==='referrals' ? 'bg-[#173327] text-white' : 'text-gray-500'" class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-extrabold">{{ __('Referral Program') }}</button>
    </div>

    <section x-show="tab==='coupons'" class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-[900px] w-full text-left text-xs">
                <thead class="bg-gray-50 text-[10px] uppercase tracking-wider text-gray-400">
                    <tr>
                        <th class="px-4 py-3">{{ __('Code') }}</th>
                        <th class="px-4 py-3">{{ __('Discount') }}</th>
                        <th class="px-4 py-3">{{ __('Usage') }}</th>
                        <th class="px-4 py-3">{{ __('Rules') }}</th>
                        <th class="px-4 py-3">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($coupons as $coupon)
                    <tr>
                        <td class="px-4 py-4">
                            <p class="font-mono text-sm font-black text-[#173327]">{{ $coupon['code'] ?? '' }}</p>
                            <p class="mt-1 max-w-xs truncate text-[10px] text-gray-400">{{ $coupon['description'] ?? '' }}</p>
                        </td>
                        <td class="px-4 py-4 font-bold">
                            @if(($coupon['discount_type'] ?? '') === 'percentage')
                                {{ number_format((float) ($coupon['discount_value'] ?? 0), 0) }}%
                            @else
                                SAR {{ number_format((float) ($coupon['discount_value'] ?? 0), 2) }}
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            {{ (int) ($coupon['used_count'] ?? 0) }}
                            @if(!empty($coupon['max_uses'])) / {{ (int) $coupon['max_uses'] }} @endif
                        </td>
                        <td class="px-4 py-4 text-[10px] text-gray-500">
                            @if(!empty($coupon['new_customers_only'])) <span class="mr-1 rounded bg-blue-50 px-2 py-1 text-blue-700">{{ __('New only') }}</span> @endif
                            @if(!empty($coupon['applicable_plan_id'])) <span class="rounded bg-gray-100 px-2 py-1">{{ __('Plan') }} #{{ $coupon['applicable_plan_id'] }}</span> @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-2 py-1 text-[9px] font-extrabold {{ !empty($coupon['is_active']) ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ !empty($coupon['is_active']) ? __('Active') : __('Inactive') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <button
                                    type="button"
                                    @click='openCouponUsage(@json($coupon))'
                                    class="rounded-lg bg-[#173327] px-3 py-2 text-[10px] font-extrabold text-white transition hover:bg-[#214b35]"
                                >
                                    {{ __('Usage') }}
                                </button>
                                <button
                                    type="button"
                                    @click='editCoupon(@json($coupon))'
                                    class="rounded-lg bg-[#f4f6e8] px-3 py-2 text-[10px] font-extrabold text-[#6E7A25] transition hover:bg-[#eef1dc]"
                                >
                                    {{ __('Edit') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">{{ __('No discount codes yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section x-show="tab==='referrals'" x-cloak class="space-y-5">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('Referral Relationships') }}</p>
                <p class="mt-2 text-2xl font-black text-[#173327]">{{ (int) ($referralMeta['total'] ?? count($referrals)) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('Total Commission Earned') }}</p>
                <p class="mt-2 text-2xl font-black text-green-600">SAR {{ number_format((float) ($referralMeta['earnings_total'] ?? 0), 2) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('Available Customer Credit') }}</p>
                <p class="mt-2 text-2xl font-black text-amber-600">SAR {{ number_format((float) ($referralMeta['available_credit_total'] ?? 0), 2) }}</p>
            </div>
            <div class="rounded-2xl border border-gray-100 bg-white p-4 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('Earning Transactions') }}</p>
                <p class="mt-2 text-2xl font-black text-purple-600">{{ (int) ($earningsMeta['total'] ?? count($referralEarnings)) }}</p>
            </div>
        </div>

        <div class="grid gap-5 xl:grid-cols-3">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm xl:col-span-1">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-black text-gray-900">{{ __('Referral Commission Settings') }}</h2>
                        <p class="mt-1 text-[10px] leading-relaxed text-gray-400">{{ __('Control exactly how referral-code owners earn NutrioMeals credit from successful referred subscription payments.') }}</p>
                    </div>
                    <span
                        class="rounded-full px-2.5 py-1 text-[9px] font-extrabold"
                        :class="referralSettings.is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500'"
                        x-text="referralSettings.is_active ? '{{ __('Active') }}' : '{{ __('Inactive') }}'"
                    ></span>
                </div>

                <div class="mt-5 space-y-4">
                    <label class="flex items-center justify-between rounded-xl bg-gray-50 p-3">
                        <div>
                            <span class="block text-xs font-bold text-gray-700">{{ __('Program Active') }}</span>
                            <span class="mt-0.5 block text-[9px] text-gray-400">{{ __('Stop or allow new referral earnings.') }}</span>
                        </div>
                        <input type="checkbox" x-model="referralSettings.is_active">
                    </label>

                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('Reward Model') }}</label>
                        <select
                            x-model="referralSettings.reward_mode"
                            @change="syncRewardMode()"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm font-semibold"
                        >
                            <option value="fixed_per_payment">{{ __('Fixed amount per successful payment') }}</option>
                            <option value="percentage_of_payment">{{ __('Percentage of successful payment') }}</option>
                            <option value="fixed_first_payment">{{ __('Fixed amount on first successful payment only') }}</option>
                        </select>
                        <p class="mt-1.5 text-[10px] leading-relaxed text-gray-400" x-text="rewardModeHelp()"></p>
                    </div>

                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">
                            <span x-text="referralSettings.reward_mode === 'percentage_of_payment' ? '{{ __('Commission Percentage') }}' : '{{ __('Reward Amount (SAR)') }}'"></span>
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                min="0.01"
                                :max="referralSettings.reward_mode === 'percentage_of_payment' ? 100 : null"
                                step="0.01"
                                x-model.number="referralSettings.reward_value"
                                class="w-full rounded-xl border border-gray-200 px-3 py-2.5 pr-14 text-sm"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400"
                                  x-text="referralSettings.reward_mode === 'percentage_of_payment' ? '%' : 'SAR'"></span>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('Commission Scope') }}</label>
                        <select
                            x-model="referralSettings.commission_scope"
                            :disabled="referralSettings.reward_mode === 'fixed_first_payment'"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm font-semibold disabled:bg-gray-100 disabled:text-gray-400"
                        >
                            <option value="first_payment_only">{{ __('First successful payment only') }}</option>
                            <option value="every_payment">{{ __('Every successful subscription payment') }}</option>
                        </select>
                        <p class="mt-1.5 text-[10px] leading-relaxed text-gray-400">
                            {{ __('Every payment keeps the referred customer permanently attributed to the original referral-code owner.') }}
                        </p>
                    </div>

                    <div x-show="referralSettings.reward_mode === 'percentage_of_payment'">
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('Maximum Reward Per Payment (Optional)') }}</label>
                        <div class="relative">
                            <input
                                type="number"
                                min="0.01"
                                step="0.01"
                                x-model.number="referralSettings.max_reward_per_payment"
                                placeholder="{{ __('No cap') }}"
                                class="w-full rounded-xl border border-gray-200 px-3 py-2.5 pr-14 text-sm"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">SAR</span>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">{{ __('Credit Expiry Days') }}</label>
                        <input type="number" min="1" max="3650" x-model.number="referralSettings.reward_expiry_days" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                    </div>

                    <div class="rounded-xl border border-[#6E7A25]/15 bg-[#f8f9f2] p-3">
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-[#6E7A25]">{{ __('Live Example') }}</p>
                        <p class="mt-1 text-xs font-semibold leading-relaxed text-[#173327]" x-text="rewardExample()"></p>
                    </div>

                    <button type="button" @click="saveReferralSettings()" :disabled="savingReferral" class="w-full rounded-xl bg-[#173327] py-3 text-xs font-extrabold text-white disabled:opacity-50">
                        <span x-show="!savingReferral">{{ __('Save Referral Program') }}</span>
                        <span x-show="savingReferral">{{ __('Saving...') }}</span>
                    </button>
                </div>
            </div>

            <div class="space-y-5 xl:col-span-2">
                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="flex items-center justify-between gap-3 border-b border-gray-100 px-5 py-4">
                        <div>
                            <h2 class="text-sm font-black text-gray-900">{{ __('Referral Earnings Ledger') }}</h2>
                            <p class="mt-0.5 text-[10px] text-gray-400">{{ __('One row per qualifying successful payment. This is the audit trail for referral profit.') }}</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-[850px] w-full text-left text-xs">
                            <thead class="bg-gray-50 text-[9px] uppercase tracking-wider text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">{{ __('Payment') }}</th>
                                    <th class="px-4 py-3">{{ __('Referrer') }}</th>
                                    <th class="px-4 py-3">{{ __('Referred') }}</th>
                                    <th class="px-4 py-3">{{ __('Payment Amount') }}</th>
                                    <th class="px-4 py-3">{{ __('Reward Rule') }}</th>
                                    <th class="px-4 py-3">{{ __('Earned') }}</th>
                                    <th class="px-4 py-3">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($referralEarnings as $earning)
                                    <tr>
                                        <td class="px-4 py-3 font-mono font-bold text-gray-700">#{{ $earning['payment_id'] ?? '-' }}</td>
                                        <td class="px-4 py-3">#{{ $earning['referrer_user_id'] ?? '-' }}</td>
                                        <td class="px-4 py-3">#{{ $earning['referred_user_id'] ?? '-' }}</td>
                                        <td class="px-4 py-3 font-bold">SAR {{ number_format((float) ($earning['payment_amount'] ?? 0), 2) }}</td>
                                        <td class="px-4 py-3 text-[10px]">
                                            <span class="font-bold text-gray-700">{{ str_replace('_', ' ', ucfirst($earning['reward_mode'] ?? '-')) }}</span>
                                            <div class="mt-0.5 text-gray-400">
                                                @if(($earning['reward_mode'] ?? '') === 'percentage_of_payment')
                                                    {{ number_format((float) ($earning['reward_rate'] ?? 0), 2) }}%
                                                @else
                                                    SAR {{ number_format((float) ($earning['reward_rate'] ?? 0), 2) }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 font-black text-green-700">SAR {{ number_format((float) ($earning['reward_amount'] ?? 0), 2) }}</td>
                                        <td class="px-4 py-3">
                                            <span class="rounded-full px-2 py-1 text-[9px] font-extrabold uppercase
                                                {{ ($earning['status'] ?? '') === 'available' ? 'bg-green-50 text-green-700' : (($earning['status'] ?? '') === 'used' ? 'bg-blue-50 text-blue-700' : 'bg-gray-100 text-gray-500') }}">
                                                {{ __($earning['status'] ?? 'unknown') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                                            {{ __('No referral earning transactions yet. They appear after a referred customer makes a qualifying successful payment.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-sm font-black text-gray-900">{{ __('Referral Relationships') }}</h2>
                        <p class="mt-0.5 text-[10px] text-gray-400">{{ __('Shows who referred whom and whether the relationship has produced a qualifying reward.') }}</p>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @forelse($referrals as $referral)
                            <div class="flex items-center justify-between gap-3 px-5 py-4">
                                <div>
                                    <p class="text-xs font-bold text-gray-800">{{ __('Referral') }} #{{ $referral['id'] ?? '-' }}</p>
                                    <p class="mt-1 text-[10px] text-gray-400">
                                        {{ __('Referrer') }} #{{ $referral['referrer_user_id'] ?? '-' }}
                                        → {{ __('Customer') }} #{{ $referral['referred_user_id'] ?? '-' }}
                                    </p>
                                </div>
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[9px] font-extrabold uppercase text-gray-600">
                                    {{ __($referral['status'] ?? 'pending') }}
                                </span>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-gray-400">{{ __('No referrals recorded yet.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Coupon Usage Modal --}}
    <div
        x-show="usageModal"
        x-cloak
        class="fixed inset-0 z-[110] flex items-center justify-center bg-black/50 p-3"
        @click.self="closeCouponUsage()"
    >
        <div class="flex max-h-[92vh] w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
            <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.16em] text-gray-400">{{ __('Coupon Usage History') }}</p>
                    <h3 class="mt-1 text-lg font-black text-gray-900">
                        <span class="font-mono tracking-wider text-[#173327]" x-text="usageCoupon?.code || ''"></span>
                    </h3>
                    <p class="mt-1 text-[10px] text-gray-400">
                        {{ __('Only successful coupon redemptions are shown here.') }}
                    </p>
                </div>
                <button type="button" @click="closeCouponUsage()" class="text-xl text-gray-400 hover:text-gray-700">✕</button>
            </div>

            <div class="grid grid-cols-2 gap-3 border-b border-gray-100 bg-gray-50/60 p-4 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-100 bg-white p-3">
                    <p class="text-[9px] font-bold uppercase text-gray-400">{{ __('Successful Uses') }}</p>
                    <p class="mt-1 text-xl font-black text-[#173327]" x-text="usageSummary.successful_uses || 0"></p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-white p-3">
                    <p class="text-[9px] font-bold uppercase text-gray-400">{{ __('Original Revenue') }}</p>
                    <p class="mt-1 text-lg font-black text-gray-800">SAR <span x-text="money(usageSummary.original_revenue)"></span></p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-white p-3">
                    <p class="text-[9px] font-bold uppercase text-gray-400">{{ __('Discount Given') }}</p>
                    <p class="mt-1 text-lg font-black text-red-600">SAR <span x-text="money(usageSummary.total_discount)"></span></p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-white p-3">
                    <p class="text-[9px] font-bold uppercase text-gray-400">{{ __('Paid After Discount') }}</p>
                    <p class="mt-1 text-lg font-black text-green-700">SAR <span x-text="money(usageSummary.revenue_after_discount)"></span></p>
                </div>
            </div>

            <div class="min-h-0 flex-1 overflow-auto">
                <div x-show="usageLoading" class="flex min-h-[260px] items-center justify-center p-8">
                    <div class="text-center">
                        <div class="mx-auto h-8 w-8 animate-spin rounded-full border-2 border-gray-200 border-t-[#6E7A25]"></div>
                        <p class="mt-3 text-xs font-semibold text-gray-400">{{ __('Loading usage history...') }}</p>
                    </div>
                </div>

                <div x-show="!usageLoading && usageError" class="p-8 text-center">
                    <p class="text-sm font-bold text-red-600" x-text="usageError"></p>
                </div>

                <div x-show="!usageLoading && !usageError && usageRows.length === 0" class="p-10 text-center">
                    <p class="text-sm font-bold text-gray-500">{{ __('No successful uses yet') }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ __('Customers will appear here after they complete payment using this code.') }}</p>
                </div>

                <div x-show="!usageLoading && !usageError && usageRows.length > 0" class="overflow-x-auto">
                    <table class="min-w-[900px] w-full text-left text-xs">
                        <thead class="sticky top-0 bg-gray-50 text-[9px] uppercase tracking-wider text-gray-400">
                            <tr>
                                <th class="px-4 py-3">{{ __('Customer') }}</th>
                                <th class="px-4 py-3">{{ __('Subscription') }}</th>
                                <th class="px-4 py-3">{{ __('Payment') }}</th>
                                <th class="px-4 py-3">{{ __('Original') }}</th>
                                <th class="px-4 py-3">{{ __('Discount') }}</th>
                                <th class="px-4 py-3">{{ __('Paid') }}</th>
                                <th class="px-4 py-3">{{ __('Redeemed At') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <template x-for="row in usageRows" :key="row.id || `${row.user_id}-${row.payment_id}`">
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-4 py-3">
                                        <p class="font-bold text-gray-800" x-text="row.customer_email || row.user_email || `User #${row.user_id || '-'}`"></p>
                                        <p class="mt-0.5 text-[9px] text-gray-400" x-show="row.user_id">#<span x-text="row.user_id"></span></p>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-gray-600">#<span x-text="row.subscription_id || '-'"></span></td>
                                    <td class="px-4 py-3 font-mono text-gray-600">#<span x-text="row.payment_id || '-'"></span></td>
                                    <td class="px-4 py-3">SAR <span x-text="money(row.original_amount)"></span></td>
                                    <td class="px-4 py-3 font-bold text-red-600">- SAR <span x-text="money(row.discount_amount)"></span></td>
                                    <td class="px-4 py-3 font-black text-green-700">SAR <span x-text="money(row.final_amount)"></span></td>
                                    <td class="px-4 py-3 text-[10px] text-gray-500" x-text="dateTime(row.redeemed_at)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 border-t border-gray-100 bg-white px-5 py-3">
                <p class="text-[10px] text-gray-400">
                    <span x-text="usageSummary.successful_uses || 0"></span> {{ __('successful redemption(s)') }}
                </p>
                <button type="button" @click="closeCouponUsage()" class="rounded-xl bg-gray-100 px-4 py-2.5 text-xs font-bold text-gray-600">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Coupon Modal --}}
    <div x-show="couponModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/45 p-3" @click.self="couponModal=false">
        <div class="max-h-[92vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white shadow-2xl">
            <div class="sticky top-0 flex items-center justify-between border-b border-gray-100 bg-white px-5 py-4">
                <div>
                    <h3 class="text-base font-black text-gray-900" x-text="couponForm.id ? '{{ __('Edit Discount Code') }}' : '{{ __('New Discount Code') }}'"></h3>
                    <p class="mt-0.5 text-[10px] text-gray-400">{{ __('The backend validates all rules during checkout.') }}</p>
                </div>
                <button type="button" @click="couponModal=false" class="text-gray-400">✕</button>
            </div>

            <div class="grid gap-4 p-5 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Code') }}</label>
                    <input type="text" x-model="couponForm.code" :disabled="!!couponForm.id" @input="couponForm.code=couponForm.code.toUpperCase()" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm font-black uppercase">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Discount Type') }}</label>
                    <select x-model="couponForm.discount_type" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                        <option value="percentage">{{ __('Percentage') }}</option>
                        <option value="fixed">{{ __('Fixed SAR') }}</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Discount Value') }}</label>
                    <input type="number" min="0.01" step="0.01" x-model.number="couponForm.discount_value" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Minimum Amount') }}</label>
                    <input type="number" min="0" step="0.01" x-model.number="couponForm.min_order_amount" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Total Usage Limit') }}</label>
                    <input type="number" min="1" x-model.number="couponForm.max_uses" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Per Customer Limit') }}</label>
                    <input type="number" min="1" x-model.number="couponForm.max_uses_per_user" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Applicable Plan') }}</label>
                    <select x-model="couponForm.applicable_plan_id" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm">
                        <option value="">{{ __('All Plans') }}</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan['id'] }}">{{ $plan['name_en'] ?? ('Plan #' . $plan['id']) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1 block text-[10px] font-bold uppercase text-gray-400">{{ __('Description') }}</label>
                    <textarea x-model="couponForm.description" rows="2" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm"></textarea>
                </div>
                <label class="flex items-center gap-2"><input type="checkbox" x-model="couponForm.new_customers_only"><span class="text-xs font-bold">{{ __('New customers only') }}</span></label>
                <label class="flex items-center gap-2"><input type="checkbox" x-model="couponForm.is_active"><span class="text-xs font-bold">{{ __('Active') }}</span></label>
            </div>

            <div class="sticky bottom-0 flex gap-2 border-t border-gray-100 bg-white p-4">
                <button type="button" @click="couponModal=false" class="flex-1 rounded-xl bg-gray-100 py-3 text-xs font-bold text-gray-600">{{ __('Cancel') }}</button>
                <button type="button" @click="saveCoupon()" :disabled="savingCoupon" class="flex-1 rounded-xl bg-[#173327] py-3 text-xs font-extrabold text-white disabled:opacity-50">{{ __('Save Code') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function promotionsApp() {
    return {
        tab: 'coupons',
        couponModal: false,
        savingCoupon: false,
        savingReferral: false,

        usageModal: false,
        usageLoading: false,
        usageError: '',
        usageCoupon: null,
        usageRows: [],
        usageSummary: {
            successful_uses: 0,
            original_revenue: 0,
            total_discount: 0,
            revenue_after_discount: 0,
        },
        referralSettings: {
            is_active: true,
            reward_mode: 'fixed_first_payment',
            reward_value: 100,
            commission_scope: 'first_payment_only',
            max_reward_per_payment: null,
            reward_expiry_days: 90,
            ...@json($referralSettings),
        },
        couponForm: {},

        money(value) {
            return Number(value || 0).toFixed(2);
        },

        dateTime(value) {
            if (!value) return '-';

            try {
                return new Intl.DateTimeFormat(
                    document.documentElement.lang || 'en',
                    {
                        year: 'numeric',
                        month: 'short',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                    }
                ).format(new Date(value));
            } catch (_) {
                return value;
            }
        },

        closeCouponUsage() {
            this.usageModal = false;
            this.usageLoading = false;
            this.usageError = '';
            this.usageCoupon = null;
            this.usageRows = [];
            this.usageSummary = {
                successful_uses: 0,
                original_revenue: 0,
                total_discount: 0,
                revenue_after_discount: 0,
            };
        },

        async openCouponUsage(coupon) {
            this.usageModal = true;
            this.usageLoading = true;
            this.usageError = '';
            this.usageCoupon = coupon;
            this.usageRows = [];
            this.usageSummary = {
                successful_uses: Number(coupon?.used_count || 0),
                original_revenue: 0,
                total_discount: 0,
                revenue_after_discount: 0,
            };

            try {
                const url = @js(route('admin.promotions.coupons.usage', ['id' => '__ID__']))
                    .replace('__ID__', coupon.id);

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok || data.success === false) {
                    throw new Error(
                        data.message
                        || '{{ __('Unable to load coupon usage history.') }}'
                    );
                }

                this.usageRows = Array.isArray(data.data)
                    ? data.data
                    : [];

                this.usageSummary = {
                    successful_uses: Number(
                        data.summary?.successful_uses
                        ?? this.usageRows.length
                    ),
                    original_revenue: Number(
                        data.summary?.original_revenue || 0
                    ),
                    total_discount: Number(
                        data.summary?.total_discount || 0
                    ),
                    revenue_after_discount: Number(
                        data.summary?.revenue_after_discount || 0
                    ),
                };
            } catch (error) {
                this.usageError = error?.message
                    || '{{ __('Unable to load coupon usage history.') }}';
            } finally {
                this.usageLoading = false;
            }
        },

        emptyCoupon() {
            return {
                id: null,
                code: '',
                description: '',
                discount_type: 'percentage',
                discount_value: 10,
                max_uses: null,
                max_uses_per_user: 1,
                min_order_amount: null,
                applicable_plan_id: '',
                allowed_user_id: null,
                new_customers_only: false,
                is_active: true,
            };
        },

        openCoupon() {
            this.couponForm = this.emptyCoupon();
            this.couponModal = true;
        },

        editCoupon(coupon) {
            this.couponForm = {
                ...this.emptyCoupon(),
                ...coupon,
                applicable_plan_id: coupon.applicable_plan_id || '',
            };
            this.couponModal = true;
        },

        cleanPayload(payload) {
            const cleaned = { ...payload };
            delete cleaned.id;
            delete cleaned.used_count;
            delete cleaned.created_at;
            delete cleaned.source;

            ['max_uses', 'max_uses_per_user', 'min_order_amount', 'applicable_plan_id', 'allowed_user_id']
                .forEach(key => {
                    if (cleaned[key] === '' || cleaned[key] === null || cleaned[key] === undefined) {
                        cleaned[key] = null;
                    } else {
                        cleaned[key] = Number(cleaned[key]);
                    }
                });

            cleaned.discount_value = Number(cleaned.discount_value || 0);
            return cleaned;
        },

        async saveCoupon() {
            if (this.savingCoupon) return;
            this.savingCoupon = true;

            try {
                const isEdit = !!this.couponForm.id;
                const url = isEdit
                    ? @js(route('admin.promotions.coupons.update', ['id' => '__ID__'])).replace('__ID__', this.couponForm.id)
                    : @js(route('admin.promotions.coupons.store'));

                const response = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                    },
                    body: JSON.stringify(this.cleanPayload(this.couponForm)),
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok || data.success === false) {
                    throw new Error(data.message || '{{ __('Unable to save discount code.') }}');
                }

                await Swal.fire({
                    icon: 'success',
                    title: '{{ __('Saved') }}',
                    text: data.message || '{{ __('Discount code saved.') }}',
                    confirmButtonColor: '#173327',
                });
                window.location.reload();
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('Error') }}',
                    text: error.message || '{{ __('Unable to save discount code.') }}',
                    confirmButtonColor: '#173327',
                });
            } finally {
                this.savingCoupon = false;
            }
        },

        syncRewardMode() {
            if (this.referralSettings.reward_mode === 'fixed_first_payment') {
                this.referralSettings.commission_scope = 'first_payment_only';
            }

            if (this.referralSettings.reward_mode !== 'percentage_of_payment') {
                this.referralSettings.max_reward_per_payment = null;
            }
        },

        rewardModeHelp() {
            if (this.referralSettings.reward_mode === 'percentage_of_payment') {
                return '{{ __('The referrer earns a percentage of each qualifying successful subscription payment.') }}';
            }

            if (this.referralSettings.reward_mode === 'fixed_per_payment') {
                return '{{ __('The referrer earns the same SAR amount for each qualifying successful subscription payment.') }}';
            }

            return '{{ __('The referrer earns one fixed SAR reward only when the referred customer completes their first successful subscription payment.') }}';
        },

        rewardExample() {
            const value = Number(this.referralSettings.reward_value || 0);

            if (this.referralSettings.reward_mode === 'percentage_of_payment') {
                const samplePayment = 1999;
                let reward = samplePayment * (value / 100);

                if (this.referralSettings.max_reward_per_payment) {
                    reward = Math.min(
                        reward,
                        Number(this.referralSettings.max_reward_per_payment)
                    );
                }

                return `{{ __('Example') }}: SAR ${samplePayment.toFixed(2)} × ${value}% = SAR ${reward.toFixed(2)} {{ __('referral credit') }}.`;
            }

            if (this.referralSettings.reward_mode === 'fixed_per_payment') {
                const scopeText =
                    this.referralSettings.commission_scope === 'every_payment'
                        ? '{{ __('on every qualifying successful payment') }}'
                        : '{{ __('on the first qualifying successful payment') }}';

                return `{{ __('The referral owner earns') }} SAR ${value.toFixed(2)} ${scopeText}.`;
            }

            return `{{ __('The referral owner earns') }} SAR ${value.toFixed(2)} {{ __('once, after the referred customer completes their first successful payment') }}.`;
        },

        async saveReferralSettings() {
            if (this.savingReferral) return;
            this.savingReferral = true;

            try {
                const response = await fetch(@js(route('admin.promotions.referrals.settings')), {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                    },
                    body: JSON.stringify({
                        is_active: !!this.referralSettings.is_active,
                        reward_mode: this.referralSettings.reward_mode,
                        reward_value: Number(this.referralSettings.reward_value),
                        commission_scope:
                            this.referralSettings.reward_mode === 'fixed_first_payment'
                                ? 'first_payment_only'
                                : this.referralSettings.commission_scope,
                        max_reward_per_payment:
                            this.referralSettings.reward_mode === 'percentage_of_payment'
                            && this.referralSettings.max_reward_per_payment
                                ? Number(this.referralSettings.max_reward_per_payment)
                                : null,
                        reward_expiry_days: Number(this.referralSettings.reward_expiry_days),
                    }),
                });

                const data = await response.json().catch(() => ({}));
                if (!response.ok || data.success === false) {
                    throw new Error(data.message || '{{ __('Unable to update referral program.') }}');
                }

                Swal.fire({
                    icon: 'success',
                    title: '{{ __('Referral Program Updated') }}',
                    text: data.message,
                    confirmButtonColor: '#173327',
                });
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('Error') }}',
                    text: error.message,
                    confirmButtonColor: '#173327',
                });
            } finally {
                this.savingReferral = false;
            }
        },
    };
}
</script>
@endpush
@endsection
