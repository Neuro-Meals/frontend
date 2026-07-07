@extends('errors.layout')

@section('title', __('Server Error'))

@section('content')
<div class="text-center">
    <div class="relative inline-block mb-8">
        <div class="w-40 h-40 mx-auto rounded-full bg-white/10 backdrop-blur flex items-center justify-center animate-float animate-pulse-glow border border-white/20">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <span class="absolute -top-2 -right-2 w-16 h-16 rounded-full bg-red-500 text-white text-2xl font-bold flex items-center justify-center border-4 border-emerald-900 shadow-lg animate-bounce">500</span>
    </div>

    <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-4 tracking-tight">{{ __('Oops!') }}</h1>
    <p class="text-xl text-emerald-100 mb-2">{{ __('Something went wrong') }}</p>
    <p class="text-emerald-200/70 mb-8 max-w-md mx-auto">{{ __('We are sorry, but something went wrong on our side. Please try again in a moment or contact support if the problem persists.') }}</p>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-emerald-900 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            {{ __('Back to Home') }}
        </a>
        <button onclick="location.reload()" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white/10 text-white rounded-xl font-semibold border border-white/20 hover:bg-white/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('Try Again') }}
        </button>
    </div>
</div>
@endsection
