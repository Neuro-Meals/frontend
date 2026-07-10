@php
$isLoggedIn = $isLoggedIn ?? !empty(session('api_user')['id'] ?? null);
$nextUrl = $isLoggedIn ? route('user.subscriptions') : route('register');
@endphp

@extends('layouts.auth')

@section('title', __('Payment Status') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in" x-data="paymentStatus()">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden text-center">
        <div class="px-6 sm:px-8 py-10">
            @if($verified)
            <div class="mx-auto w-20 h-20 rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 flex items-center justify-center mb-6 animate-bounce">
                <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Payment Successful!') }}</h2>
            @if($isLoggedIn)
            <p class="text-gray-500 text-sm mt-2">{{ __('Thank you. Your subscription is now active and ready.') }}</p>
            @else
            <p class="text-gray-500 text-sm mt-2">{{ __('Thank you for your payment. Create an account to manage your subscription.') }}</p>
            @endif
            @elseif($error)
            <div class="mx-auto w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Payment Confirmation Failed') }}</h2>
            <p class="text-gray-500 text-sm mt-2">{{ $error }}</p>
            @else
            <div class="mx-auto w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Payment Pending') }}</h2>
            <p class="text-gray-500 text-sm mt-2">{{ __('We are confirming your payment. It may take a moment to reflect.') }}</p>
            @endif

            @if(!empty($payment['id']))
            <div class="mt-6 bg-gray-50 rounded-xl p-4 text-left">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Payment ID') }}</span>
                    <span class="text-xs font-bold text-gray-900">#{{ $payment['id'] }}</span>
                </div>
                @if(!empty($payment['amount']))
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Amount') }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ strtoupper($payment['currency'] ?? 'USD') }} {{ number_format($payment['amount'], 2) }}</span>
                </div>
                @endif
                @if(!empty($payment['subscription_id']))
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Subscription') }}</span>
                    <span class="text-xs font-bold text-gray-900">#{{ $payment['subscription_id'] }}</span>
                </div>
                @endif
                @if(!empty($payment['paid_at']))
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Paid At') }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ date('M d, Y H:i', strtotime($payment['paid_at'])) }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2">
                    <span class="text-xs text-gray-500">{{ __('Status') }}</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold
                        {{ $verified ? 'bg-emerald-100 text-emerald-700' : ($error ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ ucfirst($payment['status'] ?? 'pending') }}
                    </span>
                </div>
            </div>
            @endif

            <div class="mt-8 space-y-3">
                <p class="text-xs text-gray-400">
                    {{ __('Redirecting in') }} <span x-text="countdown" class="font-bold text-gray-600"></span> {{ __('seconds') }}
                </p>
                <a href="{{ $nextUrl }}" class="inline-flex items-center justify-center gap-2 w-full py-3 text-sm font-bold text-white rounded-lg shadow-md bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#6E7A25] hover:to-[#173327] transition-all">
                    @if($isLoggedIn)
                    {{ __('Go to My Subscriptions') }}
                    @else
                    {{ __('Create Account') }}
                    @endif
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                @if(!$isLoggedIn)
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 w-full py-3 text-sm font-bold text-[#6E7A25] border border-[#6E7A25]/30 rounded-lg hover:bg-[#6E7A25]/5 transition-all">
                    {{ __('Already have an account? Login') }}
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
                        window.location.href = @json($nextUrl);
                    }
                }, 1000);
            }
        };
    }
</script>
@endpush
@endsection
