@extends('layouts.admin')

@section('title', __('Reports') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Reports & Analytics'))

@section('content')
@php
    $typeColors = [
        'Financial' => 'bg-green-50 text-green-700 border-green-200',
        'Analytics' => 'bg-blue-50 text-blue-700 border-blue-200',
        'Operations' => 'bg-amber-50 text-amber-700 border-amber-200',
    ];
    $fmt = fn($n) => $n >= 1000000 ? number_format($n/1000000, 2).'M' : ($n >= 1000 ? number_format($n/1000, 1).'K' : number_format($n));
@endphp

{{-- Stats Row --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Total Reports') }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['totalReports'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Generated This Month') }}</p>
        <p class="text-2xl font-bold text-[#6E7A25]">{{ $stats['generatedThisMonth'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Scheduled') }}</p>
        <p class="text-2xl font-bold text-blue-600">{{ $stats['scheduled'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
        <p class="text-xs text-gray-400 mb-1">{{ __('Avg Generation Time') }}</p>
        <p class="text-2xl font-bold text-gray-900">{{ $stats['avgGenTime'] }}</p>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Revenue Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Revenue Growth') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('Last 6 months') }}</p>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-[#6E7A25]"></span>
        </div>
        @php $revMax = max($chartData['revenue']) ?: 1; @endphp
        <div class="flex items-end gap-4 h-44">
            @foreach($chartData['revenue'] as $i => $rev)
                @php $pct = min(100, ($rev / $revMax) * 100); @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                    <div class="w-full relative h-36 flex items-end">
                        <div class="w-full rounded-t-lg bg-gradient-to-t from-[#6E7A25] to-[#6E7A25]/60 transition-all duration-300 group-hover:opacity-80" style="height: {{ max($pct, 5) }}%"></div>
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap">
                            SAR {{ $fmt($rev) }}
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $chartData['months'][$i] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Customer Growth --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Customer Growth') }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ __('Last 6 months') }}</p>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-[#173327]"></span>
        </div>
        @php $custMax = max($chartData['customers']) ?: 1; @endphp
        <div class="flex items-end gap-4 h-44">
            @foreach($chartData['customers'] as $i => $cust)
                @php $pct = min(100, ($cust / $custMax) * 100); @endphp
                <div class="flex-1 flex flex-col items-center gap-2 group cursor-pointer">
                    <div class="w-full relative h-36 flex items-end">
                        <div class="w-full rounded-t-lg bg-[#173327] transition-all duration-300 group-hover:opacity-80" style="height: {{ max($pct, 5) }}%"></div>
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md">
                            {{ $cust }}
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $chartData['months'][$i] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Reports Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-gray-900">{{ __('Generated Reports') }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('Download and manage reports') }}</p>
        </div>
        <button class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-[#173327] to-[#6E7A25] rounded-lg shadow-sm hover:shadow-md transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ __('Generate Report') }}
        </button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-400 bg-gray-50/50 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">{{ __('Report Name') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Type') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Period') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Format') }}</th>
                    <th class="px-6 py-3 font-medium">{{ __('Generated') }}</th>
                    <th class="px-6 py-3 font-medium text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-900">{{ $report['name'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold border {{ $typeColors[$report['type']] }}">{{ $report['type'] }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-500">{{ $report['period'] }}</td>
                    <td class="px-6 py-3.5">
                        <span class="text-xs font-medium {{ $report['format'] === 'PDF' ? 'text-red-500' : 'text-green-600' }}">{{ $report['format'] }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-xs text-gray-400">{{ date('M d, Y', strtotime($report['date'])) }}</td>
                    <td class="px-6 py-3.5 text-right">
                        <button class="text-gray-400 hover:text-[#6E7A25] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
