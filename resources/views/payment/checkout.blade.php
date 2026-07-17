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
            <a href="{{ route('user.subscriptions') }}" class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 text-sm font-bold text-gray-700 border border-gray-200 rounded-lg hover:bg-gray-50 transition-all">
                {{ __('Back to Subscriptions') }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.moyasar.com/mpf/0.3.0/moyasar.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkout = @json($checkout);
        const loadingEl = document.getElementById('moyasar-loading');
        const errorEl = document.getElementById('moyasar-error');
        const localPaymentId = checkout.payment_id;
        const callbackUrl = (checkout.callback_url || '') + (checkout.callback_url && checkout.callback_url.includes('?') ? '&' : '?') + 'payment_id=' + localPaymentId;

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
                on_completed: function(payment) {
                    loadingEl.classList.remove('hidden');
                },
                on_failure: function(error) {
                    loadingEl.classList.add('hidden');
                    errorEl.textContent = (error && error.message) || 'Payment failed. Please try again.';
                    errorEl.classList.remove('hidden');
                },
            });
            loadingEl.classList.add('hidden');
        } catch (err) {
            loadingEl.classList.add('hidden');
            errorEl.textContent = 'Failed to load payment form. Please try again.';
            errorEl.classList.remove('hidden');
        }
    });
</script>
@endpush
@endsection
