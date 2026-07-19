@extends('layouts.user')

@section('title', __('Notifications') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Notifications'))

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-100 text-green-700 rounded-xl px-4 py-3 text-sm">
    {{ session('success') }}
</div>
@endif

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-xl p-4 text-white shadow-lg shadow-[#6E7A25]/20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <span class="text-[10px] font-medium text-white/60">{{ __('Unread') }}</span>
            <div class="text-2xl font-bold mt-1">{{ $stats['unread'] }}</div>
            <div class="text-[10px] text-white/50 mt-1">{{ $stats['total'] > 0 ? round(($stats['unread'] / $stats['total']) * 100) : 0 }}% {{ __('of total') }}</div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Total') }}</span>
        <div class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ __('all notifications') }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Read') }}</span>
        <div class="text-2xl font-bold text-green-600 mt-1">{{ $stats['total'] - $stats['unread'] }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ $stats['total'] > 0 ? round((($stats['total'] - $stats['unread']) / $stats['total']) * 100) : 0 }}% {{ __('read') }}</div>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <span class="text-[10px] font-medium text-gray-400">{{ __('Delivery') }}</span>
        <div class="text-2xl font-bold text-[#025C5F] mt-1">{{ $stats['byType']['delivery'] ?? 0 }}</div>
        <div class="text-[10px] text-gray-400 mt-1">{{ __('notifications') }}</div>
    </div>
</div>

{{-- Filter Tabs + Mark All --}}
<div class="flex items-center justify-between gap-3 mb-4 flex-wrap">
    <div class="flex items-center gap-2 flex-wrap">
        <button onclick="filterNotifs('all')" class="notif-filter-btn active px-3 py-1.5 rounded-lg text-xs font-bold bg-gradient-to-r from-[#173327] to-[#6E7A25] text-white shadow-sm transition-all" data-filter="all">
            {{ __('All') }} <span class="ml-1 opacity-70">{{ $stats['total'] }}</span>
        </button>
        <button onclick="filterNotifs('unread')" class="notif-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-600 hover:border-[#6E7A25] transition-all" data-filter="unread">
            {{ __('Unread') }} <span class="ml-1 opacity-70">{{ $stats['unread'] }}</span>
        </button>
        @if(($stats['byType']['delivery'] ?? 0) > 0)
        <button onclick="filterNotifs('delivery')" class="notif-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-600 hover:border-[#025C5F] transition-all" data-filter="delivery">
            {{ __('Delivery') }} <span class="ml-1 opacity-70">{{ $stats['byType']['delivery'] }}</span>
        </button>
        @endif
        @if(($stats['byType']['subscription'] ?? 0) > 0)
        <button onclick="filterNotifs('subscription')" class="notif-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-600 hover:border-[#6E7A25] transition-all" data-filter="subscription">
            {{ __('Subscription') }} <span class="ml-1 opacity-70">{{ $stats['byType']['subscription'] }}</span>
        </button>
        @endif
        @if(($stats['byType']['payment'] ?? 0) > 0)
        <button onclick="filterNotifs('payment')" class="notif-filter-btn px-3 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-600 hover:border-green-400 transition-all" data-filter="payment">
            {{ __('Payment') }} <span class="ml-1 opacity-70">{{ $stats['byType']['payment'] }}</span>
        </button>
        @endif
    </div>
    @if($stats['unread'] > 0)
    <form method="POST" action="{{ route('user.notifications.read-all') }}">
        @csrf
        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-600 hover:bg-green-50 hover:border-green-200 hover:text-green-700 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ __('Mark all read') }}
        </button>
    </form>
    @endif
</div>

