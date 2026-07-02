@extends('layouts.admin')

@section('title', __('Report Overview') . ' - ' . __('Nutrio Meals'))
@section('page_title', __('Report Overview'))

@section('content')
@php $reportName = __('Report Overview'); @endphp
@include('admin.reports._filter_bar')

{{-- Print Header (only visible when printing) --}}
<div class="hidden print:block mb-6">
    <h1 class="text-2xl font-bold text-gray-900">{{ __('Nutrio Meals') }} - {{ __('Report Overview') }} {{ __('Report') }}</h1>
    <p class="text-sm text-gray-500">{{ __('Generated') }}: {{ $lastUpdated }} | {{ __('Timezone') }}: {{ $timezone }}</p>
</div>

{{-- Executive KPI Row --}}
<div class="mb-6">
    <h3 class="text-sm font-bold text-gray-900 mb-3">{{ __('Executive') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('KPIs') }}</span></h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
        @foreach($kpis as $kpi)
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm kpi-card relative overflow-hidden">
            <div class="absolute top-0 right-0 w-16 h-16 rounded-full blur-2xl opacity-10" style="background: {{ $kpi['color'] }}"></div>
            <div class="flex items-center justify-between relative z-10">
                <span class="text-[10px] font-medium text-gray-400">{{ $kpi['label'] }}</span>
                @if($kpi['trend'] === 'up')
                <svg class="w-3.5 h-3.5 text-[#025C5F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                @else
                <svg class="w-3.5 h-3.5 text-[#173327]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                @endif
            </div>
            <div class="text-xl font-bold text-gray-900 mt-2 relative z-10">{{ $kpi['value'] }}</div>
            <span class="text-[10px] font-semibold mt-1 block relative z-10 {{ $kpi['trend'] === 'up' ? 'text-[#025C5F]' : 'text-[#173327]' }}">{{ $kpi['delta'] }} {{ __('vs prev period') }}</span>
        </div>
        @endforeach
    </div>
</div>

{{-- Revenue Line Chart + Subscription Funnel --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Revenue Trend with Comparison --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Revenue') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Trend') }}</span></h3>
                <span class="text-[10px] text-gray-400">SAR | {{ __('Last 6 months') }} | {{ __('Comparison') }}: {{ __('Current') }} vs {{ __('Previous') }}</span>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#6E7A25]"></span> {{ __('Current') }}</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-gray-300"></span> {{ __('Previous') }}</span>
            </div>
        </div>
        @php $revMax = max(array_merge($revenueTrend['current'], $revenueTrend['previous'])) ?: 500000; @endphp
        <div class="flex items-end gap-3 h-52">
            @foreach($revenueTrend['labels'] as $i => $label)
            @php $currPct = ($revenueTrend['current'][$i] / $revMax) * 100; $prevPct = ($revenueTrend['previous'][$i] / $revMax) * 100; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-44 overflow-hidden flex items-end justify-center gap-1">
                    <div class="w-1/2 rounded-t-md transition-all duration-300 bg-gradient-to-t from-[#6E7A25] to-[#6E7A25]/70 group-hover:opacity-80" style="height: {{ max($currPct, 4) }}%">
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap z-10">SAR {{ number_format($revenueTrend['current'][$i]) }}</div>
                    </div>
                    <div class="w-1/2 rounded-t-md transition-all duration-300 bg-gray-300 group-hover:opacity-70" style="height: {{ max($prevPct, 4) }}%"></div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Subscription Funnel --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-1">{{ __('Subscription') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Funnel') }}</span></h3>
        <span class="text-[10px] text-gray-400 block mb-4">{{ __('Visit') }} → {{ __('Trial') }} → {{ __('Subscribe') }} → {{ __('Renew') }}</span>
        <div class="space-y-3">
            @foreach($subscriptionFunnel as $i => $stage)
            @php $widthPct = $stage['pct']; $colors = ['#6E7A25', '#3b82f6', '#949B50', '#173327']; $color = $colors[$i] ?? '#6E7A25'; @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-semibold text-gray-700">{{ $stage['stage'] }}</span>
                    <span class="text-xs font-bold text-gray-900">{{ number_format($stage['count']) }}</span>
                </div>
                <div class="h-7 bg-gray-50 rounded-lg overflow-hidden relative">
                    <div class="h-full rounded-lg flex items-center px-2 transition-all duration-500" style="width: {{ max($widthPct, 5) }}%; background: linear-gradient(90deg, {{ $color }}, {{ $color }}cc)">
                        <span class="text-[10px] font-bold text-white">{{ $widthPct }}%</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Delivery SLA + Operational Metrics --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Delivery SLA Bar Chart --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">{{ __('Delivery') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('SLA by Zone') }}</span></h3>
                <span class="text-[10px] text-gray-400">{{ __('On-time %') }} | {{ __('Target') }}: 92%</span>
            </div>
        </div>
        <div class="space-y-4">
            @foreach($deliverySla as $zone)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold text-gray-700">{{ $zone['zone'] }}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold {{ $zone['onTime'] >= 92 ? 'text-green-600' : 'text-amber-600' }}">{{ $zone['onTime'] }}%</span>
                        <span class="text-[10px] text-gray-400">{{ $zone['total'] }} {{ __('deliveries') }}</span>
                    </div>
                </div>
                <div class="h-6 bg-gray-50 rounded-lg overflow-hidden relative">
                    <div class="h-full rounded-lg transition-all duration-500 {{ $zone['onTime'] >= 92 ? 'bg-gradient-to-r from-[#6E7A25] to-[#6E7A25]/70' : 'bg-gradient-to-r from-amber-500 to-amber-400' }}" style="width: {{ $zone['onTime'] }}%"></div>
                    <div class="absolute top-0 right-[8%] h-full w-0.5 bg-red-400/50"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Operational Metrics --}}
    <div class="bg-gradient-to-br from-[#173327] to-[#122620] rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#6E7A25]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <h3 class="text-sm font-bold mb-4 relative z-10">{{ __('Operational') }} <span class="text-[#6E7A25]">{{ __('Metrics') }}</span></h3>
        <div class="space-y-3 relative z-10">
            @foreach($operationalMetrics as $metric)
            <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                <span class="text-xs text-white/60">{{ $metric['label'] }}</span>
                <span class="text-sm font-bold" style="color: {{ $metric['color'] }}">{{ $metric['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Exceptions Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-900">{{ __('Exceptions') }} <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">{{ __('Log') }}</span></h3>
        <span class="text-[10px] text-gray-400">{{ count($exceptions) }} {{ __('active exceptions') }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">{{ __('ID') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Type') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Zone') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Detail') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Severity') }}</th>
                    <th class="px-5 py-3 font-medium">{{ __('Time') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exceptions as $exc)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $exc['id'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-600">{{ $exc['type'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $exc['zone'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $exc['detail'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $exc['severity'] === 'critical' ? 'bg-red-50 text-red-700' : ($exc['severity'] === 'warning' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">{{ __(ucfirst($exc['severity'])) }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $exc['time'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
