@extends('layouts.admin')

@section('title', 'Delivery Operations Report - Nutrio Meals')
@section('page_title', 'Delivery Operations Report')

@section('content')
@php $reportName = 'Delivery Operations'; @endphp
@include('admin.reports._filter_bar')

<div class="hidden print:block mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nutrio Meals - Delivery Operations Report</h1>
    <p class="text-sm text-gray-500">Generated: {{ $lastUpdated }} | Timezone: {{ $timezone }}</p>
</div>

{{-- KPI Row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
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
        <div class="text-xl font-bold text-gray-900 mt-2 relative z-10">{{ $kpi['value'] }}</div>
        <span class="text-[10px] font-semibold mt-1 block relative z-10 {{ $kpi['trend'] === 'up' ? 'text-green-600' : 'text-red-500' }}">{{ $kpi['delta'] }}</span>
    </div>
    @endforeach
</div>

{{-- On-Time Trend + Zone Performance --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- On-Time Delivery Trend with Target Line --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">On-Time Delivery <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Trend</span></h3>
                <span class="text-[10px] text-gray-400">Percentage | Target: 92%</span>
            </div>
            <div class="flex items-center gap-3 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#6E7A25]"></span> Actual</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-0.5 bg-red-400"></span> Target</span>
            </div>
        </div>
        @php $slaMax = 100; @endphp
        <div class="flex items-end gap-3 h-48 relative">
            {{-- Target line --}}
            <div class="absolute left-0 right-0 border-t-2 border-dashed border-red-400/40 z-10" style="bottom: 92%"></div>
            @foreach($onTimeTrend['labels'] as $i => $label)
            @php $ratePct = $onTimeTrend['rate'][$i]; @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden">
                    <div class="absolute bottom-0 left-0 right-0 rounded-t-md transition-all duration-300 {{ $ratePct >= 92 ? 'bg-gradient-to-t from-[#6E7A25] to-[#6E7A25]/70' : 'bg-gradient-to-t from-amber-500 to-amber-400' }} group-hover:opacity-80" style="height: {{ $ratePct }}%">
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap z-10">{{ $ratePct }}%</div>
                    </div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Zone Performance Bars --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-1">Zone <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Performance</span></h3>
        <span class="text-[10px] text-gray-400 block mb-4">On-time % by delivery zone</span>
        <div class="space-y-4">
            @foreach($zonePerformance as $zone)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold text-gray-700">{{ $zone['zone'] }}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold {{ $zone['onTime'] >= 92 ? 'text-green-600' : 'text-amber-600' }}">{{ $zone['onTime'] }}%</span>
                        <span class="text-[10px] text-gray-400">{{ $zone['total'] }} total</span>
                    </div>
                </div>
                <div class="h-6 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full rounded-lg transition-all duration-500 {{ $zone['onTime'] >= 92 ? 'bg-gradient-to-r from-[#6E7A25] to-[#6E7A25]/70' : 'bg-gradient-to-r from-amber-500 to-amber-400' }}" style="width: {{ $zone['onTime'] }}%"></div>
                </div>
                <div class="flex items-center gap-3 mt-1 text-[10px] text-gray-400">
                    <span>Avg: {{ $zone['avgTime'] }}</span>
                    <span>Failed: {{ $zone['failed'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Delivery Heatmap --}}
<div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm mb-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm font-bold text-gray-900">Delivery Load <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Heatmap</span></h3>
            <span class="text-[10px] text-gray-400">Deliveries by weekday and hour</span>
        </div>
    </div>
    @php $heatMax = 0; foreach($deliveryHeatmap as $row) { $heatMax = max($heatMax, max($row['hours'])); } @endphp
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr>
                    <th class="px-2 py-1.5 text-left text-[10px] text-gray-400 font-medium">Day</th>
                    @foreach($heatmapHours as $hr)
                    <th class="px-2 py-1.5 text-center text-[10px] text-gray-400 font-medium">{{ $hr }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($deliveryHeatmap as $row)
                <tr>
                    <td class="px-2 py-1.5 text-[10px] font-semibold text-gray-600">{{ $row['day'] }}</td>
                    @foreach($row['hours'] as $val)
                    @php $intensity = $heatMax > 0 ? round($val / $heatMax * 100) : 0; @endphp
                    <td class="px-1 py-1">
                        <div class="h-8 rounded-md flex items-center justify-center text-[10px] font-bold transition-all cursor-pointer hover:ring-2 hover:ring-[#6E7A25]/30"
                             style="background: rgba(110,122,37,{{ max($intensity / 100, 0.05) }}); color: {{ $intensity > 50 ? '#fff' : '#666' }}">
                            {{ $val }}
                        </div>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-end gap-2 mt-3 text-[10px] text-gray-400">
        <span>Low</span>
        <div class="flex gap-0.5">
            <div class="w-4 h-3 rounded-sm" style="background: rgba(110,122,37,0.1)"></div>
            <div class="w-4 h-3 rounded-sm" style="background: rgba(110,122,37,0.3)"></div>
            <div class="w-4 h-3 rounded-sm" style="background: rgba(110,122,37,0.5)"></div>
            <div class="w-4 h-3 rounded-sm" style="background: rgba(110,122,37,0.7)"></div>
            <div class="w-4 h-3 rounded-sm" style="background: rgba(110,122,37,0.9)"></div>
        </div>
        <span>High</span>
    </div>
</div>

{{-- Exception Reasons + Driver Productivity --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Exception Reasons --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50">
            <h3 class="text-sm font-bold text-gray-900">Exception <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Reasons</span></h3>
        </div>
        <div class="p-5 space-y-3">
            @foreach($exceptionReasons as $reason)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold text-gray-700">{{ $reason['reason'] }}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-900">{{ $reason['count'] }}</span>
                        <span class="text-[10px] text-gray-400">{{ $reason['pct'] }}%</span>
                    </div>
                </div>
                <div class="h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full rounded-lg transition-all duration-500 bg-gradient-to-r from-red-500 to-red-400" style="width: {{ $reason['pct'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Driver Productivity --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-50">
            <h3 class="text-sm font-bold text-gray-900">Driver <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Productivity</span></h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                        <th class="px-4 py-3 font-medium">Driver</th>
                        <th class="px-4 py-3 font-medium">Deliveries</th>
                        <th class="px-4 py-3 font-medium">On-Time</th>
                        <th class="px-4 py-3 font-medium">Avg Time</th>
                        <th class="px-4 py-3 font-medium">Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($driverProductivity as $driver)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                        <td class="px-4 py-3 text-xs font-bold text-gray-900">{{ $driver['driver'] }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $driver['deliveries'] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $driver['onTime'] >= 95 ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ $driver['onTime'] }}%</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $driver['avgTime'] }}</td>
                        <td class="px-4 py-3 text-xs font-bold text-amber-500">{{ $driver['rating'] }} ★</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
