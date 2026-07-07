@extends('errors.layout')

@section('title', __('Forbidden'))

@section('content')
<div class="text-center">
    <div class="relative inline-block mb-8">
        <div class="w-40 h-40 mx-auto rounded-full bg-white/10 backdrop-blur flex items-center justify-center animate-float animate-pulse-glow border border-white/20">
            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <span class="absolute -top-2 -right-2 w-16 h-16 rounded-full bg-amber-500 text-white text-2xl font-bold flex items-center justify-center border-4 border-emerald-900 shadow-lg animate-bounce">403</span>
    </div>

    <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-4 tracking-tight">{{ __('Access Denied') }}</h1>
    <p class="text-xl text-emerald-100 mb-2">{{ __('You do not have permission to access this page.') }}</p>
    <p class="text-emerald-200/70 mb-8 max-w-md mx-auto">{{ __('If you believe this is a mistake, please contact the administrator or return to the homepage.') }}</p>

    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-emerald-900 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            {{ __('Back to Home') }}
        </a>
    </div>
</div>
@endsection
