@extends('layouts.auth')

@section('title', __('Payment Cancelled') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in" x-data="paymentCancel()">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden text-center">
        <div class="px-8 py-10">
            <div class="mx-auto w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Payment Cancelled') }}</h2>
            <p class="text-gray-500 text-sm mt-2">{{ __('No worries. You can review your plan and try again whenever you are ready.') }}</p>

            @if($paymentId)
            <div class="mt-6 bg-gray-50 rounded-xl p-4 text-left">
                <div class="flex justify-between py-2">
                    <span class="text-xs text-gray-500">{{ __('Payment Reference') }}</span>
                    <span class="text-xs font-bold text-gray-900">#{{ $paymentId }}</span>
                </div>
            </div>
            @endif

            <div class="mt-8 space-y-3">
                <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center gap-2 w-full py-3 text-sm font-bold text-white rounded-lg shadow-md bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light transition-all">
                    {{ __('Try Again') }}
                </a>
                <a href="{{ route('landing') }}" class="inline-flex items-center justify-center gap-2 w-full py-3 text-sm font-bold text-gray-700 rounded-lg border border-gray-200 hover:bg-gray-50 transition-all">
                    {{ __('Back to Home') }}
                </a>
            </div>

            <p class="mt-4 text-xs text-gray-400">
                {{ __('Redirecting to subscriptions in') }} <span x-text="countdown" class="font-bold text-gray-600"></span> {{ __('seconds') }}
            </p>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-gray-300">&copy; {{ date('Y') }} {{ config('app.name', 'Nutrio Meals') }}. All rights reserved.</p>
</div>

@push('scripts')
<script>
    function paymentCancel() {
        return {
            countdown: 10,
            init() {
                const timer = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        clearInterval(timer);
                        window.location.href = @json(route('user.subscriptions'));
                    }
                }, 1000);
            }
        };
    }
</script>
@endpush
@endsection
