@extends('layouts.auth')

@section('title', __('Payment') . ' - ' . __('Nutrio Meals'))

@section('content')
@php
    $initialAmountSar = ((int) ($checkout['amount'] ?? 0)) / 100;
@endphp

<div
    x-data="paymentCheckout(@js($checkout), {{ (int) $subscriptionId }})"
    x-init="init()"
    class="w-full max-w-md animate-simple-fade-in"
>
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl">
        <div class="h-2 w-full bg-gradient-to-r from-[#173327] via-[#6E7A25] to-[#173327]"></div>

        <div class="p-6">
            <div class="mb-5 text-center">
                <h2 class="text-xl font-bold text-gray-900">{{ __('Complete Payment') }}</h2>
                <p class="mt-2 text-2xl font-black text-[#173327]">
                    <span x-text="checkout.currency || 'SAR'"></span>
                    <span x-text="formatMoney((checkout.amount || 0) / 100)"></span>
                </p>
                <p class="mt-1 text-xs text-gray-400" x-text="checkout.description || ''"></p>
            </div>

            {{-- Discount / Reward Code --}}
            <div class="mb-5 rounded-2xl border border-[#6E7A25]/20 bg-[#f8f9f2] p-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-extrabold text-gray-800">{{ __('Discount or Reward Code') }}</p>
                        <p class="mt-0.5 text-[10px] text-gray-400">
                            {{ __('Use an admin discount code or your referral reward code.') }}
                        </p>
                    </div>
                    <a href="{{ route('user.referrals') }}" class="text-[10px] font-extrabold text-[#6E7A25]">
                        {{ __('My Rewards') }}
                    </a>
                </div>

                <div class="mt-3 flex gap-2">
                    <input
                        type="text"
                        x-model="couponCode"
                        @input="couponCode = couponCode.toUpperCase(); couponError = ''"
                        @keydown.enter.prevent="applyCoupon()"
                        maxlength="50"
                        placeholder="{{ __('Enter code') }}"
                        class="min-w-0 flex-1 rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm font-bold uppercase tracking-wider outline-none focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/10"
                    >
                    <button
                        type="button"
                        @click="applyCoupon()"
                        :disabled="couponLoading || !couponCode.trim()"
                        class="rounded-xl bg-[#173327] px-4 py-2.5 text-xs font-extrabold text-white disabled:opacity-50"
                    >
                        <span x-show="!couponLoading">{{ __('Apply') }}</span>
                        <span x-show="couponLoading">{{ __('Checking...') }}</span>
                    </button>
                </div>

                <template x-if="couponApplied">
                    <div class="mt-3 rounded-xl border border-green-100 bg-green-50 px-3 py-2.5">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-extrabold text-green-800">
                                    {{ __('Code applied') }}:
                                    <span x-text="appliedCode"></span>
                                </p>
                                <p class="mt-0.5 text-[10px] text-green-700">
                                    {{ __('You save') }}
                                    <span x-text="'SAR ' + formatMoney(discountAmount)"></span>
                                </p>
                            </div>
                            <button type="button" @click="removeCoupon()" class="text-[10px] font-extrabold text-red-600">
                                {{ __('Remove') }}
                            </button>
                        </div>
                    </div>
                </template>

                <p x-show="couponError" x-text="couponError" class="mt-2 text-xs font-semibold text-red-600"></p>
            </div>

            {{-- Price breakdown --}}
            <div class="mb-5 space-y-2 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 text-xs">
                <div class="flex justify-between text-gray-500">
                    <span>{{ __('Original amount') }}</span>
                    <span>SAR <span x-text="formatMoney(originalAmount)"></span></span>
                </div>
                <div x-show="couponApplied" class="flex justify-between font-semibold text-green-700">
                    <span>{{ __('Discount') }}</span>
                    <span>- SAR <span x-text="formatMoney(discountAmount)"></span></span>
                </div>
                <div class="flex justify-between border-t border-gray-200 pt-2 font-extrabold text-gray-900">
                    <span>{{ __('Amount to pay') }}</span>
                    <span>SAR <span x-text="formatMoney((checkout.amount || 0) / 100)"></span></span>
                </div>
            </div>

            <div class="mysr-form" id="moyasar-form-container"></div>

            <div id="moyasar-loading" class="py-8 text-center">
                <svg class="mx-auto h-8 w-8 animate-spin text-[#6E7A25]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <p class="mt-2 text-xs text-gray-500">{{ __('Loading payment form...') }}</p>
            </div>

            <div id="moyasar-error" class="mt-4 hidden rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

            <div class="mt-4 flex items-center justify-center gap-1.5 text-[10px] text-gray-400">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                {{ __('Secured by Moyasar') }} · {{ __('256-bit SSL encryption') }}
            </div>

            <a href="{{ route('user.subscriptions') }}" class="mt-4 flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 py-2.5 text-sm font-bold text-gray-700 transition hover:bg-gray-50">
                {{ __('Back to Subscriptions') }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moyasar-payment-form@2.2.9/dist/moyasar.umd.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/moyasar-payment-form@2.2.9/dist/moyasar.css">

<style>
    .mysr-form { min-height: 200px; }
    .mysr-form .mysr-btn {
        background: linear-gradient(to right, #173327, #6E7A25) !important;
        border-radius: .5rem !important;
        font-weight: 700 !important;
    }
    .mysr-form .mysr-input {
        border-radius: .5rem !important;
        border-color: #e5e7eb !important;
    }
</style>

<script>
function paymentCheckout(initialCheckout, subscriptionId) {
    return {
        checkout: initialCheckout || {},
        subscriptionId,
        originalAmount: Number(@json($initialAmountSar)),
        couponCode: '',
        appliedCode: '',
        couponApplied: false,
        couponLoading: false,
        couponError: '',
        discountAmount: 0,

        init() {
            this.$nextTick(() => this.renderMoyasar());
        },

        formatMoney(value) {
            return Number(value || 0).toFixed(2);
        },

        async applyCoupon() {
            const code = String(this.couponCode || '').trim().toUpperCase();

            if (!code || this.couponLoading) {
                return;
            }

            this.couponLoading = true;
            this.couponError = '';

            try {
                const url = @js(route('user.subscriptions.checkout', ['subscriptionId' => '__SUB__']))
                    .replace('__SUB__', this.subscriptionId);

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        coupon_code: code,
                    }),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok || data.success === false || !data.checkout) {
                    throw new Error(
                        data.message
                        || data.detail
                        || '{{ __('This discount code cannot be applied.') }}'
                    );
                }

                const newCheckout = data.checkout;
                const finalAmount = Number(newCheckout.amount || 0) / 100;

                this.checkout = newCheckout;
                this.appliedCode = code;
                this.couponCode = code;
                this.couponApplied = true;
                this.discountAmount = Math.max(
                    this.originalAmount - finalAmount,
                    0
                );

                this.$nextTick(() => this.renderMoyasar());

            } catch (error) {
                this.couponApplied = false;
                this.appliedCode = '';
                this.discountAmount = 0;
                this.couponError = error?.message
                    || '{{ __('Unable to apply discount code.') }}';
            } finally {
                this.couponLoading = false;
            }
        },

        async removeCoupon() {
            if (this.couponLoading) {
                return;
            }

            this.couponLoading = true;
            this.couponError = '';

            try {
                const url = @js(route('user.subscriptions.checkout', ['subscriptionId' => '__SUB__']))
                    .replace('__SUB__', this.subscriptionId);

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @js(csrf_token()),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok || data.success === false || !data.checkout) {
                    throw new Error(
                        data.message
                        || '{{ __('Unable to remove discount code.') }}'
                    );
                }

                this.checkout = data.checkout;
                this.couponCode = '';
                this.appliedCode = '';
                this.couponApplied = false;
                this.discountAmount = 0;

                this.$nextTick(() => this.renderMoyasar());

            } catch (error) {
                this.couponError = error?.message
                    || '{{ __('Unable to remove discount code.') }}';
            } finally {
                this.couponLoading = false;
            }
        },

        renderMoyasar() {
            const loadingEl = document.getElementById('moyasar-loading');
            const errorEl = document.getElementById('moyasar-error');
            const formContainer = document.getElementById('moyasar-form-container');

            if (!loadingEl || !errorEl || !formContainer) {
                return;
            }

            formContainer.innerHTML = '';
            formContainer.style.display = '';
            errorEl.classList.add('hidden');
            loadingEl.classList.remove('hidden');

            if (typeof Moyasar === 'undefined') {
                loadingEl.classList.add('hidden');
                errorEl.textContent = '{{ __('Payment SDK failed to load. Please refresh and try again.') }}';
                errorEl.classList.remove('hidden');
                return;
            }

            const checkout = this.checkout;
            const localPaymentId = checkout.payment_id;
            const callbackUrl = (checkout.callback_url || '')
                + (
                    checkout.callback_url
                    && checkout.callback_url.includes('?')
                        ? '&'
                        : '?'
                )
                + 'payment_id='
                + localPaymentId;

            try {
                Moyasar.init({
                    element: '#moyasar-form-container',
                    amount: checkout.amount || 0,
                    currency: checkout.currency || 'SAR',
                    description: checkout.description || 'Subscription Payment',
                    publishable_api_key: checkout.publishable_api_key,
                    callback_url: callbackUrl,
                    supported_networks: checkout.supported_networks || ['mada', 'visa', 'mastercard'],
                    methods: checkout.methods || ['creditcard'],
                    metadata: checkout.metadata || {},
                    language: document.documentElement.lang || 'en',

                    on_completed: async (payment) => {
                        const status = String(payment?.status || '').toLowerCase();
                        const moyasarPaymentUuid = payment?.id || '';

                        if (['failed', 'voided', 'canceled', 'cancelled'].includes(status)) {
                            loadingEl.classList.add('hidden');
                            formContainer.style.display = '';
                            errorEl.textContent =
                                payment?.source?.message
                                || payment?.source?.code
                                || '{{ __('Payment was declined. Please try another card.') }}';
                            errorEl.classList.remove('hidden');
                            return;
                        }

                        loadingEl.classList.remove('hidden');
                        formContainer.style.display = 'none';

                        if (moyasarPaymentUuid && localPaymentId) {
                            const attachUrl = @js(route('user.payments.attach-moyasar', ['paymentId' => '__PID__']))
                                .replace('__PID__', localPaymentId);

                            try {
                                await fetch(attachUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': @js(csrf_token()),
                                    },
                                    credentials: 'same-origin',
                                    body: JSON.stringify({
                                        moyasar_payment_id: moyasarPaymentUuid,
                                    }),
                                });
                            } catch (error) {
                                console.warn('Moyasar attach failed', error);
                            }
                        }

                        const successUrl =
                            @js(route('payment.success'))
                            + '?payment_id=' + localPaymentId
                            + '&id=' + encodeURIComponent(moyasarPaymentUuid);

                        window.location.href = successUrl;
                    },

                    on_redirect: () => {
                        loadingEl.classList.remove('hidden');
                        formContainer.style.display = 'none';
                    },

                    on_failure: (error) => {
                        loadingEl.classList.add('hidden');
                        formContainer.style.display = '';
                        errorEl.textContent =
                            error?.message
                            || '{{ __('Payment form error. Please try again.') }}';
                        errorEl.classList.remove('hidden');
                    },
                });

                loadingEl.classList.add('hidden');

            } catch (error) {
                loadingEl.classList.add('hidden');
                errorEl.textContent = '{{ __('Failed to load payment form. Please try again.') }}';
                errorEl.classList.remove('hidden');
                console.error('Moyasar init error', error);
            }
        },
    };
}
</script>
@endpush
@endsection