{{-- Notifications List --}}
<div class="space-y-3">
    @if(!empty($notifications))
        @foreach($notifications as $notif)
        <div class="notif-item bg-white rounded-xl border {{ $notif['read'] ? 'border-gray-100' : 'border-[#6E7A25]/30 ring-1 ring-[#6E7A25]/10' }} shadow-sm p-4 hover:shadow-md transition-all" data-type="{{ $notif['type'] }}" data-read="{{ $notif['read'] ? '1' : '0' }}">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                    @switch($notif['type'])
                        @case('delivery') bg-[#025C5F]/10 text-[#025C5F] @break
                        @case('subscription') bg-[#6E7A25]/10 text-[#6E7A25] @break
                        @case('order') bg-[#949B50]/10 text-[#949B50] @break
                        @case('payment') bg-green-50 text-green-600 @break
                        @case('promotion') bg-purple-50 text-purple-600 @break
                        @default bg-gray-100 text-gray-500
                    @endswitch">
                    @switch($notif['type'])
                        @case('delivery')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1"/></svg>
                            @break
                        @case('subscription')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            @break
                        @case('order')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            @break
                        @case('payment')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @break
                        @case('promotion')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            @break
                        @default
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @endswitch
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <h4 class="text-xs font-bold text-gray-900 truncate">{{ $notif['title'] }}</h4>
                            @if(!$notif['read'])
                            <span class="w-2 h-2 bg-[#6E7A25] rounded-full flex-shrink-0 animate-pulse"></span>
                            @endif
                        </div>
                        @if(!$notif['read'])
                        <form method="POST" action="{{ route('user.notifications.read', $notif['id']) }}" class="flex-shrink-0">
                            @csrf
                            <button type="submit" class="text-[10px] font-bold text-[#6E7A25] hover:text-[#173327] transition-colors whitespace-nowrap">
                                {{ __('Mark read') }}
                            </button>
                        </form>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ $notif['message'] }}</p>
                    <div class="flex items-center gap-2 mt-2 flex-wrap">
                        <span class="text-[10px] text-gray-400">{{ $notif['time'] }}</span>
                        <span class="text-gray-200">·</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-semibold
                            @switch($notif['type'])
                                @case('delivery') bg-[#025C5F]/10 text-[#025C5F] @break
                                @case('subscription') bg-[#6E7A25]/10 text-[#6E7A25] @break
                                @case('order') bg-[#949B50]/10 text-[#949B50] @break
                                @case('payment') bg-green-50 text-green-600 @break
                                @case('promotion') bg-purple-50 text-purple-600 @break
                                @default bg-gray-100 text-gray-500
                            @endswitch
                        ">{{ ucfirst($notif['type']) }}</span>
                        @if($notif['channel'] && $notif['channel'] !== 'in_app')
                        <span class="text-[9px] text-gray-400 uppercase tracking-wide">{{ $notif['channel'] }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center">
        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-[#6E7A25]/10 to-[#173327]/10 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-[#6E7A25]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        </div>
        <p class="text-sm font-bold text-gray-900">{{ __('No notifications') }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ __('You\'ll be notified about deliveries, subscriptions, and more.') }}</p>
    </div>
    @endif
</div>

<script>
function filterNotifs(filter) {
    const items = document.querySelectorAll('.notif-item');
    const buttons = document.querySelectorAll('.notif-filter-btn');

    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-gradient-to-r', 'from-[#173327]', 'to-[#6E7A25]', 'text-white', 'shadow-sm');
        btn.classList.add('bg-white', 'border', 'border-gray-200', 'text-gray-600');
    });

    event.target.closest('button').classList.add('active', 'bg-gradient-to-r', 'from-[#173327]', 'to-[#6E7A25]', 'text-white', 'shadow-sm');
    event.target.closest('button').classList.remove('bg-white', 'border', 'border-gray-200', 'text-gray-600');

    items.forEach(item => {
        if (filter === 'all') {
            item.style.display = '';
        } else if (filter === 'unread') {
            item.style.display = item.dataset.read === '0' ? '' : 'none';
        } else {
            item.style.display = item.dataset.type === filter ? '' : 'none';
        }
    });
}
</script>

@endsection
