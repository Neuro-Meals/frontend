@extends('layouts.auth')

@section('title', __('Payment') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
        <div class="h-2 w-full bg-gradient-to-r from-[#173327] via-[#6E7A25] to-[#173327]"></div>
        <div class="p-6">
            <div class="text-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">{{ __('Complete Payment') }}</h2>
                @php $amountSar = ($checkout['amount'] ?? 0) / 100; @endphp
                <p class="text-2xl font-bold text-[#173327] mt-2">{{ $checkout['currency'] ?? 'SAR' }} {{ number_format($amountSar, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $checkout['description'] ?? '' }}</p>
            </div>
            <div class="mysr-form" id="moyasar-form-container"></div>
            <div id="moyasar-loading" class="text-center py-8">
                <svg class="w-8 h-8 text-[#6E7A25] animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <p class="text-xs text-gray-500 mt-2">{{ __('Loading payment form...') }}</p>
            </div>
            <div id="moyasar-error" class="hidden mt-4 bg-red-50 border border-red-100 text-red-700 rounded-xl px-4 py-3 text-sm"></div>
            <div class="mt-4 flex items-center justify-center gap-1.5 text-[10px] text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                {{ __('Secured by Moyasar') }} &middot; {{ __('256-bit SSL encryption') }}
            </div>
            <a href="{{ route('user.subscriptions') }}" class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 text-sm font-bold text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                {{ __('Back to Subscriptions') }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/moyasar-payment-form@2.2.9/dist/moyasar.umd.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/moyasar-payment-form@2.2.9/dist/moyasar.css">
<script>
    // Fallback: load from official CDN if jsDelivr fails
    if (typeof Moyasar === 'undefined') {
        document.write('<script src="https://cdn.moyasar.com/mpf/1.0.0/moyasar.min.js"><\/script>');
    }
</script>
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
    document.addEventListener('DOMContentLoaded', function() {
        const checkout = @json($checkout);
        const loadingEl = document.getElementById('moyasar-loading');
        const errorEl = document.getElementById('moyasar-error');
        const formContainer = document.getElementById('moyasar-form-container');
        const localPaymentId = checkout.payment_id;
        const callbackUrl = (checkout.callback_url || '') + (checkout.callback_url && checkout.callback_url.includes('?') ? '&' : '?') + 'payment_id=' + localPaymentId;

        if (typeof Moyasar === 'undefined') {
            loadingEl.classList.add('hidden');
            errorEl.textContent = 'Payment SDK failed to load. Please refresh the page and try again.';
            errorEl.classList.remove('hidden');
            return;
        }

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
                        if (loadingText) loadingText.textContent = '{{ __('Confirming payment...') }}';

                        if (moyasarPaymentUuid && localPaymentId) {
                            const attachUrl = '{{ route("user.payments.attach-moyasar", ["paymentId" => "__PID__"]) }}'.replace('__PID__', localPaymentId);
                            try {
                                const attachResponse = await fetch(attachUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                        if (loadingText) loadingText.textContent = '{{ __('Processing payment...') }}';
                        const successUrl = '{{ route("payment.success") }}' + '?payment_id=' + localPaymentId + (moyasarPaymentUuid ? '&id=' + moyasarPaymentUuid : '');
                        window.location.href = successUrl;
                    }
                },
                on_redirect: async function(url) {
                    loadingEl.classList.remove('hidden');
                    formContainer.style.display = 'none';
                    const loadingText = loadingEl.querySelector('p');
                    if (loadingText) loadingText.textContent = '{{ __('Redirecting to your bank for verification...') }}';
                },
                on_failure: async function(error) {
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
    });
</script>
@endpush
@endsection
