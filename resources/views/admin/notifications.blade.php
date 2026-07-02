@extends('layouts.admin')

@section('title', 'Notifications - Nutrio Meals')
@section('page_title', 'Notifications')

@section('content')
@php
    $typeIcons = [
        'subscription' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
        'delivery' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z',
        'payment' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'customer' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'reminder' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'digest' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
    ];
    $typeColors = [
        'subscription' => 'from-[#6E7A25] to-[#173327]',
        'delivery' => 'from-blue-500 to-blue-700',
        'payment' => 'from-amber-400 to-orange-500',
        'customer' => 'from-violet-500 to-purple-700',
        'reminder' => 'from-teal-400 to-teal-600',
        'digest' => 'from-rose-400 to-rose-600',
    ];
    $statusColors = [
        'sent' => 'bg-green-50 text-green-700 border-green-200',
        'failed' => 'bg-red-50 text-red-700 border-red-200',
        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
    ];
    $channelIcons = [
        'email' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'sms' => 'M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z',
        'whatsapp' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        'push' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5',
    ];
@endphp

{{-- KPI Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="kpi-card bg-gradient-to-br from-[#173327] to-[#6E7A25] rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-[#6E7A25]/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Total Sent</p>
            <p class="text-2xl font-bold tracking-tight">{{ number_format($stats['totalSent']) }}</p>
            <p class="text-xs text-white/50 mt-1">{{ $stats['todaySent'] }} today</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-blue-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Delivery Rate</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['deliveryRate'] }}%</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-amber-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Open Rate</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['openRate'] }}%</p>
        </div>
    </div>
    <div class="kpi-card bg-gradient-to-br from-violet-500 to-purple-700 rounded-2xl p-5 text-white relative overflow-hidden shadow-lg shadow-violet-500/20">
        <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
        <div class="relative z-10">
            <p class="text-xs text-white/60 font-medium mb-1">Failed / Pending</p>
            <p class="text-2xl font-bold tracking-tight">{{ $stats['failed'] }} / {{ $stats['pending'] }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Notifications Log --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900">Recent <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Notifications</span></h3>
                <p class="text-xs text-gray-400 mt-0.5">Latest notification activity</p>
            </div>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($notifications as $notif)
            <div class="px-6 py-4 hover:bg-gray-50/30 transition-colors">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $typeColors[$notif['type']] ?? 'from-gray-400 to-gray-600' }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeIcons[$notif['type']] ?? 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5' }}"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $notif['title'] }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $statusColors[$notif['status']] }} flex-shrink-0">{{ ucfirst($notif['status']) }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $notif['message'] }}</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="flex items-center gap-1 text-[10px] text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $channelIcons[$notif['channel']] ?? $channelIcons['email'] }}"/></svg>
                                {{ ucfirst($notif['channel']) }}
                            </span>
                            <span class="text-[10px] text-gray-400">{{ $notif['recipient'] }}</span>
                            <span class="text-[10px] text-gray-400">·</span>
                            <span class="text-[10px] text-gray-400">{{ $notif['time'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Templates --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="mb-5">
            <h3 class="text-base font-bold text-gray-900">Notification <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Templates</span></h3>
            <p class="text-xs text-gray-400 mt-0.5">Active message templates</p>
        </div>
        <div class="space-y-3">
            @foreach($templates as $template)
            <div class="p-3 rounded-xl border border-gray-100 hover:shadow-sm transition-all">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-semibold text-gray-900">{{ $template['name'] }}</p>
                    <span class="text-[10px] font-bold text-[#6E7A25] uppercase">{{ $template['type'] }}</span>
                </div>
                <p class="text-[10px] text-gray-400">{{ $template['trigger'] }}</p>
                <p class="text-[10px] text-gray-500 mt-1">{{ number_format($template['sends']) }} sends</p>
            </div>
            @endforeach
        </div>
        <button class="w-full mt-4 px-4 py-2 text-xs font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Template
        </button>
    </div>
</div>
@endsection
