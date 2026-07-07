@extends('layouts.auth')

@section('title', __('Verify Email') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-lg animate-simple-fade-in" x-data="verifyForm()">
    <div class="bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
        {{-- Header --}}
        <div class="relative bg-gradient-to-br from-emerald-700 to-emerald-900 px-8 py-10 text-center overflow-hidden">
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-32 h-32 bg-emerald-400/10 rounded-full blur-2xl"></div>

            <div class="relative z-10 mx-auto w-20 h-20 bg-white/10 backdrop-blur rounded-full flex items-center justify-center mb-4 ring-4 ring-white/10 shadow-xl">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
            </div>
            <h2 class="relative z-10 text-2xl font-extrabold text-white">{{ __('Verify Your Email') }}</h2>
            <p class="relative z-10 text-emerald-100 text-sm mt-1">{{ __('One more step to get started') }}</p>
        </div>

        {{-- Content --}}
        <div class="p-8 sm:p-10">

            {{-- Toast Notification --}}
            <div x-show="toast.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed top-5 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full px-4" x-cloak>
                <div class="rounded-xl border shadow-xl p-4 flex items-start gap-3"
                     :class="toast.type === 'success' ? 'border-emerald-200 bg-white' : 'border-red-200 bg-white'">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                         :class="toast.type === 'success' ? 'bg-emerald-100' : 'bg-red-100'">
                        <svg x-show="toast.type === 'success'" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <svg x-show="toast.type !== 'success'" class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900" x-text="toast.title"></p>
                        <p class="text-sm text-gray-600 mt-0.5 break-words" x-text="toast.message"></p>
                    </div>
                    <button @click="toast.show = false" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0" aria-label="Close">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="text-center mb-8">
                <p class="text-gray-600 mb-2">{{ __('Enter the 6-digit code sent to your email.') }}</p>
                <p class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-full" x-text="email"></p>
            </div>

            <form class="space-y-6" method="POST" action="{{ route('verify.email.verify') }}" @submit.prevent="verify">
                @csrf
                <input type="hidden" name="email" x-model="email" value="{{ $email ?? old('email') ?? '' }}">

                {{-- 6-digit OTP inputs --}}
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-3 text-center tracking-wide uppercase">{{ __('Verification Code') }}</label>
                    <div class="flex justify-center gap-2 sm:gap-3" @paste="handlePaste($event)">
                        <template x-for="(digit, index) in otpDigits" :key="index">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                class="otp-input w-11 h-13 sm:w-13 sm:h-14 text-center text-2xl font-bold rounded-xl border-2 bg-gray-50 outline-none transition-all duration-200 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                :class="error ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200'"
                                x-model="otpDigits[index]"
                                @input="handleInput(index, $event)"
                                @keydown.backspace="handleBackspace(index, $event)"
                                @keydown.left="focusPrev(index)"
                                @keydown.right="focusNext(index)"
                                :aria-label="'Digit ' + (index + 1)">
                        </template>
                    </div>
                    <input type="hidden" name="otp" x-model="otp">
                    <div x-show="error && error.length > 0" x-transition class="mt-4 flex items-center justify-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg py-2 px-3" x-cloak>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="error"></span>
                    </div>
                </div>

                <button type="submit" :disabled="loadingVerify || otp.length !== 6"
                    class="w-full py-3.5 text-sm font-bold text-white rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed hover:-translate-y-0.5 hover:shadow-xl"
                    :class="loadingVerify ? 'bg-gray-400' : 'bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600'">
                    <svg x-show="!loadingVerify" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="loadingVerify" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loadingVerify ? pleaseWait : verifyText"></span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm mb-2">{{ __('Didn\'t receive the code?') }}</p>
                <form method="POST" action="{{ route('verification.resend') }}" @submit.prevent="resend">
                    @csrf
                    <input type="hidden" name="email" x-model="email" value="{{ $email ?? old('email') ?? '' }}">
                    <button type="submit" :disabled="loadingResend"
                        class="inline-flex items-center gap-2 text-sm font-semibold transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
                        :class="loadingResend ? 'text-gray-400' : 'text-emerald-600 hover:text-emerald-700'">
                        <svg x-show="!loadingResend" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <svg x-show="loadingResend" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="loadingResend ? pleaseWait : resendText"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-white/60">&copy; {{ date('Y') }} {{ config('app.name', 'Nutrio Meals') }}. All rights reserved.</p>
</div>

@push('scripts')
<script>
    function verifyForm() {
        return {
            email: @json($email ?? old('email') ?? ''),
            otpDigits: ['', '', '', '', '', ''],
            loadingVerify: false,
            loadingResend: false,
            error: '',
            toast: { show: false, message: '', type: 'error', title: '' },
            pleaseWait: @json(__('Please wait...')),
            successTitle: @json(__('Success')),
            errorTitle: @json(__('Verification failed')),
            networkError: @json(__('Network error. Please try again.')),
            verifyUrl: @json(route('verify.email.verify')),
            resendUrl: @json(route('verification.resend')),
            verifyText: @json(__('Verify Email')),
            resendText: @json(__('Resend OTP')),
            invalidOtp: @json(__('Please enter the 6-digit code.')),
            resendFailed: @json(__('Failed to resend OTP.')),
            invalidOtpMessage: @json(__('Invalid or expired OTP.')),
            get otp() {
                return this.otpDigits.join('');
            },
            showToast(message, type = 'error') {
                this.toast = {
                    show: true,
                    message: message,
                    type: type,
                    title: type === 'success' ? this.successTitle : this.errorTitle
                };
                setTimeout(() => { this.toast.show = false }, 7000);
            },
            focusInput(index) {
                const inputs = document.querySelectorAll('.otp-input');
                if (inputs[index]) inputs[index].focus();
            },
            focusNext(index) {
                if (index < 5) this.focusInput(index + 1);
            },
            focusPrev(index) {
                if (index > 0) this.focusInput(index - 1);
            },
            handleInput(index, event) {
                const value = event.target.value;
                if (!/^\d*$/.test(value)) {
                    this.otpDigits[index] = '';
                    return;
                }
                if (value.length > 1) {
                    const digits = value.replace(/\D/g, '').split('');
                    for (let i = 0; i < digits.length && index + i < 6; i++) {
                        this.otpDigits[index + i] = digits[i];
                    }
                    this.focusInput(Math.min(index + digits.length, 5));
                    return;
                }
                this.otpDigits[index] = value.slice(-1);
                if (value && index < 5) {
                    this.focusNext(index);
                }
            },
            handleBackspace(index, event) {
                if (!this.otpDigits[index] && index > 0) {
                    this.focusPrev(index);
                }
            },
            handlePaste(event) {
                event.preventDefault();
                const pasted = (event.clipboardData || window.clipboardData).getData('text');
                const digits = pasted.replace(/\D/g, '').split('').slice(0, 6);
                for (let i = 0; i < 6; i++) {
                    this.otpDigits[i] = digits[i] || '';
                }
                this.focusInput(Math.min(digits.length, 5));
            },
            async verify() {
                if (this.otp.length !== 6) {
                    this.error = this.invalidOtp;
                    this.showToast(this.error);
                    return;
                }

                this.loadingVerify = true;
                this.error = '';
                this.toast.show = false;

                try {
                    const response = await fetch(this.verifyUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: this.email, otp: this.otp })
                    });

                    const data = await response.json();
                    this.loadingVerify = false;

                    if (data.success) {
                        this.showToast(data.message, 'success');
                        setTimeout(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        }, data.already_verified ? 2000 : 1500);
                        return;
                    }

                    this.error = data.message || this.invalidOtpMessage;
                    this.showToast(this.error);
                } catch (error) {
                    this.loadingVerify = false;
                    this.error = error.message || this.networkError;
                    this.showToast(this.error);
                }
            },
            async resend() {
                this.loadingResend = true;
                this.error = '';
                this.toast.show = false;

                try {
                    const response = await fetch(this.resendUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: this.email })
                    });

                    const data = await response.json();
                    this.loadingResend = false;

                    if (data.success) {
                        this.showToast(data.message, 'success');
                        if (data.already_verified && data.redirect) {
                            setTimeout(() => { window.location.href = data.redirect }, 2000);
                        }
                        return;
                    }

                    this.error = data.message || this.resendFailed;
                    this.showToast(this.error);
                } catch (error) {
                    this.loadingResend = false;
                    this.error = error.message || this.networkError;
                    this.showToast(this.error);
                }
            }
        };
    }
</script>
@endpush
@endsection
