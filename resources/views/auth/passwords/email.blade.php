@extends('layouts.auth')

@section('title', __('Forgot Password') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in">
    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl">
        <div class="border-b border-gray-100 px-8 py-8 text-center">
            <img src="{{ asset('whitelogo.png') }}" alt="{{ config('app.name', 'NutrioMeals') }}" class="mx-auto h-20 w-auto object-contain">
            <h2 class="mt-4 text-2xl font-extrabold text-gray-900">{{ __('Forgot Password?') }}</h2>
            <p class="mt-2 text-sm leading-relaxed text-gray-500">
                {{ __('Enter your account email. We will send a 6-digit OTP that is valid for 10 minutes.') }}
            </p>
        </div>

        <div class="p-8" x-data="{ loading: false }">
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5" @submit="loading = true">
                @csrf
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-700">{{ __('Email Address') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        class="w-full rounded-xl border @error('email') border-red-300 @else border-gray-200 @enderror px-4 py-3 text-sm outline-none focus:border-[#6E7A25] focus:ring-2 focus:ring-[#6E7A25]/10"
                        placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" :disabled="loading"
                    class="flex w-full items-center justify-center rounded-xl bg-[#173327] py-3 text-sm font-extrabold text-white hover:bg-[#214b35] disabled:opacity-60">
                    <span x-show="!loading">{{ __('Send Reset OTP') }}</span>
                    <span x-show="loading">{{ __('Sending OTP...') }}</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#6E7A25] hover:underline">
                    ← {{ __('Back to Login') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
