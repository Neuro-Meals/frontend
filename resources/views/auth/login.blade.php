@extends('layouts.auth')

@section('title', __('Login') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in" x-data="loginForm()">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white px-8 py-8 text-center border-b border-gray-100">
            <div class="mx-auto mb-4 flex items-center justify-center">
                <img src="{{ asset('whitelogo.png') }}" alt="{{ config('app.name', 'Nutrio Meals') }}" class="h-20 w-auto object-contain">
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Welcome Back') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('Sign in to your account') }}</p>
        </div>

        {{-- Form --}}
        <div class="p-8">

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

            <form class="space-y-5" method="POST" action="{{ route('login') }}" @submit.prevent="submit">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Email') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" x-model="form.email" value="{{ old('email') ?? session('verified_email') }}" required autocomplete="email" autofocus
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.email ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="you@example.com">
                    </div>
                    <p x-show="errors.email" x-text="errors.email" class="mt-1.5 text-sm text-red-600 flex items-center gap-1" x-cloak>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </p>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" x-model="form.password" required autocomplete="current-password"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.password ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="Enter your password">
                    </div>
                    <p x-show="errors.password" x-text="errors.password" class="mt-1.5 text-sm text-red-600 flex items-center gap-1" x-cloak>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </p>
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm text-gray-600">{{ __('Remember Me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors">{{ __('Forgot Your Password?') }}</a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="loading"
                    class="w-full py-3 text-sm font-bold text-white rounded-lg shadow-md transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    :class="loading ? 'bg-gray-400' : 'bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light hover:shadow-lg'">
                    <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? pleaseWait : loginText"></span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-400">or</span></div>
            </div>

            {{-- Register link --}}
            <p class="text-center text-sm text-gray-500">
                {{ __('Don\'t have an account?') }}
                <a href="{{ route('register') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">{{ __('Create Account') }}</a>
            </p>
        </div>
    </div>

    {{-- Email Verification Modal --}}
    <div x-show="verificationRequired" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="verificationRequired = false"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl border border-emerald-100 max-w-md w-full p-8 transform transition-all scale-100" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto bg-emerald-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                    </svg>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900">{{ __('Verify Your Email') }}</h3>
                <p class="text-gray-500 text-sm mt-1">{{ __('Enter the 6-digit code sent to') }}</p>
                <p class="inline-flex items-center gap-2 px-3 py-1 mt-2 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-full" x-text="verificationEmail"></p>
            </div>

            <form class="space-y-5" @submit.prevent="verifyOtp">
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-3 text-center tracking-wide uppercase">{{ __('Verification Code') }}</label>
                    <div class="flex justify-center gap-2 sm:gap-3" @paste="handleOtpPaste($event)">
                        <template x-for="(digit, index) in otpDigits" :key="index">
                            <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                class="otp-input w-11 h-13 sm:w-13 sm:h-14 text-center text-2xl font-bold rounded-xl border-2 bg-gray-50 outline-none transition-all duration-200 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-100"
                                :class="verificationError ? 'border-red-300 bg-red-50 focus:border-red-500 focus:ring-red-100' : 'border-gray-200'"
                                x-model="otpDigits[index]"
                                @input="handleOtpInput(index, $event)"
                                @keydown.backspace="handleOtpBackspace(index, $event)"
                                @keydown.left="focusOtpPrev(index)"
                                @keydown.right="focusOtpNext(index)">
                        </template>
                    </div>
                    <input type="hidden" x-model="otp">
                    <div x-show="verificationError && verificationError.length > 0" x-transition class="mt-3 flex items-center justify-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg py-2 px-3" x-cloak>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="verificationError"></span>
                    </div>
                </div>

                <button type="submit" :disabled="verifying || otp.length !== 6"
                    class="w-full py-3.5 text-sm font-bold text-white rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed hover:-translate-y-0.5 hover:shadow-xl"
                    :class="verifying ? 'bg-gray-400' : 'bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600'">
                    <svg x-show="!verifying" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="verifying" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="verifying ? pleaseWait : verifyText"></span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm mb-2">{{ __('Didn\'t receive the code?') }}</p>
                <button type="button" @click="resendOtp" :disabled="resending"
                    class="inline-flex items-center gap-2 text-sm font-semibold transition-colors disabled:opacity-60 disabled:cursor-not-allowed"
                    :class="resending ? 'text-gray-400' : 'text-emerald-600 hover:text-emerald-700'">
                    <svg x-show="!resending" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <svg x-show="resending" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="resending ? pleaseWait : resendText"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <p class="mt-6 text-center text-xs text-gray-300">&copy; {{ date('Y') }} {{ config('app.name', 'Nutrio Meals') }}. All rights reserved.</p>
</div>

@push('scripts')
<script>
    function loginForm() {
        return {
            loading: false,
            errors: {},
            toast: { show: false, message: '', type: 'error', title: '' },
            verificationRequired: false,
            verificationEmail: '',
            verifying: false,
            resending: false,
            verificationError: '',
            otpDigits: ['', '', '', '', '', ''],
            pleaseWait: @json(__('Please wait...')),
            loginText: @json(__('Login')),
            successTitle: @json(__('Success')),
            errorTitle: @json(__('Login failed')),
            verifyText: @json(__('Verify Email')),
            resendText: @json(__('Resend OTP')),
            networkError: @json(__('Network error. Please try again.')),
            invalidOtpMessage: @json(__('Please enter the 6-digit code.')),
            resendFailed: @json(__('Failed to resend OTP.')),
            loginUrl: @json(route('login')),
            verifyUrl: @json(route('verify.email.verify')),
            resendUrl: @json(route('verification.resend')),
            form: {
                email: @json(old('email') ?? session('verified_email') ?? ''),
                password: ''
            },
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
            async submit() {
                this.loading = true;
                this.errors = {};
                this.toast.show = false;

                try {
                    const response = await fetch(this.loginUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    });

                    const data = await response.json();
                    this.loading = false;

                    if (data.success) {
                        this.showToast(data.message || @json(__('Login successful. Redirecting to your dashboard...')), 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect || @json(route('user.dashboard'));
                        }, 1200);
                        return;
                    }

                    if (data.requires_verification) {
                        this.verificationEmail = data.email || this.form.email;
                        this.verificationRequired = true;
                        this.showToast(data.message || @json(__('Please verify your email to continue.')), 'error');
                        this.$nextTick(() => this.focusOtpInput(0));
                        return;
                    }

                    this.errors = data.errors || {};
                    const message = data.message || this.errorTitle;
                    this.showToast(message);
                } catch (error) {
                    this.loading = false;
                    this.showToast(error.message || this.networkError);
                }
            },
            focusOtpInput(index) {
                const inputs = document.querySelectorAll('.otp-input');
                if (inputs[index]) inputs[index].focus();
            },
            focusOtpNext(index) {
                if (index < 5) this.focusOtpInput(index + 1);
            },
            focusOtpPrev(index) {
                if (index > 0) this.focusOtpInput(index - 1);
            },
            handleOtpInput(index, event) {
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
                    this.focusOtpInput(Math.min(index + digits.length, 5));
                    return;
                }
                this.otpDigits[index] = value.slice(-1);
                if (value && index < 5) {
                    this.focusOtpNext(index);
                }
            },
            handleOtpBackspace(index, event) {
                if (!this.otpDigits[index] && index > 0) {
                    this.focusOtpPrev(index);
                }
            },
            handleOtpPaste(event) {
                event.preventDefault();
                const pasted = (event.clipboardData || window.clipboardData).getData('text');
                const digits = pasted.replace(/\D/g, '').split('').slice(0, 6);
                for (let i = 0; i < 6; i++) {
                    this.otpDigits[i] = digits[i] || '';
                }
                this.focusOtpInput(Math.min(digits.length, 5));
            },
            resetOtp() {
                this.otpDigits = ['', '', '', '', '', ''];
                this.verificationError = '';
            },
            async verifyOtp() {
                if (this.otp.length !== 6) {
                    this.verificationError = this.invalidOtpMessage;
                    this.showToast(this.invalidOtpMessage);
                    return;
                }

                this.verifying = true;
                this.verificationError = '';
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
                        body: JSON.stringify({ email: this.verificationEmail, otp: this.otp })
                    });

                    const data = await response.json();
                    this.verifying = false;

                    if (data.success) {
                        this.showToast(data.message || @json(__('Email verified successfully. Please log in.')), 'success');
                        this.verificationRequired = false;
                        this.resetOtp();
                        this.form.password = '';
                        return;
                    }

                    this.verificationError = data.message || this.invalidOtpMessage;
                    this.showToast(this.verificationError);
                } catch (error) {
                    this.verifying = false;
                    this.verificationError = error.message || this.networkError;
                    this.showToast(this.verificationError);
                }
            },
            async resendOtp() {
                this.resending = true;
                this.verificationError = '';
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
                        body: JSON.stringify({ email: this.verificationEmail })
                    });

                    const data = await response.json();
                    this.resending = false;

                    if (data.success) {
                        this.showToast(data.message || @json(__('A new verification code has been sent.')), 'success');
                        if (data.already_verified) {
                            this.verificationRequired = false;
                            this.resetOtp();
                        }
                        return;
                    }

                    this.verificationError = data.message || this.resendFailed;
                    this.showToast(this.verificationError);
                } catch (error) {
                    this.resending = false;
                    this.verificationError = error.message || this.networkError;
                    this.showToast(this.verificationError);
                }
            }
        };
    }
</script>
@endpush
@endsection
