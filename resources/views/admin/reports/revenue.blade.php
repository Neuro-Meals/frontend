@extends('layouts.admin')

@section('title', 'Revenue & Finance Report - Nutrio Meals')
@section('page_title', 'Revenue & Finance Report')

@section('content')
@php $reportName = 'Revenue & Finance'; @endphp
@include('admin.reports._filter_bar')

<div class="hidden print:block mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nutrio Meals - Revenue & Finance Report</h1>
    <p class="text-sm text-gray-500">Generated: {{ $lastUpdated }} | Timezone: {{ $timezone }}</p>
</div>

{{-- KPI Row --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
    @foreach($kpis as $kpi)
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm kpi-card relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-full blur-2xl opacity-10" style="background: {{ $kpi['color'] }}"></div>
        <div class="flex items-center justify-between relative z-10">
            <span class="text-[10px] font-medium text-gray-400">{{ $kpi['label'] }}</span>
            @if($kpi['trend'] === 'up')
            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            @else
            <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            @endif
        </div>
        <div class="text-lg font-bold text-gray-900 mt-2 relative z-10">{{ $kpi['value'] }}</div>
        <span class="text-[10px] font-semibold mt-1 block relative z-10 {{ $kpi['trend'] === 'up' ? 'text-green-600' : 'text-red-500' }}">{{ $kpi['delta'] }}</span>
    </div>
    @endforeach
</div>

{{-- Revenue Trend + Payment Trends --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- Revenue Trend with Comparison --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Revenue <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Trend</span></h3>
                <span class="text-[10px] text-gray-400">SAR | Current vs Previous Period</span>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#259B00]"></span> Current</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-gray-300"></span> Previous</span>
            </div>
        </div>
        @php $revMax = max(array_merge($revenueTrend['current'], $revenueTrend['previous'])) ?: 500000; @endphp
        <div class="flex items-end gap-3 h-48">
            @foreach($revenueTrend['labels'] as $i => $label)
            @php $currPct = ($revenueTrend['current'][$i] / $revMax) * 100; $prevPct = ($revenueTrend['previous'][$i] / $revMax) * 100; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden flex items-end justify-center gap-1">
                    <div class="w-1/2 rounded-t-md transition-all duration-300 bg-gradient-to-t from-[#259B00] to-[#259B00]/70 group-hover:opacity-80" style="height: {{ max($currPct, 4) }}%">
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap z-10">SAR {{ number_format($revenueTrend['current'][$i]) }}</div>
                    </div>
                    <div class="w-1/2 rounded-t-md transition-all duration-300 bg-gray-300 group-hover:opacity-70" style="height: {{ max($prevPct, 4) }}%"></div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Payment Success/Failure Trends --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Payment <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Success & Failure</span></h3>
                <span class="text-[10px] text-gray-400">Percentage | Monthly Trend</span>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#259B00]"></span> Success</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-red-400"></span> Failure</span>
            </div>
        </div>
        @php $payMax = 100; @endphp
        <div class="flex items-end gap-3 h-48">
            @foreach($paymentTrends['labels'] as $i => $label)
            @php $succPct = $paymentTrends['success'][$i]; $failPct = $paymentTrends['failure'][$i]; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden flex items-end justify-center gap-0.5">
                    <div class="w-1/2 rounded-t-md transition-all duration-300 bg-gradient-to-t from-[#259B00] to-[#259B00]/70 group-hover:opacity-80" style="height: {{ $succPct }}%">
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap z-10">{{ $succPct }}% success</div>
                    </div>
                    <div class="w-1/2 rounded-t-md transition-all duration-300 bg-gradient-to-t from-red-400 to-red-300 group-hover:opacity-80" style="height: {{ max($failPct * 10, 4) }}%"></div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Refund Volume + Revenue by Plan --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- Refund Volume --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Refund <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Volume</span></h3>
                <span class="text-[10px] text-gray-400">SAR | Monthly | Refund Ratio: 1.4%</span>
            </div>
        </div>
        @php $refMax = max($refundVolume['amount']) ?: 7000; @endphp
        <div class="flex items-end gap-3 h-40">
            @foreach($refundVolume['labels'] as $i => $label)
            @php $rPct = ($refundVolume['amount'][$i] / $refMax) * 100; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-32 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 bg-gradient-to-t from-red-500 to-red-400 group-hover:opacity-80" style="height: {{ max($rPct, 4) }}%">
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap">SAR {{ number_format($refundVolume['amount'][$i]) }} ({{ $refundVolume['count'][$i] }})</div>
                    </div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Revenue by Plan - Donut style --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-1">Revenue by <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Plan</span></h3>
        <span class="text-[10px] text-gray-400 block mb-4">Distribution | SAR</span>
        <div class="space-y-3">
            @php $totalRev = array_sum(array_column($revenueByPlan, 'revenue')); @endphp
            @foreach($revenueByPlan as $plan)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-sm" style="background: {{ $plan['color'] }}"></span>
                        <span class="text-xs font-semibold text-gray-700">{{ $plan['plan'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-900">SAR {{ number_format($plan['revenue']) }}</span>
                        <span class="text-[10px] text-gray-400">{{ $plan['pct'] }}%</span>
                    </div>
                </div>
                <div class="h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full rounded-lg transition-all duration-500" style="width: {{ $plan['pct'] }}%; background: linear-gradient(90deg, {{ $plan['color'] }}, {{ $plan['color'] }}cc)"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Payment Methods Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">Payment <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Methods Breakdown</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">Method</th>
                    <th class="px-5 py-3 font-medium">Transactions</th>
                    <th class="px-5 py-3 font-medium">Volume (SAR)</th>
                    <th class="px-5 py-3 font-medium">Share</th>
                    <th class="px-5 py-3 font-medium">Success Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paymentMethods as $pm)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $pm['method'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ number_format($pm['count']) }}</td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ number_format($pm['volume']) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#259B00] rounded-full" style="width: {{ $pm['pct'] }}%"></div>
                            </div>
                            <span class="text-xs text-gray-500">{{ $pm['pct'] }}%</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $pm['successRate'] >= 99 ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ $pm['successRate'] }}%</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
