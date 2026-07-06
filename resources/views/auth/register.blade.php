@extends('layouts.auth')

@section('title', __('Register') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in" x-data="registerForm()">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white px-8 py-8 text-center border-b border-gray-100">
            <div class="mx-auto mb-4 flex items-center justify-center">
                <img src="{{ asset('whitelogo.png') }}" alt="{{ config('app.name', 'Nitrio Meals') }}" class="h-20 w-auto object-contain">
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Create Account') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('Get started with') }} {{ config('app.name', 'Nitrio Meals') }}</p>
        </div>

        {{-- Form --}}
        <div class="p-8">

            {{-- Toast Notification --}}
            <div x-show="toast.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="fixed top-5 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full px-4" x-cloak>
                <div class="rounded-xl border shadow-xl p-4 flex items-start gap-3"
                     :class="toast.type === 'success' ? 'border-emerald-200 bg-white dark:bg-gray-900 dark:border-emerald-800/40' : 'border-red-200 bg-white dark:bg-gray-900 dark:border-red-800/40'">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                         :class="toast.type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-red-100 dark:bg-red-900/30'">
                        <svg x-show="toast.type === 'success'" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <svg x-show="toast.type !== 'success'" class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="toast.title"></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5 break-words" x-text="toast.message"></p>
                    </div>
                    <button @click="toast.show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors flex-shrink-0" aria-label="Close">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <form class="space-y-5" @submit.prevent="submit">

                {{-- First Name --}}
                <div>
                    <label for="first_name" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('First Name') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="first_name" type="text" x-model="form.first_name" required autocomplete="given-name" autofocus
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.first_name ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="John">
                    </div>
                </div>

                {{-- Last Name --}}
                <div>
                    <label for="last_name" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Last Name') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input id="last_name" type="text" x-model="form.last_name" required autocomplete="family-name"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.last_name ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="Doe">
                    </div>
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Phone') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <input id="phone" type="tel" x-model="form.phone" required autocomplete="tel" minlength="8"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.phone ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="+966 55 123 4567">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Email') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" x-model="form.email" required autocomplete="email"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.email || errors.general ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="name@example.com">
                    </div>
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
                        <input id="password" type="password" x-model="form.password" required autocomplete="new-password" minlength="6"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.password ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="Min. 6 characters">
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password-confirm" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ __('Confirm Password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input id="password-confirm" type="password" x-model="form.password_confirmation" required autocomplete="new-password"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border outline-none transition-all text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
                            :class="errors.password_confirmation ? 'border-red-300 ring-2 ring-red-100' : 'border-gray-200'"
                            placeholder="Re-enter your password">
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="loading"
                    class="w-full py-3 text-sm font-bold text-white rounded-lg shadow-md transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    :class="loading ? 'bg-gray-400' : 'bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light hover:shadow-lg'">
                    <svg x-show="!loading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <svg x-show="loading" class="animate-spin w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="loading ? pleaseWait : createAccount"></span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-400">or</span></div>
            </div>

            {{-- Login link --}}
            <p class="text-center text-sm text-gray-500">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">{{ __('Login') }}</a>
            </p>
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-gray-300">&copy; {{ date('Y') }} {{ config('app.name', 'Nitrio Meals') }}. All rights reserved.</p>
</div>

@push('scripts')
<script>
    function registerForm() {
        return {
            loading: false,
            toast: { show: false, message: '', type: 'error', title: '' },
            errors: {},
            pleaseWait: @json(__('Please wait...')),
            createAccount: @json(__('Create Account')),
            registrationFailed: @json(__('Registration failed')),
            successTitle: @json(__('Success')),
            networkError: @json(__('Network error. Please try again.')),
            registerUrl: @json(route('register')),
            form: {
                first_name: '',
                last_name: '',
                phone: '',
                email: '',
                password: '',
                password_confirmation: ''
            },
            showToast(message, type = 'error') {
                this.toast = {
                    show: true,
                    message: message,
                    type: type,
                    title: type === 'success' ? this.successTitle : this.registrationFailed
                };
                setTimeout(() => { this.toast.show = false }, 7000);
            },
            async submit() {
                this.loading = true;
                this.errors = {};
                this.toast.show = false;

                try {
                    const response = await fetch(this.registerUrl, {
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
                        this.showToast(data.message, 'success');
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                        return;
                    }

                    this.errors = data.errors || {};

                    const messages = [];
                    if (data.message) messages.push(data.message);
                    Object.values(this.errors).forEach(fieldErrors => {
                        if (Array.isArray(fieldErrors)) messages.push(...fieldErrors);
                        else messages.push(fieldErrors);
                    });

                    this.showToast(messages.length ? messages.join(' | ') : this.registrationFailed);
                } catch (error) {
                    this.loading = false;
                    this.showToast(error.message || this.networkError);
                }
            }
        };
    }
</script>
@endpush
@endsection
