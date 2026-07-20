@extends('layouts.auth')

@section('title', __('Payment Failed') . ' - ' . __('Nutrio Meals'))

@php
    $declineReason = $declineReason ?? '';
    $status = $status ?? 'failed';
    $isDeclined = in_array($status, ['failed', 'declined']);
    $isCancelled = in_array($status, ['cancelled', 'canceled']);
@endphp

@section('content')
<div class="w-full max-w-md animate-simple-fade-in" x-data="paymentCancel()">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden text-center relative">
        {{-- Top accent bar --}}
        <div class="h-2 w-full bg-gradient-to-r from-red-500 via-red-400 to-red-500"></div>

        <div class="px-8 py-10">
            {{-- Icon --}}
            <div class="mx-auto w-24 h-24 rounded-full flex items-center justify-center mb-6 shadow-inner
                {{ $isCancelled ? 'bg-amber-100' : 'bg-red-100' }}">
                @if($isCancelled)
                <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @else
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                @endif
            </div>

            {{-- Title --}}
            <h2 class="text-2xl font-extrabold text-gray-900">
                @if($isCancelled)
                {{ __('Payment Cancelled') }}
                @else
                {{ __('Payment Failed') }}
                @endif
            </h2>

            {{-- Subtitle --}}
            <p class="text-gray-500 text-sm mt-2">
                @if($isCancelled)
                {{ __('No worries. You can review your plan and try again whenever you are ready.') }}
                @else
                {{ __('Your payment could not be processed. Please check your card details and try again.') }}
                @endif
            </p>

            {{-- Decline reason box --}}
            @if($declineReason)
            <div class="mt-6 bg-red-50 border border-red-100 rounded-xl p-4 text-left">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-red-700 uppercase tracking-wide">{{ __('Decline Reason') }}</p>
                        <p class="text-sm text-red-900 mt-1 font-medium break-words">{{ $declineReason }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Payment reference --}}
            @if($paymentId)
            <div class="mt-4 bg-gray-50 rounded-xl p-4 text-left border border-gray-100">
                <div class="flex justify-between py-2">
                    <span class="text-xs text-gray-500">{{ __('Payment Reference') }}</span>
                    <span class="text-xs font-bold text-gray-900">#{{ $paymentId }}</span>
                </div>
                <div class="flex justify-between py-2 border-t border-gray-100">
                    <span class="text-xs text-gray-500">{{ __('Status') }}</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide
                        {{ $isCancelled ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700' }}">
                        {{ $status }}
                    </span>
                </div>
            </div>
            @endif

            {{-- Tips for declined cards --}}
            @if($isDeclined)
            <div class="mt-6 bg-gradient-to-br from-[#173327]/5 to-[#6E7A25]/5 rounded-xl p-4 text-left border border-[#6E7A25]/10">
                <p class="text-xs font-bold text-[#173327] mb-2">{{ __('Quick Tips') }}</p>
                <ul class="space-y-1.5">
                    <li class="flex items-start gap-2 text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 text-[#6E7A25] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Check if your card is valid and has sufficient funds.') }}
                    </li>
                    <li class="flex items-start gap-2 text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 text-[#6E7A25] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Make sure 3D Secure authentication is enabled on your card.') }}
                    </li>
                    <li class="flex items-start gap-2 text-xs text-gray-600">
                        <svg class="w-3.5 h-3.5 text-[#6E7A25] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Try a different card or contact your bank if the issue persists.') }}
                    </li>
                </ul>
            </div>
            @endif

            {{-- Actions --}}
            <div class="mt-8 space-y-3">
                <a href="{{ route('user.subscriptions') }}" class="inline-flex items-center justify-center gap-2 w-full py-3.5 text-sm font-bold text-white rounded-xl shadow-md bg-gradient-to-r from-[#173327] to-[#6E7A25] hover:from-[#6E7A25] hover:to-[#173327] transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('Try Again') }}
                </a>
                <a href="{{ route('landing') }}" class="inline-flex items-center justify-center gap-2 w-full py-3.5 text-sm font-bold text-gray-700 rounded-xl border border-gray-200 hover:bg-gray-50 transition-all">
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
