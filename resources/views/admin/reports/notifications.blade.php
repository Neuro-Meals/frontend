@extends('layouts.admin')

@section('title', __('Notifications & Campaign') . ' - Nutrio Meals')
@section('page_title', __('Notifications & Campaign'))

@section('content')
@php $reportName = __('Notifications & Campaign'); @endphp
@include('admin.reports._filter_bar')

<div class="hidden print:block mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Nutrio Meals - Notifications & Campaign Report</h1>
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

{{-- Send Volume by Channel + Channel Mix --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    {{-- Send Volume Stacked Bar --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Send Volume by <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Channel</span></h3>
                <span class="text-[10px] text-gray-400">Notification count | Monthly | Stacked</span>
            </div>
            <div class="flex items-center gap-2 text-[10px] flex-wrap">
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#173327]"></span> Email</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#6E7A25]"></span> SMS</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#949B50]"></span> Push</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-[#8b5cf6]"></span> WhatsApp</span>
            </div>
        </div>
        @php $volMax = 0; for($i = 0; $i < count($sendVolumeByChannel['labels']); $i++) { $volMax = max($volMax, $sendVolumeByChannel['email'][$i] + $sendVolumeByChannel['sms'][$i] + $sendVolumeByChannel['push'][$i] + $sendVolumeByChannel['whatsapp'][$i]); } @endphp
        <div class="flex items-end gap-3 h-48">
            @foreach($sendVolumeByChannel['labels'] as $i => $label)
            @php
            $emailPct = ($sendVolumeByChannel['email'][$i] / $volMax) * 100;
            $smsPct = ($sendVolumeByChannel['sms'][$i] / $volMax) * 100;
            $pushPct = ($sendVolumeByChannel['push'][$i] / $volMax) * 100;
            $waPct = ($sendVolumeByChannel['whatsapp'][$i] / $volMax) * 100;
            $total = $sendVolumeByChannel['email'][$i] + $sendVolumeByChannel['sms'][$i] + $sendVolumeByChannel['push'][$i] + $sendVolumeByChannel['whatsapp'][$i];
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1.5 group cursor-pointer">
                <div class="w-full bg-gray-50 rounded-t-md relative h-40 overflow-hidden flex flex-col-reverse items-center">
                    <div class="w-full transition-all duration-300 bg-[#8b5cf6] group-hover:opacity-80" style="height: {{ max($waPct, 1) }}%"></div>
                    <div class="w-full transition-all duration-300 bg-[#949B50] group-hover:opacity-80" style="height: {{ max($pushPct, 1) }}%"></div>
                    <div class="w-full transition-all duration-300 bg-[#6E7A25] group-hover:opacity-80" style="height: {{ max($smsPct, 1) }}%"></div>
                    <div class="w-full rounded-t-md transition-all duration-300 bg-[#173327] group-hover:opacity-80" style="height: {{ max($emailPct, 1) }}%">
                        <div class="absolute -top-7 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-gray-900 text-white text-[10px] font-medium px-2 py-1 rounded-md whitespace-nowrap z-10">{{ number_format($total) }} total</div>
                    </div>
                </div>
                <span class="text-[10px] text-gray-400 font-medium">{{ $label }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Channel Mix Donut --}}
    <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
        <h3 class="text-sm font-bold text-gray-900 mb-1">Channel <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Mix</span></h3>
        <span class="text-[10px] text-gray-400 block mb-4">Distribution by channel</span>
        {{-- CSS Donut --}}
        @php
        $totalMix = array_sum(array_column($channelMix, 'count'));
        $donutSegments = '';
        $cumulative = 0;
        foreach($channelMix as $ch) {
            $pct = ($ch['count'] / $totalMix) * 100;
            $donutSegments .= $ch['color'] . ' 0 ' . $pct . '% ';
            $cumulative += $pct;
        }
        @endphp
        <div class="flex flex-col items-center">
            <div class="relative w-32 h-32 mb-4">
                <div class="w-full h-full rounded-full" style="background: conic-gradient({{ $channelMix[0]['color'] }} 0 {{ ($channelMix[0]['count']/$totalMix*100) }}%, {{ $channelMix[1]['color'] }} 0 {{ (($channelMix[0]['count']+$channelMix[1]['count'])/$totalMix*100) }}%, {{ $channelMix[2]['color'] }} 0 {{ (($channelMix[0]['count']+$channelMix[1]['count']+$channelMix[2]['count'])/$totalMix*100) }}%, {{ $channelMix[3]['color'] }} 0 100%)"></div>
                <div class="absolute inset-4 bg-white rounded-full flex flex-col items-center justify-center">
                    <span class="text-lg font-bold text-gray-900">{{ number_format($totalMix) }}</span>
                    <span class="text-[10px] text-gray-400">Total</span>
                </div>
            </div>
            <div class="space-y-2 w-full">
                @foreach($channelMix as $ch)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-sm" style="background: {{ $ch['color'] }}"></span>
                        <span class="text-xs font-medium text-gray-700">{{ $ch['channel'] }}</span>
                    </div>
                    <span class="text-xs font-bold text-gray-900">{{ $ch['pct'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Campaign Performance Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-50">
        <h3 class="text-sm font-bold text-gray-900">Campaign <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Performance</span></h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">Campaign</th>
                    <th class="px-5 py-3 font-medium">Channel</th>
                    <th class="px-5 py-3 font-medium">Sent</th>
                    <th class="px-5 py-3 font-medium">Opened</th>
                    <th class="px-5 py-3 font-medium">Clicked</th>
                    <th class="px-5 py-3 font-medium">CTR</th>
                    <th class="px-5 py-3 font-medium">Converted</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaignPerformance as $campaign)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $campaign['name'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600">{{ $campaign['channel'] }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ number_format($campaign['sent']) }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $campaign['opened'] > 0 ? number_format($campaign['opened']) : '—' }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ number_format($campaign['clicked']) }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-12 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-[#6E7A25] rounded-full" style="width: {{ min($campaign['ctr'] * 3, 100) }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-900">{{ $campaign['ctr'] }}%</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-xs font-bold text-[#6E7A25]">{{ $campaign['converted'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Failed Send Diagnostics --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-900">Failed Send <span class="bg-gradient-to-r from-[#173327] to-[#6E7A25] bg-clip-text text-transparent">Diagnostics</span></h3>
        <span class="text-[10px] text-gray-400">{{ count($failedDiagnostics) }} failures</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-xs text-gray-500 border-b border-gray-50">
                    <th class="px-5 py-3 font-medium">ID</th>
                    <th class="px-5 py-3 font-medium">Channel</th>
                    <th class="px-5 py-3 font-medium">Recipient</th>
                    <th class="px-5 py-3 font-medium">Reason</th>
                    <th class="px-5 py-3 font-medium">Campaign</th>
                    <th class="px-5 py-3 font-medium">Time</th>
                    <th class="px-5 py-3 font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($failedDiagnostics as $fail)
                <tr class="border-b border-gray-50 hover:bg-gray-50/30 transition-colors">
                    <td class="px-5 py-3 text-xs font-bold text-gray-900">{{ $fail['id'] }}</td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600">{{ $fail['channel'] }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500 font-mono">{{ $fail['recipient'] }}</td>
                    <td class="px-5 py-3 text-xs text-red-600 font-medium">{{ $fail['reason'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $fail['campaign'] }}</td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $fail['time'] }}</td>
                    <td class="px-5 py-3">
                        <button class="text-[10px] font-bold text-[#6E7A25] hover:underline">Retry</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
