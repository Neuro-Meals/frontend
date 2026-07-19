@extends('layouts.user')

@section('title', __('Notifications') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Notifications'))

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20">
        <span class="text-[10px] font-medium text-white/60">{{ __('Unread') }}</span>
        <div class="text-2xl font-bold mt-1">{{ $stats['unread'] }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Total Notifications') }}</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Notifications List --}}
    <div class="lg:col-span-2 space-y-3">
        @foreach($notifications as $notif)
        <div class="bg-white rounded-xl border {{ $notif['read'] ? 'border-gray-100' : 'border-[#6E7A25]/30 ring-1 ring-[#6E7A25]/10' }} shadow-sm p-4 hover:shadow-md transition-all">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                    @switch($notif['type'])
                        @case('delivery') bg-[#025C5F]/10 text-[#025C5F] @break
                        @case('subscription') bg-[#6E7A25]/10 text-[#6E7A25] @break
                        @case('achievement') bg-[#949B50]/10 text-[#949B50] @break
                        @case('meal') bg-[#6E7A25]/10 text-[#6E7A25] @break
                        @case('payment') bg-green-50 text-green-600 @break
                        @default bg-gray-100 text-gray-500
                    @endswitch">
                    @switch($notif['type'])
                        @case('delivery')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"/></svg>
                            @break
                        @case('subscription')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            @break
                        @case('achievement')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            @break
                        @case('meal')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"/></svg>
                            @break
                        @case('payment')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @break
                        @default
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @endswitch
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h4 class="text-xs font-bold text-gray-900">{{ $notif['title'] }}</h4>
                        @if(!$notif['read'])
                        <span class="w-2 h-2 bg-[#6E7A25] rounded-full flex-shrink-0"></span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $notif['message'] }}</p>
                    <span class="text-[10px] text-gray-400 mt-2 block">{{ $notif['time'] }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Preferences --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h3 class="text-sm font-bold text-gray-900 mb-4">{{ __('Notification') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Preferences') }}</span></h3>
        <div class="space-y-3">
            @foreach($preferences as $pref)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-xs font-semibold text-gray-900">{{ $pref['name'] }}</p>
                    <p class="text-[10px] text-gray-400">{{ $pref['channel'] }}</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" {{ $pref['enabled'] ? 'checked' : '' }}>
                    <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#6E7A25] transition-colors"></div>
                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4"></div>
                </label>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
