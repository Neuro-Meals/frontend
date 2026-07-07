@extends('errors.layout')

@section('title', __('Page Expired'))

@section('content')
<div class="text-center">
    <div class="relative inline-block mb-8">
        <div class="w-40 h-40 mx-auto rounded-full bg-white/10 backdrop-blur flex items-center justify-center animate-float animate-pulse-glow border border-white/20">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span class="absolute -top-2 -right-2 w-16 h-16 rounded-full bg-amber-500 text-white text-xl font-bold flex items-center justify-center border-4 border-emerald-900 shadow-lg animate-bounce">419</span>
    </div>

    <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-4 tracking-tight">{{ __('Session Expired') }}</h1>
    <p class="text-xl text-emerald-100 mb-2">{{ __('Your session has expired.') }}</p>
    <p class="text-emerald-200/70 mb-8 max-w-md mx-auto">{{ __('Please refresh the page and try again. This usually happens when a form is left open for too long.') }}</p>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <button onclick="location.reload()" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-emerald-900 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            {{ __('Refresh Page') }}
        </button>
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white/10 text-white rounded-xl font-semibold border border-white/20 hover:bg-white/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            {{ __('Login Again') }}
        </a>
    </div>
</div>
@endsection
