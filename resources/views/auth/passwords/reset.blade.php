@extends('layouts.auth')

@section('title', __('Verify OTP & Reset Password') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in">
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl">
        <div class="border-b border-gray-100 px-8 py-7 text-center">
            <img src="{{ asset('whitelogo.png') }}" alt="{{ config('app.name', 'NutrioMeals') }}" class="mx-auto h-20 w-auto object-contain">

            <h2 class="mt-4 text-2xl font-extrabold text-gray-900">{{ __('Check Your Email') }}</h2>
            <p class="mt-2 text-sm text-gray-500">{{ __('Enter the 6-digit OTP sent to') }}</p>
            <p class="mt-1 break-all text-sm font-extrabold text-[#173327]">{{ $email }}</p>
            <p class="mt-2 text-[11px] text-gray-400">{{ __('The OTP expires after 10 minutes.') }}</p>
        </div>

        <div class="p-8" x-data="{ loading: false, showPassword: false, showConfirmation: false }">
            @if(session('status'))
                <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5" @submit="loading = true">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="otp" class="mb-1.5 block text-sm font-semibold text-gray-700">{{ __('6-Digit OTP') }}</label>
                    <input id="otp" type="text" inputmode="numeric" name="otp" value="{{ old('otp') }}" required maxlength="6"
                        pattern="[0-9]{6}" autocomplete="one-time-code" autofocus placeholder="000000"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)"
                        class="w-full rounded-xl border @error('otp') border-red-300 @else border-gray-200 @enderror px-4 py-3 text-center font-mono text-xl font-black tracking-[.45em] outline-none focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/10">
                    @error('otp')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-semibold text-gray-700">{{ __('New Password') }}</label>
                    <div class="relative">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required minlength="6" maxlength="128"
                            autocomplete="new-password" placeholder="{{ __('Enter new password') }}"
                            class="w-full rounded-xl border @error('password') border-red-300 @else border-gray-200 @enderror px-4 py-3 pr-12 text-sm outline-none focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/10">
                        <button type="button" @click="showPassword=!showPassword" class="absolute inset-y-0 right-3 text-xs font-bold text-gray-400"
                            x-text="showPassword ? '{{ __('Hide') }}' : '{{ __('Show') }}'"></button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-gray-700">{{ __('Confirm New Password') }}</label>
                    <div class="relative">
                        <input id="password_confirmation" :type="showConfirmation ? 'text' : 'password'" name="password_confirmation" required minlength="6" maxlength="128"
                            autocomplete="new-password" placeholder="{{ __('Re-enter new password') }}"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 pr-12 text-sm outline-none focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/10">
                        <button type="button" @click="showConfirmation=!showConfirmation" class="absolute inset-y-0 right-3 text-xs font-bold text-gray-400"
                            x-text="showConfirmation ? '{{ __('Hide') }}' : '{{ __('Show') }}'"></button>
                    </div>
                </div>

                <button type="submit" :disabled="loading"
                    class="flex w-full items-center justify-center rounded-xl bg-[#173327] py-3 text-sm font-extrabold text-white hover:bg-[#214b35] disabled:opacity-60">
                    <span x-show="!loading">{{ __('Verify OTP & Reset Password') }}</span>
                    <span x-show="loading">{{ __('Resetting Password...') }}</span>
                </button>
            </form>

            <div class="mt-5 border-t border-gray-100 pt-5 text-center">
                <p class="text-xs text-gray-400">{{ __("Didn't receive the OTP?") }}</p>
                <form method="POST" action="{{ route('password.otp.resend') }}" class="mt-2">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-sm font-extrabold text-[#6E7A25] hover:underline">{{ __('Resend OTP') }}</button>
                </form>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-gray-400 hover:text-gray-600">
                    {{ __('Use a different email') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
