@extends('layouts.admin')

@section('title', 'Admin Dashboard - Nutrio Meals')
@section('page_title', 'Dashboard Overview')

@section('content')
@php
    $fmt = fn($n) => $n >= 1000000 ? number_format($n/1000000, 2).'M' : ($n >= 1000 ? number_format($n/1000, 1).'K' : number_format($n));
@endphp

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    @foreach([
        ['label'=>'Total Users','value'=>number_format($stats['totalUsers']),'change'=>'+'.$stats['newUsersThisWeek'].' this week','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z','from'=>'brand-600','to'=>'brand-700','border'=>'brand-500','text'=>'brand-100','sub'=>'brand-200'],
        ['label'=>'Total Revenue','value'=>'SAR '.$fmt($stats['totalRevenue']),'change'=>'This week: SAR '.$fmt(0),'icon'=>'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','from'=>'accent-400','to'=>'accent-500','border'=>'accent-300','text'=>'accent-50','sub'=>'accent-100'],
        ['label'=>'Active Subscriptions','value'=>number_format($stats['activeSubscriptions']),'change'=>'Currently active','icon'=>'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15','from'=>'sky-500','to'=>'sky-600','border'=>'sky-400','text'=>'sky-100','sub'=>'sky-200'],
        ['label'=>'Total Meals','value'=>number_format($stats['totalMeals']),'change'=>'In catalog','icon'=>'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z','from'=>'violet-500','to'=>'violet-600','border'=>'violet-400','text'=>'violet-100','sub'=>'violet-200']
    ] as $card)
    <div class="card-sm bg-gradient-to-br from-{{ $card['from'] }} to-{{ $card['to'] }} rounded-xl border border-{{ $card['border'] }} p-4 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 bg-white/10 rounded-full -mr-8 -mt-8"></div>
        <div class="relative z-10">
            <div class="flex items-start justify-between mb-2">
                <span class="text-[10px] font-medium {{ $card['text'] }}">{{ $card['label'] }}</span>
                <svg class="w-4 h-4 {{ $card['sub'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
            <p class="text-xl font-bold tracking-tight text-white">{{ $card['value'] }}</p>
            <p class="text-[10px] {{ $card['sub'] }} font-medium mt-1">{{ $card['change'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Revenue Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl border p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Revenue Overview</h3>
                <p class="text-xs text-gray-400">Last 14 days</p>
            </div>
        </div>
        @php
            $dailyRevenue = array_fill(0, 14, 0);
            $revMax = max($dailyRevenue) ?: 1;
        @endphp
        <div class="flex items-end gap-[4px] h-44">
            @foreach($dailyRevenue as $i => $rev)
                @php $pct = min(100, ($rev / $revMax) * 100); $isToday = $i === count($dailyRevenue)-1; @endphp
                <div class="flex-1 flex flex-col items-center gap-1 group cursor-pointer">
                    <div class="w-full bg-gray-50 rounded-t-md relative h-36 overflow-hidden">
                        <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 {{ $isToday ? 'bg-brand-500' : 'bg-brand-300 hover:bg-brand-400' }}" style="height: {{ max($pct, 3) }}%"></div>
                    </div>
                    <span class="text-[9px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse('now')->subDays(13-$i)->format('d') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="bg-gradient-to-br from-brand-700 to-brand-800 rounded-xl border border-brand-600 p-5 text-white">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <h3 class="text-sm font-semibold text-white">This Month</h3>
        </div>
        <div class="space-y-4">
            <div>
                <p class="text-[10px] text-brand-300 uppercase tracking-wider font-medium">Revenue</p>
                <p class="text-2xl font-bold text-white mt-1">SAR 0</p>
            </div>
            <div>
                <p class="text-[10px] text-brand-300 uppercase tracking-wider font-medium">New Users</p>
                <p class="text-2xl font-bold text-white mt-1">{{ $stats['newUsersThisWeek'] }}</p>
            </div>
            <div class="pt-3 border-t border-brand-600">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-brand-400 rounded-full animate-pulse"></span>
                    <p class="text-[11px] text-brand-200">System operational</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Users Table --}}
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="px-5 py-4 border-b flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900">Recent Users</h3>
        <a href="#" class="text-xs font-medium text-brand-500 hover:text-brand-600">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 bg-gray-50/50">
                    <th class="px-5 py-2.5 font-medium">Name</th>
                    <th class="px-5 py-2.5 font-medium">Email</th>
                    <th class="px-5 py-2.5 font-medium">Role</th>
                    <th class="px-5 py-2.5 font-medium">Joined</th>
                </tr>
            </thead>
            <tbody>
                @forelse(\App\Models\User::latest()->take(5)->get() as $user)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors">
                    <td class="px-5 py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-brand-100 flex items-center justify-center text-brand-700 font-bold text-xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-medium text-gray-900">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-2.5 text-xs text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-2.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium {{ $user->isAdmin() ? 'bg-accent-50 text-accent-700 border border-accent-100' : 'bg-brand-50 text-brand-700 border border-brand-100' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-5 py-2.5 text-xs text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-8 text-center text-gray-400 text-xs">No users yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
