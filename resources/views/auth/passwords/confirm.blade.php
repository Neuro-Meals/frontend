@extends('layouts.auth')

@section('title', __('Confirm Password') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="w-full max-w-md animate-simple-fade-in">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white px-8 py-8 text-center border-b border-gray-100">
            <div class="mx-auto mb-4 flex items-center justify-center">
                <img src="{{ asset('whitelogo.png') }}" alt="{{ config('app.name', 'Nitromeals') }}" class="h-20 w-auto object-contain">
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">{{ __('Confirm Password') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('Secure area - please verify') }}</p>
        </div>

        {{-- Form --}}
        <div class="p-8">
            <p class="text-gray-600 text-center mb-6">Please confirm your password before continuing.</p>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="w-full pl-11 pr-4 py-2.5 rounded-lg border @error('password') border-red-300 ring-2 ring-red-100 @else border-gray-200 @enderror focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 outline-none transition-all text-sm"
                            placeholder="Enter your password">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full py-3 text-sm font-bold text-white bg-gradient-to-r from-brand-light to-brand-dark hover:from-brand-dark hover:to-brand-light rounded-lg shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Confirm Password
                </button>
            </form>

            @if (Route::has('password.request'))
                <div class="mt-4 text-center">
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition-colors">Forgot your password?</a>
                </div>
            @endif
        </div>
    </div>

    <p class="mt-6 text-center text-xs text-gray-300">&copy; {{ date('Y') }} {{ config('app.name', 'Nitromeals') }}. All rights reserved.</p>
</div>
@endsection
