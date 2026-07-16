@extends('layouts.driver')

@section('title', __('Profile') . ' - ' . __('Nutrio Meals'))

@section('content')
@php
    $driverName = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?: ($user['email'] ?? __('Driver'));
@endphp

<div class="pb-4">
    {{-- Header --}}
    <div class="relative bg-gradient-to-br from-brand-700 to-brand-800 text-white px-5 pt-8 pb-12 rounded-b-[2.5rem] shadow-lg shadow-brand-700/20 overflow-hidden animate-slide-up text-center">
        <svg class="absolute -bottom-4 -right-4 w-32 h-32 text-white/5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8 6 6 9 6 13a6 6 0 0012 0c0-4-2-7-6-11z"/></svg>
        <div class="w-20 h-20 rounded-full bg-white/15 border-2 border-white/25 flex items-center justify-center text-2xl font-extrabold mx-auto mb-3">
            {{ strtoupper(substr($driverName, 0, 1)) }}
        </div>
        <h1 class="text-lg font-bold">{{ $driverName }}</h1>
        <p class="text-xs text-white/70 mt-0.5">{{ $user['email'] ?? '' }}</p>
        <div class="flex items-center justify-center gap-2 mt-3">
            <span class="px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-bold">{{ $driverCode }}</span>
            <span class="px-3 py-1 rounded-full bg-white/15 border border-white/20 text-xs font-bold flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $primaryZone }}
            </span>
        </div>
    </div>

    <div class="p-4 -mt-8 relative z-10 space-y-4">
        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-3 animate-slide-up animate-delay-1">
            <div class="bg-white rounded-2xl p-4 shadow-md border border-gray-100 text-center">
                <p class="text-2xl font-extrabold text-green-600">{{ $totalDelivered }}</p>
                <p class="text-xs text-gray-400 font-semibold mt-0.5">{{ __('Delivered') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-md border border-gray-100 text-center">
                <p class="text-2xl font-extrabold text-red-500">{{ $totalFailed }}</p>
                <p class="text-xs text-gray-400 font-semibold mt-0.5">{{ __('Failed') }}</p>
            </div>
        </div>

        {{-- Contact info --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up animate-delay-2">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">{{ __('Contact Information') }}</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="text-xs text-gray-700 break-all">{{ $user['email'] ?? __('Not provided') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-brand-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <p class="text-xs text-gray-700">{{ $user['phone'] ?? ($user['mobile'] ?? __('Not provided')) }}</p>
                </div>
            </div>
        </div>

        {{-- Links --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-slide-up animate-delay-3">
            <a href="{{ route('driver.deliveries') }}" class="flex items-center justify-between p-4 border-b border-gray-50 hover:bg-gray-50 transition-colors">
                <span class="flex items-center gap-3 text-sm font-semibold text-gray-800">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 001 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1"/></svg>
                    {{ __('My Deliveries') }}
                </span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            <a href="{{ route('driver.notifications') }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                <span class="flex items-center gap-3 text-sm font-semibold text-gray-800">
                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    {{ __('Notifications') }}
                </span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="animate-slide-up animate-delay-4">
            @csrf
            <button type="submit" class="btn-action w-full flex items-center justify-center gap-2 py-3.5 rounded-2xl bg-red-50 text-red-600 border border-red-100 text-sm font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                {{ __('Logout') }}
            </button>
        </form>
    </div>
</div>
@endsection
