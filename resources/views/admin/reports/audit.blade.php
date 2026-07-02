@extends('layouts.admin')

@section('title', 'Audit & Compliance Report - Nutrio Meals')
@section('page_title', 'Audit & Compliance Report')

@section('content')
@php $reportName = 'Audit & Compliance'; @endphp
@include('admin.reports._filter_bar')

<div class="hidden print:block mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nutrio Meals - Audit & Compliance Report</h1>
    <p class="text-sm text-gray-500">Generated: {{ $lastUpdated }} | Timezone: {{ $timezone }}</p>
    <div class="mt-4 border-2 border-gray-200 rounded-lg p-4">
        <p class="text-xs text-gray-500">This document contains privileged audit information. Distribution is restricted to authorized personnel only.</p>
        <p class="text-xs text-gray-500 mt-2">Trace ID: trc_{{ substr(md5($reportName), 0, 12) }} | Classification: CONFIDENTIAL</p>
    </div>
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

{{-- Change Hotspots + Access Control Info --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Change Hotspots --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-bold text-gray-900 mb-1">Change <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Hotspots by Module</span></h3>
        <span class="text-[10px] text-gray-400 block mb-4">Privileged action volume | Last 30 days</span>
        <div class="space-y-3">
            @foreach($changeHotspots as $spot)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-sm" style="background: {{ $spot['color'] }}"></span>
                        <span class="text-xs font-semibold text-gray-700">{{ $spot['module'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-gray-900">{{ $spot['changes'] }}</span>
                        <span class="text-[10px] text-gray-400">{{ $spot['pct'] }}%</span>
                    </div>
                </div>
                <div class="h-5 bg-gray-50 rounded-lg overflow-hidden">
                    <div class="h-full rounded-lg transition-all duration-500" style="width: {{ $spot['pct'] }}%; background: linear-gradient(90deg, {{ $spot['color'] }}, {{ $spot['color'] }}cc)"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Access Control --}}
    <div class="bg-gradient-to-br from-[#033133] to-[#01241f] rounded-xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-24 h-24 bg-[#259B00]/10 rounded-full -mr-12 -mt-12 blur-2xl"></div>
        <h3 class="text-sm font-bold mb-4 relative z-10">Access <span class="text-[#259B00]">Control</span></h3>
        <div class="space-y-3 relative z-10">
            <div class="flex items-center justify-between py-2 border-b border-white/5">
                <span class="text-xs text-white/60">reports.read</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#259B00]/20 text-[#259B00]">Granted</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/5">
                <span class="text-xs text-white/60">reports.export</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#259B00]/20 text-[#259B00]">Granted</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/5">
                <span class="text-xs text-white/60">audit.read</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#259B00]/20 text-[#259B00]">Granted</span>
            </div>
            <div class="flex items-center justify-between py-2 border-b border-white/5">
                <span class="text-xs text-white/60">Export Actions Audited</span>
                <span class="text-xs font-bold text-white">100%</span>
            </div>
            <div class="flex items-center justify-between py-2">
                <span class="text-xs text-white/60">Sensitive Fields Masked</span>
                <span class="text-xs font-bold text-[#259B00]">Yes</span>
            </div>
        </div>
    </div>
</div>

{{-- Audit Events Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-900">Audit <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Event Log</span></h3>
        <span class="text-[10px] text-gray-400">{{ count($auditEvents) }} events shown | Exportable</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">Event ID</th>
                    <th class="px-5 py-3 font-medium">Actor</th>
                    <th class="px-5 py-3 font-medium">Action</th>
                    <th class="px-5 py-3 font-medium">Module</th>
                    <th class="px-5 py-3 font-medium">Detail</th>
                    <th class="px-5 py-3 font-medium">IP</th>
                    <th class="px-5 py-3 font-medium">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach($auditEvents as $event)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $event['id'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-600 font-medium">{{ $event['actor'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold
                            @switch($event['action'])
                                @case('EXPORT_PDF') @case('EXPORT_EXCEL') bg-blue-50 text-blue-700 @break
                                @case('DELETE') bg-red-50 text-red-700 @break
                                @case('CREATE') bg-green-50 text-green-700 @break
                                @case('UPDATE') bg-amber-50 text-amber-700 @break
                                @case('REFUND') bg-purple-50 text-purple-700 @break
                                @case('SEND_CAMPAIGN') bg-indigo-50 text-indigo-700 @break
                                @case('UPDATE_ROLE') bg-red-50 text-red-700 @break
                                @default bg-gray-100 text-gray-600
                            @endswitch">{{ $event['action'] }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $event['module'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $event['detail'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-400 font-mono">{{ $event['ip'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $event['time'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Export History --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">Export <span class="bg-gradient-to-r from-[#033133] to-[#259B00] bg-clip-text text-transparent">Job History</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">Job ID</th>
                    <th class="px-5 py-3 font-medium">Report Type</th>
                    <th class="px-5 py-3 font-medium">Format</th>
                    <th class="px-5 py-3 font-medium">Requested By</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Size</th>
                    <th class="px-5 py-3 font-medium">Time</th>
                    <th class="px-5 py-3 font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exportHistory as $exp)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $exp['id'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-600">{{ $exp['type'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $exp['format'] === 'PDF' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">{{ $exp['format'] }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $exp['requested_by'] }}</td>
                    <td class="px-5 py-3">
                        @if($exp['status'] === 'completed')
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-700"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Completed</span>
                        @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-red-700"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Failed</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $exp['size'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $exp['time'] }}</td>
                    <td class="px-5 py-3">
                        @if($exp['status'] === 'completed')
                        <button class="text-[10px] font-bold text-[#259B00] hover:underline">Download</button>
                        @else
                        <button class="text-[10px] font-bold text-amber-600 hover:underline">Retry</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
