@php
$isLoggedIn = $isLoggedIn ?? !empty(session('api_user')['id'] ?? null);
$nextUrl = $isLoggedIn ? route('user.subscriptions') : route('register');
$status = $verified ? 'paid' : ($error ? 'error' : 'pending');
@endphp

@extends('layouts.auth')

@section('title', __('Payment Status') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-lg animate-simple-fade-in" x-data="paymentStatus()">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden text-center relative">
        {{-- Top gradient accent bar --}}
        <div class="h-3 w-full bg-gradient-to-r from-[#173327] via-[#6E7A25] to-[#173327]"></div>

        @if($verified)
        {{-- Success gradient header --}}
        <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] px-6 py-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>
            <div class="relative z-10">
                <div class="mx-auto w-24 h-24 rounded-full bg-white/15 backdrop-blur flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-white">{{ __('Payment Successful!') }}</h2>
                @if($isLoggedIn)
                <p class="text-white/70 text-sm mt-2 max-w-xs mx-auto">{{ __('Your subscription is now active and your meals are being prepared.') }}</p>
                @else
                <p class="text-white/70 text-sm mt-2 max-w-xs mx-auto">{{ __('Thank you for your payment. Create an account to manage your subscription and meals.') }}</p>
                @endif
            </div>
        </div>

        @elseif($error)
        {{-- Error gradient header --}}
        <div class="bg-gradient-to-br from-red-500 to-red-700 px-6 py-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <div class="mx-auto w-24 h-24 rounded-full bg-white/15 backdrop-blur flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-white">{{ __('Payment Not Confirmed') }}</h2>
                <p class="text-white/70 text-sm mt-2 max-w-xs mx-auto">{{ $error }}</p>
            </div>
        </div>

        @else
        {{-- Pending gradient header --}}
        <div class="bg-gradient-to-br from-amber-500 to-amber-700 px-6 py-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <div class="mx-auto w-24 h-24 rounded-full bg-white/15 backdrop-blur flex items-center justify-center mb-4 shadow-lg">
                    <svg class="w-12 h-12 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-white">{{ __('Payment Pending') }}</h2>
                <p class="text-white/70 text-sm mt-2 max-w-xs mx-auto">{{ __('We are confirming your payment with the gateway. It may take a few moments to reflect.') }}</p>
            </div>
        </div>
        @endif

        <div class="px-6 sm:px-8 py-8">
            @if(!empty($payment['id']))
            <div class="bg-gray-50 rounded-xl p-5 text-left border border-gray-100">
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Payment ID') }}</span>
                    <span class="text-xs font-bold text-gray-900">#{{ $payment['id'] }}</span>
                </div>
                @if(!empty($payment['provider_payment_id']))
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Gateway Payment ID') }}</span>
                    <span class="text-xs font-mono font-bold text-gray-900 truncate max-w-[140px]" title="{{ $payment['provider_payment_id'] }}">{{ $payment['provider_payment_id'] }}</span>
                </div>
                @endif
                @if(!empty($payment['provider_reference']))
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Gateway Reference') }}</span>
                    <span class="text-xs font-mono font-bold text-gray-900 truncate max-w-[140px]" title="{{ $payment['provider_reference'] }}">{{ $payment['provider_reference'] }}</span>
                </div>
                @endif
                @if(!empty($payment['tap_charge_id']))
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Gateway Charge ID') }}</span>
                    <span class="text-xs font-mono font-bold text-gray-900 truncate max-w-[140px]" title="{{ $payment['tap_charge_id'] }}">{{ $payment['tap_charge_id'] }}</span>
                </div>
                @endif
                @if(!empty($payment['amount']))
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Amount') }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ strtoupper($payment['currency'] ?? 'SAR') }} {{ number_format($payment['amount'], 2) }}</span>
                </div>
                @endif
                @if(!empty($payment['subscription_id']))
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Subscription') }}</span>
                    <span class="text-xs font-bold text-gray-900">#{{ $payment['subscription_id'] }}</span>
                </div>
                @endif
                @if(!empty($payment['plan_change_id']))
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Plan Change') }}</span>
                    <span class="text-xs font-bold text-gray-900">#{{ $payment['plan_change_id'] }}</span>
                </div>
                @endif
                @if(!empty($payment['paid_at']))
                <div class="flex justify-between py-2.5 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Paid At') }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ date('M d, Y H:i', strtotime($payment['paid_at'])) }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2.5">
                    <span class="text-xs text-gray-500">{{ __('Status') }}</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                        {{ $verified ? 'bg-emerald-100 text-emerald-700' : ($error ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ $payment['status'] ?? 'pending' }}
                    </span>
                </div>
            </div>
            @endif

            @if($verified && $isLoggedIn)
            {{-- Next steps banner --}}
            <div class="mt-6 bg-gradient-to-br from-[#173327]/5 to-[#6E7A25]/5 rounded-xl p-4 text-left border border-[#6E7A25]/10">
                <p class="text-xs font-bold text-[#173327] mb-2">{{ __('What happens next?') }}</p>
                <ul class="space-y-1.5">
                    <li class="flex items-start gap-2 text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 text-[#6E7A25] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Your subscription is now active.') }}
                    </li>
                    <li class="flex items-start gap-2 text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 text-[#6E7A25] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Browse your weekly meal schedule and customize your preferences.') }}
                    </li>
                    <li class="flex items-start gap-2 text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 text-[#6E7A25] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Your meals will be freshly prepared and delivered to your doorstep.') }}
                    </li>
                </ul>
            </div>
            @endif

            <div class="mt-8 space-y-3">
                @if($status === 'pending' && ($chargeId || !empty($moyasarPaymentId)))
                <p class="text-xs text-gray-400">
                    {{ __('Rechecking in') }} <span x-text="countdown" class="font-bold text-gray-600"></span> {{ __('seconds') }}
                </p>
                <button type="button" @click="window.location.reload()" class="inline-flex items-center justify-center gap-2 w-full py-3.5 text-sm font-bold text-white rounded-xl shadow-md bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#6E7A25] hover:to-[#173327] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('Check Payment Status') }}
                </button>
                @else
                <p class="text-xs text-gray-400">
                    {{ __('Redirecting in') }} <span x-text="countdown" class="font-bold text-gray-600"></span> {{ __('seconds') }}
                </p>
                <a href="{{ $nextUrl }}" class="inline-flex items-center justify-center gap-2 w-full py-3.5 text-sm font-bold text-white rounded-xl shadow-md bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#6E7A25] hover:to-[#173327] transition-all">
                    @if($isLoggedIn)
                    {{ __('Go to My Subscriptions') }}
                    @else
                    {{ __('Create Account') }}
                    @endif
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                @endif

                @if(!$isLoggedIn)
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 w-full py-3.5 text-sm font-bold text-[#6E7A25] border border-[#6E7A25]/30 rounded-xl hover:bg-[#6E7A25]/5 transition-all">
                    {{ __('Already have an account? Login') }}
                </a>
                @endif

                @if($error)
                <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center gap-2 w-full py-3.5 text-sm font-bold text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all">
                    {{ __('Back to Subscriptions') }}
                </a>
                @endif
            </div>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-gray-300">&copy; {{ date('Y') }} {{ config('app.name', 'Nutrio Meals') }}. All rights reserved.</p>
</div>

@push('scripts')
<script>
    function paymentStatus() {
        return {
            countdown: 7,
            init() {
                const timer = setInterval(() => {
                    this.countdown--;
                    if (this.countdown <= 0) {
                        clearInterval(timer);
                        @if($status === 'pending' && ($chargeId || !empty($moyasarPaymentId)))
                            window.location.reload();
                        @else
                            window.location.href = @json($nextUrl);
                        @endif
                    }
                }, 1000);
            }
        };
    }
</script>
@endpush
@endsection
