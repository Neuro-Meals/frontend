@extends('layouts.admin')

@section('title', __('Subscription & Retention') . ' - Nutrio Meals')
@section('page_title', __('Subscription & Retention'))

@section('content')
@php $reportName = __('Subscription & Retention'); @endphp
@include('admin.reports._filter_bar')

<div class="hidden print:block mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nutrio Meals - Subscriptions & Retention Report</h1>
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

{{-- New vs Churn + Renewal Rate --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- New vs Churn Stacked Bar --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">New vs <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Churn</span></h3>
                <span class="text-[10px] text-gray-400">Subscriber count | Monthly</span>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#6E7A25]"></span> New</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-red-400"></span> Churned</span>
            </div>
        </div>
        @php $ncMax = max(array_merge($newVsChurn['new'], $newVsChurn['churn'])) ?: 50; @endphp
        <div class="flex items-end gap-3 h-48">
            @foreach($newVsChurn['labels'] as $i => $label)
            @php $newPct = ($newVsChurn['new'][$i] / $ncMax) * 100; $churnPct = ($newVsChurn['churn'][$i] / $ncMax) * 100; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden flex flex-col-reverse items-center">
                    <div class="w-full rounded-t-md transition-all duration-300 bg-gradient-to-t from-red-400 to-red-300 group-hover:opacity-80" style="height: {{ max($churnPct, 3) }}%"></div>
                    <div class="w-full transition-all duration-300 bg-gradient-to-t from-[#6E7A25] to-[#6E7A25]/70 group-hover:opacity-80" style="height: {{ max($newPct, 3) }}%">
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap z-10">+{{ $newVsChurn['new'][$i] }} / -{{ $newVsChurn['churn'][$i] }}</div>
                    </div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Renewal Rate Trend --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Renewal Rate <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Trend</span></h3>
                <span class="text-[10px] text-gray-400">Percentage | Monthly</span>
            </div>
        </div>
        @php $renMax = 100; @endphp
        <div class="flex items-end gap-3 h-48">
            @foreach($renewalTrend['labels'] as $i => $label)
            @php $renPct = $renewalTrend['rate'][$i]; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 bg-gradient-to-t from-[#949B50] to-[#949B50]/70 group-hover:opacity-80" style="height: {{ $renPct }}%">
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap z-10">{{ $renPct }}%</div>
                    </div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Goal Distribution Donut + Corporate Metrics --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- Goal Type Distribution --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-1">Goal Type <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Distribution</span></h3>
        <span class="text-[10px] text-gray-400 block mb-4">Subscriber goals breakdown</span>
        <div class="space-y-3">
            @php $totalGoals = array_sum(array_column($goalDistribution, 'count')); @endphp
            @foreach($goalDistribution as $goal)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-sm" style="background: {{ $goal['color'] }}"></span>
                        <span class="text-xs font-semibold text-gray-700">{{ $goal['goal'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-900">{{ $goal['count'] }}</span>
                        <span class="text-[10px] text-gray-400">{{ $goal['pct'] }}%</span>
                    </div>
                </div>
                <div class="h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full rounded-lg transition-all duration-500" style="width: {{ $goal['pct'] }}%; background: linear-gradient(90deg, {{ $goal['color'] }}, {{ $goal['color'] }}cc)"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Corporate Metrics --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#122620] rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#6E7A25]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <h3 class="text-sm font-bold mb-4 relative z-10">Corporate <span class="text-[#6E7A25]">Metrics</span></h3>
        <div class="grid grid-cols-2 gap-4 relative z-10">
            @foreach($corporateMetrics as $cm)
            <div class="border border-white/5 rounded-xl p-3">
                <span class="text-[10px] text-white/50 block">{{ $cm['label'] }}</span>
                <span class="text-xl font-bold block mt-1" style="color: {{ $cm['color'] }}">{{ $cm['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Plan Performance Ranking --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">Plan Performance <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Ranking</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">Rank</th>
                    <th class="px-5 py-3 font-medium">Plan</th>
                    <th class="px-5 py-3 font-medium">Subscribers</th>
                    <th class="px-5 py-3 font-medium">Revenue (SAR)</th>
                    <th class="px-5 py-3 font-medium">Retention</th>
                    <th class="px-5 py-3 font-medium">Churn</th>
                </tr>
            </thead>
            <tbody>
                @foreach($planRanking as $i => $plan)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg text-[10px] font-bold text-white" style="background: {{ $plan['color'] }}">{{ $i + 1 }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $plan['plan'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $plan['subscribers'] }}</td>
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ number_format($plan['revenue']) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full" style="background: {{ $plan['color'] }}; width: {{ $plan['retention'] }}%"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-700">{{ $plan['retention'] }}%</span>
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $plan['churn'] <= 2 ? 'bg-green-50 text-green-700' : ($plan['churn'] <= 3 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">{{ $plan['churn'] }}%</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
