@extends('layouts.driver')

@section('title', __('Notifications') . ' - ' . __('Nutrio Meals'))

@section('content')
<div class="pb-4">
    {{-- Header --}}
    <div class="bg-gradient-to-br from-brand-700 to-brand-600 text-white p-5 rounded-b-3xl shadow-lg shadow-brand-700/20 animate-slide-up">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('driver.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-lg font-bold">{{ __('Notifications') }}</h1>
        </div>
        <p class="text-xs text-white/70">{{ count($notifications) }} {{ __('items') }}</p>
    </div>

    <div class="p-4 space-y-3">
        @forelse($notifications as $notification)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 animate-slide-up flex items-start gap-3 {{ $notification['is_read'] ? '' : 'ring-1 ring-brand-100' }}">
            <div class="w-10 h-10 rounded-full {{ $notification['is_read'] ? 'bg-gray-100 text-gray-400' : 'bg-gradient-to-br from-brand-700 to-brand-600 text-white' }} flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-gray-900">{{ $notification['title'] ?: __('Notification') }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $notification['message'] }}</p>
                @if($notification['created_at'])
                <p class="text-[10px] text-gray-400 mt-1.5">{{ \Illuminate\Support\Carbon::parse($notification['created_at'])->diffForHumans() }}</p>
                @endif
            </div>
            @unless($notification['is_read'])
            <form action="{{ route('driver.notifications.read', $notification['id']) }}" method="POST">
                @csrf
                <button type="submit" class="w-2.5 h-2.5 rounded-full bg-brand-600 flex-shrink-0 mt-1" title="{{ __('Mark as read') }}"></button>
            </form>
            @endunless
        </div>
        @empty
        <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 shadow-sm">
            <div class="w-14 h-14 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <p class="text-sm text-gray-500">{{ __('No notifications yet.') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
